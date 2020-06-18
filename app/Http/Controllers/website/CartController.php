<?php

namespace App\Http\Controllers\website;

use App\Coupon;
use App\EmailCollect;
use App\Helpers\CalendarHelper;
use App\Helpers\CreditCardHelper;
use App\Helpers\PriceHelper;
use App\Helpers\Toolkit;
use App\Helpers\UnionPayHelper;
use App\Emailset;
use App\Http\Controllers\Controller;
use App\Jobs\ProcessOrderJob;
use App\Lock;
use App\MailAddress;
use App\Nationality;
use App\Order;
use App\OrderProduct;
use App\OrderProductPackage;
use App\OrderProductTurbojet;
use App\PaymentHistory;
use App\Quota;
use App\Sitelogo;
use App\Staticpage;
use App\TicketList;
use App\TicketPrice;
use App\TicketPricegroup;
use App\TicketQuota;
use App\TourList;
use App\TourPrice;
use App\TourPricegroup;
use App\Transactioncustomer;
use App\Transactionorder;
use App\TransactionOrderlist;
use App\Transactionprice;
use App\Transactionproduct;
use App\TransactionProductlist;
use App\TransportationList;
use App\TransportationPrice;
use App\TransportationPricegroup;
use App\TransportationQuota;
use App\TurbojetTicket;
use Carbon\Carbon;
use Faker\Provider\at_AT\Payment;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Cart;

class CartController extends Controller
{

    public function add(Requests\AddCartRequest $request)
    {
        $type = $request->input('type');
        $price_group_id = $request->input('price-group-id');

        if ($type == 'tour')
        {
            $price_group = TourPricegroup::where('id', $price_group_id)->where('status', 'active')->first();
            $product = $price_group->TourList()->where('status', 'active')->first();
            $prices = $price_group->TourPrice()->where('status', 'active')->get();

            if ($product->post == TourList::POST_Private && $product->payment_status != TourList::Payment_STATUS_NOTPAID)
                abort(404);
        }
        else if ($type == 'ticket')
        {
            $price_group = TicketPricegroup::where('id', $price_group_id)->where('status', 'active')->first();
            $product = $price_group->TicketList()->where('status', 'active')->first();
            $prices = $price_group->TicketPrice()->where('status', 'active')->get();
        }
        else if ($type == 'transportation')
        {
            $price_group = TransportationPricegroup::where('id', $price_group_id)->where('status', 'active')->first();
            $product = $price_group->TransportationList()->where('status', 'active')->first();
            $prices = $price_group->TransportationPrice()->where('status', 'active')->get();

            if ($product->post == TransportationList::POST_Private && $product->payment_status != TransportationList::Payment_STATUS_NOTPAID)
                abort(404);

            if ($product->isTurbojetType() && !$price_group->turbojet_ticket)
                abort(404);
        }
        else
            abort(404);

        $rules =  [
            'date' => 'required|date',
            'hotel' => 'required|max:255',
            'title' => 'required|in:' . trans('reserve.mr') . ','  . trans('reserve.mrs') . ','  . trans('reserve.miss'),
            'first-name' => 'required|max:255',
            'last-name' => 'required|max:255',
            'passport' => 'required|integer',
            'email' => 'required|email',
            'terms' => 'required',
            'telephone' => 'numeric',
        ];

        if ($type == 'transportation' && !$product->isTurbojetType() && $price_group->time_table == 'On')
            $rules['travel-time'] = 'required';

        if ($type == 'transportation' && $product->isTurbojetType()) {
            $rules['date'] = 'date';
            $rules['class'] = 'required|in:economy,super,primer-grand';

            if ($price_group->turbojet_ticket->departure_city == TurbojetTicket::DEPARTURE_CITY_ANY)
                $rules['departure_city'] = 'required|in:city_1,city_2';

            $rules['tickets'] = 'required|integer';
            $rules['departure-date'] = 'required|date';
            $rules['departure-time'] = 'required';

            if ($price_group->turbojet_ticket->type == TurbojetTicket::TYPE_ROUND_TRIP)
            {
                $rules['return-date'] = 'required|date';
                $rules['return-time'] = 'required';
            }
        }

        // Combo Type
        if ($type == 'tour' && $product->isTurbojetType()) {
            $rules['class'] = 'required|in:economy,super,primer-grand';

            if ($price_group->turbojet_ticket->departure_city == TurbojetTicket::DEPARTURE_CITY_ANY)
                $rules['departure_city'] = 'required|in:city_1,city_2';

            $rules['departure-date'] = 'required|date';
            $rules['departure-time'] = 'required';

            if ($price_group->turbojet_ticket->type == TurbojetTicket::TYPE_ROUND_TRIP)
            {
                $rules['return-date'] = 'required|date';
                $rules['return-time'] = 'required';
            }
        }

        $this->validate($request, $rules, [], [
            'date' => $type == 'tour' ? trans('reserve.tour-date') : trans('reserve.travel-date'),
            'hotel' => trans('reserve.hotel'),
            'title' => trans('reserve.title'),
            'first-name' => trans('reserve.first-name'),
            'last-name' => trans('reserve.last-name'),
            'passport' => trans('reserve.passport'),
            'email' => trans('reserve.email'),
            'telephone' => trans('reserve.tel'),
        ]);

        // Custom Validation
        $nationality = Nationality::find($request->input('passport'));
        if (!$nationality)
            return back()->withInput()->withErrors(['passport' => 'Invalid nationality']);

        $quantity_data = [];
        foreach ($prices as $price)
        {
            if ($request->has('subqty' . $price->id))
                $quantity_data[$price->id] = intval($request->input('subqty' . $price->id));
        }

        if ($type == 'transportation' && $product->isTurbojetType()) {
            if (!$request->has('departure-flight-no'))
                return back()->withInput()->withErrors(['departure-inventory' => 'Please select flight no']);

            if ($price_group->turbojet_ticket->type == TurbojetTicket::TYPE_ROUND_TRIP && !$request->has('return-flight-no'))
                return back()->withInput()->withErrors(['return-inventory' => 'Please select flight no']);
        }

        if ($type == 'tour')
            $price_data = PriceHelper::getTourPrice($price_group_id, $request->input('date'), trim($request->input('promocode')), $quantity_data);
        else if ($type == 'ticket')
            $price_data = PriceHelper::getTicketPrice($price_group_id, $request->input('date'), $quantity_data);
        else if ($type == 'transportation') {
            if ($product->isTurbojetType())
                $price_data = PriceHelper::getTurbojetPrice($price_group, $request);
            else
                $price_data = PriceHelper::getTransportationPrice($price_group_id, $request->input('date'), $quantity_data);
        }

        if ($price_data['total-price'] != $request->input('total-price-ver'))
            abort(400, 'Invalid Request');

        if (!$price_data['buyable'])
            abort(400, 'Invalid Request');

        if ($price_data['total-quantity'] == 0)
            return back()->withInput()->withErrors(['inventory' => 'Please select inventory']);

        if (floatval($price_data['total-price']) == floatval(0))
            return back()->withInput()->withErrors(['add-to-cart' => trans('reserve.amount-error')]);

        $name = $request->input('first-name') . ' ' . $request->input('last-name');
        if (!\App::isLocale('en'))
            $name = $request->input('last-name') . $request->input('last-name');

        // inserting data to email collect to send advertisement
        if ($request->has('info'))
        {
            $emailCheck = EmailCollect::where('email',$request['email'])->count();
            if ($emailCheck == 0) {
                EmailCollect::create([
                    'title' => $request->title,
                    'name' => $name,
                    'email' => $request->input('email'),
                ]);
            }
        }

        // Add to Cart
        $option = [
            'type' => $type,
            'product-id' => $product->id,
            'price-group-id' => $price_group_id,
            'product-title' => $product->title,
            'product-image' => $product->image,
            'product-description' => $product->description,
            'product-price-group-title' => $price_group->title,
            'date' => $request->input('date'),
            'hotel' => trim($request->input('hotel')),
            'title' => $request->input('title'),
            'first-name' => trim($request->input('first-name')),
            'last-name' => trim($request->input('last-name')),
            'passport' => $request->input('passport'),
            'email' => trim($request->input('email')),
            'country-code' => '+' . trim(str_replace('+', '', $request->input('country-code'))),
            'telephone' => trim($request->input('telephone')),
            'remark' => trim($request->input('remark')),
            'language' => \App::getLocale(),
            'is-private' => false,
            'quantity-data' => $quantity_data,
            'price-data' => $price_data,
            'turbojet' => false,
        ];

        if ($type == 'tour')
        {
            if ($price_data['discount_type'] == 'price' || $price_data['discount_type'] == 'percentage')
                $option['promocode'] = trim($request->input('promocode'));

            if ($product->post == TourList::POST_Private)
                $option['is-private'] = true;
        }

        if ($type == 'transportation')
        {
            if ($product->post == TransportationList::POST_Public)
                $option['is-private'] = true;
        }

        if ($type == 'transportation' && $price_group->time_table == 'On')
            $option['travel-time'] = $request->input('travel-time');

        if ($type == 'transportation' && $product->isTurbojetType()) {
            $option['class'] = $request->input('class');
            $option['date'] = $request->input('departure-date');
            $option['turbojet'] = true;
        }

        // Combo
        if ($type == 'tour' && $product->isTurbojetType()) {
            $option['class'] = $request->input('class');

            $option['turbojet-tickets'] = [];
            $option['departure-datetime'] = Carbon::parse($request->input('departure-date') . ' ' . $request->input('departure-time'));

            $option['turbojet-tickets'][] = [
                'from-code' => $request->input('departure-city-code'),
                'from-name' => $request->input('departure-city-name'),
                'to-code' => $request->input('return-city-code'),
                'to-name' => $request->input('return-city-name'),
                'time' => $option['departure-datetime']->format('Y-m-d H:i'),
            ];

            if ($price_group->turbojet_ticket->type == TurbojetTicket::TYPE_ROUND_TRIP) {
                $option['return-datetime'] = Carbon::parse($request->input('return-date') . ' ' . $request->input('return-time'));
                $option['turbojet-tickets'][] = [
                    'from-code' => $request->input('return-city-code'),
                    'from-name' => $request->input('return-city-name'),
                    'to-code' => $request->input('departure-city-code'),
                    'to-name' => $request->input('departure-city-name'),
                    'time' => $option['return-datetime']->format('Y-m-d H:i'),
                ];
            }

            $option['turbojet'] = true;
        }

        if ($type != 'tour')
            $option['service-charge'] = $price_group->servicecharge;

        Cart::add(Toolkit::getUniqueTimestamp(), $product->title, 1, $price_data['total-price'], $option);
        return redirect('cart');
    }

    public function show(Request $request){
        $cookie = $request->cookie('language');
        if(empty($cookie) && $cookie==""){
            $cookie = "English";
        }

        if (\App::isLocale('en'))
            $term_column = 'discription';
        else if (\App::isLocale('zh-hk'))
            $term_column = 'discription1';
        else if (\App::isLocale('zh-cn'))
            $term_column = 'discription2';

        $data['cookie']=$cookie;
        $data['sitelogo'] = Sitelogo::where('status','active')->where('language','=',$cookie)->get();
        $data['carts'] = Cart::content();
        $data['total'] = Cart::subtotal();
        $data['terms'] = Staticpage::count() > 0 ? Staticpage::first()->{$term_column} : "";

        return view('website.cart.show',$data);
    }

    public function remove($rowid)
    {
        Cart::remove($rowid);
        return redirect('cart');
    }

    public function checkout(Request $request){
        if (sizeof(Cart::content()) == 0)
            return redirect('cart');

        $cookie = $request->cookie('language');
        if(empty($cookie) && $cookie==""){
            $cookie = "English";
        }
        $data['cookie']=$cookie;
        $data['sitelogo'] = Sitelogo::where('status','active')->where('language','=',$cookie)->get();
        $data['total'] = Cart::subtotal();

        return view('website.cart.checkout', $data);
    }

    public function getPaymentForm(Request $request, $method) {
        if (sizeof(Cart::content()) == 0)
            abort(404);

        $amount = str_replace(",", "", Cart::subtotal());

        if ($method == 'visa')
            return CreditCardHelper::getForm('visa', $amount);
        else if ($method == 'mastercard')
            return CreditCardHelper::getForm('mastercard', $amount);
        else if ($method == 'unionpay')
            return UnionPayHelper::getForm($amount);
        else
            abort(404);
    }

    public function cancelNotify(Request $request)
    {
        return redirect('checkout');
    }

    public function notify(Request $request)
    {
        if (!$request->has('type'))
            abort(404);

        $type = $request->input('type');
        if ($type == 'visa' || $type == 'mastercard')
            $verify = CreditCardHelper::verify($request->except('type'));
        else if ($type == 'unionpay')
            $verify = UnionPayHelper::verify($request->except('type'));
        else
            abort(404);

        $payment_history = new PaymentHistory();
        $payment_history->{PaymentHistory::COLUMN_TYPE} = $type;
        $payment_history->{PaymentHistory::COLUMN_IS_VALID} = $verify;
        if ($verify) {
            if ($type == 'visa' || $type == 'mastercard') {
                $payment_history->{PaymentHistory::COLUMN_IS_SUCCESS} = ($request->input('transaction_state', 'failed') == 'success');
                $payment_history->{PaymentHistory::COLUMN_SETTLED_AMOUNT} = floatval($request->input('requested_amount'));
                $payment_history->{PaymentHistory::COLUMN_TRANSACTION_ID} = $request->input('transaction_id');
                $payment_history->{PaymentHistory::COLUMN_ACCOUNT_NO} = $request->input('masked_account_number');
                $payment_history->{PaymentHistory::COLUMN_AUTHORIZATION_CODE} = $request->input('authorization_code');
            } else {
                $payment_history->{PaymentHistory::COLUMN_IS_SUCCESS} = ($request->input('respMsg', 'failed') == 'success');
                $payment_history->{PaymentHistory::COLUMN_SETTLED_AMOUNT} = floatval($request->input('settleAmt') / 100);
                $payment_history->{PaymentHistory::COLUMN_TRANSACTION_ID} = $request->input('orderId');
            }
        } else {
            $payment_history->{PaymentHistory::COLUMN_IS_SUCCESS} = false;
            $payment_history->{PaymentHistory::COLUMN_SETTLED_AMOUNT} = 0;
        }

        if ($type == 'visa')
            $payment_history->{PaymentHistory::COLUMN_PAYMENT_METHOD} = Order::PAYMENT_METHOD_VISA;
        else if ($type == 'mastercard')
            $payment_history->{PaymentHistory::COLUMN_PAYMENT_METHOD} = Order::PAYMENT_METHOD_MASTERCARD;
        else
            $payment_history->{PaymentHistory::COLUMN_PAYMENT_METHOD} = Order::PAYMENT_METHOD_UNIONPAY;

        $payment_history->{PaymentHistory::COLUMN_RESPONSE_DATA} = json_encode($request->except('type'), JSON_UNESCAPED_UNICODE);
        $payment_history->{PaymentHistory::COLUMN_CART_DATA} = json_encode(Cart::content(), JSON_UNESCAPED_UNICODE);
        $payment_history->{PaymentHistory::COLUMN_REQUESTED_AMOUNT} = floatval(str_replace(",", "", Cart::subtotal()));
        $payment_history->{PaymentHistory::COLUMN_IS_AMOUNT_MATCH} = ($payment_history->{PaymentHistory::COLUMN_REQUESTED_AMOUNT} == $payment_history->{PaymentHistory::COLUMN_SETTLED_AMOUNT});
        $payment_history->save();

        $payment_error_message = "";
        if (!$verify || !$payment_history->{PaymentHistory::COLUMN_IS_SUCCESS})
            $payment_error_message = "Payment issue. Please contact us.";

        if (!$payment_history->{PaymentHistory::COLUMN_IS_AMOUNT_MATCH})
            $payment_error_message = "Requested amount does not match to the settled amount. Please contact us.";

        // Handle Payment error
        if (strlen($payment_error_message) > 0)
        {
            $email_set = Emailset::where('type', Emailset::TYPE_PAYMENT_FAILED)->first();
            if ($email_set) {
                $mail_addresses = $email_set->MailAddress->pluck('mail_address');
                foreach ($mail_addresses as $email)
                {
                    \Mail::send('admin.orders.emails.payment-history', compact('payment_history'), function ($m) use ($email) {
                        $m->to($email)->subject("Payment Failed Record");
                    });
                }
            }

            return redirect('checkout')->withErrors(['error' => $payment_error_message]);
        }

        // Refresh the ID
        $payment_history = $payment_history->fresh();

        $lock = Lock::getLock(Lock::LOCK_CHECKOUT);

        try
        {
            \DB::beginTransaction();

            // Lock to prevent simultaneous checkout, the next order_id is stored in the lock data
            $lock = $lock->lock();
            $next_id = intval($lock->{Lock::COLUMN_DATA});
            if ($next_id == 0)
                $next_id = 1;

            $order_id = "GLC-" . Carbon::today()->format("Ymd") . "-" . (10000 + $next_id);
            $lock->{Lock::COLUMN_DATA} = ++$next_id;
            $lock->save();

            // Create Order
            $order = new Order();
            $order->{Order::COLUMN_ID} = $order_id;
            $order->{Order::COLUMN_TOTAL_AMOUNT} = str_replace(",", "", Cart::subtotal());
            $order->{Order::COLUMN_STATUS} = Order::STATUS_IN_QUEUE;
            $order->{Order::COLUMN_PAYMENT_METHOD} = $payment_history->{PaymentHistory::COLUMN_PAYMENT_METHOD};
            $order->{Order::COLUMN_PAYMENT_REFERENCE} = $payment_history->{PaymentHistory::COLUMN_TRANSACTION_ID};
            $order->{Order::COLUMN_PAYMENT_HISTORY_ID} = $payment_history->id;
            $order->{Order::COLUMN_ACCOUNT_NO} = $payment_history->{PaymentHistory::COLUMN_ACCOUNT_NO};
            $order->{Order::COLUMN_LANGUAGE} = \App::getLocale();
            $order->{Order::COLUMN_ENQUIRY_NO} = Order::newEnquireNo();
            $order->save();

            // Create Order Product for each cart item
            $product_index = 1;
            foreach (Cart::content() as $row)
            {
                $order_product_id = $order_id . "-" . $product_index;

                $type = $row->options['type'];

                $order_product = new OrderProduct();
                $order_product->{OrderProduct::COLUMN_ID} = $order_product_id;
                $order_product->{OrderProduct::COLUMN_ORDER_ID} = $order_id;
                $order_product->{OrderProduct::COLUMN_STATUS} = OrderProduct::STATUS_IN_QUEUE;
                $order_product->{OrderProduct::COLUMN_SUB_TOTAL} = $row->options['price-data']['sub-total'];
                $order_product->{OrderProduct::COLUMN_DISCOUNT} = array_key_exists('discounted', $row->options['price-data']) ? $row->options['price-data']['discounted'] : 0;
                $order_product->{OrderProduct::COLUMN_UNIT_SERVICE_CHARGE} = $type == 'tour' ? 0 : $row->options['service-charge'];
                $order_product->{OrderProduct::COLUMN_TOTAL_SERVICE_CHARGE} = $type == 'tour' ? 0 : ($row->options['price-data']['total-quantity'] * $row->options['service-charge']);
                $order_product->{OrderProduct::COLUMN_TOTAL_AMOUNT} = str_replace(",", "", $row->subtotal());
                $order_product->{OrderProduct::COLUMN_PACKAGE_QUANTITY} = $row->options['price-data']['total-quantity'];
                if ($type == 'tour' && $row->options['turbojet'])
                    $order_product->{OrderProduct::COLUMN_TYPE} = OrderProduct::TYPE_COMBO;
                else
                    $order_product->{OrderProduct::COLUMN_TYPE} = $type;
                $order_product->{OrderProduct::COLUMN_PRODUCT_ID} = $row->options['product-id'];
                $order_product->{OrderProduct::COLUMN_PRODUCT_PRICE_GROUP_ID} = $row->options['price-group-id'];
                $order_product->{OrderProduct::COLUMN_PRODUCT_TITLE} = $row->options['product-title'];
                $order_product->{OrderProduct::COLUMN_PRODUCT_DESCRIPTION} = $row->options['product-description'];
                $order_product->{OrderProduct::COLUMN_PRODUCT_PRICE_GROUP_TITLE} = $row->options['product-price-group-title'];
                $order_product->{OrderProduct::COLUMN_DATE} = $row->options['date'];
                if ($row->options->offsetExists('travel-time'))
                    $order_product->{OrderProduct::COLUMN_TIME} = $row->options['travel-time'];
                if ($row->options->offsetExists('class'))
                    $order_product->{OrderProduct::COLUMN_CLASS} = $row->options['class'];
                $order_product->{OrderProduct::COLUMN_HOTEL} = $row->options['hotel'];
                $order_product->{OrderProduct::COLUMN_TITLE} = $row->options['title'];
                $order_product->{OrderProduct::COLUMN_FIRST_NAME} = $row->options['first-name'];
                $order_product->{OrderProduct::COLUMN_LAST_NAME} = $row->options['last-name'];
                $order_product->{OrderProduct::COLUMN_NATIONALITY_ID} = $row->options['passport'];
                $order_product->{OrderProduct::COLUMN_EMAIL} = $row->options['email'];
                $order_product->{OrderProduct::COLUMN_COUNTRY_CODE} = $row->options['country-code'];
                $order_product->{OrderProduct::COLUMN_TEL} = $row->options['telephone'];
                if ($row->options->offsetExists('promocode'))
                    $order_product->{OrderProduct::COLUMN_PROMOCODE} = $row->options['promocode'];
                $order_product->{OrderProduct::COLUMN_REMARK} = $row->options['remark'];
                $order_product->{OrderProduct::COLUMN_LANGUAGE} = $row->options['language'];
                $order_product->{OrderProduct::COLUMN_IS_PRIVATE} = $row->options['is-private'];
                $order_product->{OrderProduct::COLUMN_REFERENCE_NO} = "";
                $order_product->save();

                // Update private tour and transportation to paid
                if ($row->options['is-private'])
                {
                    if ($type == 'tour')
                        $product = TourList::find($row->options['product-id']);
                    else
                        $product = TransportationList::find($row->options['product-id']);

                    $product->payment_status = TourList::Payment_STATUS_PAID;
                    $product->save();
                }

                if ($type == 'transportation' && $row->options['turbojet']) {
                    $unit_ticket_fee = 0;
                    foreach ($row->options['price-data']['breakdown'] as $breakdown)
                    {
                        $unit_ticket_fee += $breakdown['original'];
                    }
                    $order_product_package = new OrderProductPackage();
                    $order_product_package->{OrderProductPackage::COLUMN_ID} = $order_product_id . "-1";
                    $order_product_package->{OrderProductPackage::COLUMN_ORDER_ID} = $order_id;
                    $order_product_package->{OrderProductPackage::COLUMN_ORDER_PRODUCT_ID} = $order_product_id;
                    $order_product_package->{OrderProductPackage::COLUMN_QUANTITY} = $row->options['price-data']['total-quantity'];
                    $order_product_package->{OrderProductPackage::COLUMN_TITLE} = "Turbojet Ticket";
                    $order_product_package->{OrderProductPackage::COLUMN_UNIT_ORIGINAL_PRICE} = $unit_ticket_fee;
                    $order_product_package->{OrderProductPackage::COLUMN_UNIT_DISCOUNT} = 0;
                    $order_product_package->{OrderProductPackage::COLUMN_UNIT_FINAL_PRICE} = $unit_ticket_fee;
                    $order_product_package->{OrderProductPackage::COLUMN_PRICE_ID} = 0;
                    $order_product_package->{OrderProductPackage::COLUMN_PRICE_TYPE} = "";
                    $order_product_package->save();


                    // Create records to store turbojet flight info for reservation
                    foreach ($row->options['price-data']['breakdown'] as $breakdown)
                    {
                        $order_product_turbojet = new OrderProductTurbojet();
                        $order_product_turbojet->{OrderProductTurbojet::COLUMN_ORDER_PRODUCT_ID} = $order_product_id;
                        $order_product_turbojet->{OrderProductTurbojet::COLUMN_ROUTE} = $breakdown['flight-info']['route'];
                        $order_product_turbojet->{OrderProductTurbojet::COLUMN_TIME} = $breakdown['flight-info']['time'];
                        $order_product_turbojet->{OrderProductTurbojet::COLUMN_FROM_NAME} = $breakdown['flight-info']['from']['name'];
                        $order_product_turbojet->{OrderProductTurbojet::COLUMN_FROM_CODE} = $breakdown['flight-info']['from']['code'];
                        $order_product_turbojet->{OrderProductTurbojet::COLUMN_TO_NAME} = $breakdown['flight-info']['to']['name'];
                        $order_product_turbojet->{OrderProductTurbojet::COLUMN_TO_CODE} = $breakdown['flight-info']['to']['code'];
                        $order_product_turbojet->{OrderProductTurbojet::COLUMN_QUANTITY} = $row->options['price-data']['total-quantity'];
                        $order_product_turbojet->{OrderProductTurbojet::COLUMN_FLIGHT_NO} = $breakdown['flight-info']['flight_no'];
                        if ($breakdown['flight-info']['class'] == "E" && $breakdown['flight-info']['vessel'] == "O")
                            $order_product_turbojet->{OrderProductTurbojet::COLUMN_SEAT_CLASS} = OrderProductTurbojet::CLASS_ECONOMY;
                        else if ($breakdown['flight-info']['class'] == "S" && $breakdown['flight-info']['vessel'] == "O")
                            $order_product_turbojet->{OrderProductTurbojet::COLUMN_SEAT_CLASS} = OrderProductTurbojet::CLASS_SUPER;
                        else
                            $order_product_turbojet->{OrderProductTurbojet::COLUMN_SEAT_CLASS} = OrderProductTurbojet::CLASS_PRIMER_GRAND;
                        $order_product_turbojet->save();
                    }
                }
                else
                {
                    $product_price_index = 1;
                    foreach ($row->options['price-data']['breakdown'] as $price_id => $breakdown)
                    {
                        if ($breakdown['quantity'] == 0)
                            continue;

                        if ($type == 'tour')
                            $price = TourPrice::findOrFail($price_id);
                        else if ($type == 'transportation')
                            $price = TransportationPrice::findOrFail($price_id);
                        else
                            $price = TicketPrice::findOrFail($price_id);

                        $order_product_package = new OrderProductPackage();
                        $order_product_package->{OrderProductPackage::COLUMN_ID} = $order_product_id . "-" . $product_price_index;
                        $order_product_package->{OrderProductPackage::COLUMN_ORDER_ID} = $order_id;
                        $order_product_package->{OrderProductPackage::COLUMN_ORDER_PRODUCT_ID} = $order_product_id;
                        $order_product_package->{OrderProductPackage::COLUMN_QUANTITY} = $breakdown['quantity'];
                        $order_product_package->{OrderProductPackage::COLUMN_TITLE} = $breakdown['title'];
                        $order_product_package->{OrderProductPackage::COLUMN_UNIT_ORIGINAL_PRICE} = $breakdown['original'];
                        $order_product_package->{OrderProductPackage::COLUMN_UNIT_DISCOUNT} = $breakdown['discount'];
                        $order_product_package->{OrderProductPackage::COLUMN_UNIT_FINAL_PRICE} = $breakdown['new'];
                        $price->order_product_packages()->save($order_product_package);

                        $product_price_index++;
                    }

                    if ($type == 'tour' && $row->options['turbojet'])
                    {
                        // Combo Type
                        foreach ($row->options['turbojet-tickets'] as $ticket)
                        {
                            $order_product_turbojet = new OrderProductTurbojet();
                            $order_product_turbojet->{OrderProductTurbojet::COLUMN_ORDER_PRODUCT_ID} = $order_product_id;
                            $order_product_turbojet->{OrderProductTurbojet::COLUMN_ROUTE} = $ticket['from-code'] . $ticket['to-code'];
                            $order_product_turbojet->{OrderProductTurbojet::COLUMN_TIME} = $ticket['time'];
                            $order_product_turbojet->{OrderProductTurbojet::COLUMN_FROM_NAME} = $ticket['from-name'];
                            $order_product_turbojet->{OrderProductTurbojet::COLUMN_FROM_CODE} = $ticket['from-code'];
                            $order_product_turbojet->{OrderProductTurbojet::COLUMN_TO_NAME} = $ticket['to-name'];
                            $order_product_turbojet->{OrderProductTurbojet::COLUMN_TO_CODE} = $ticket['to-code'];
                            $order_product_turbojet->{OrderProductTurbojet::COLUMN_QUANTITY} = $row->options['price-data']['total-quantity'];
                            $order_product_turbojet->{OrderProductTurbojet::COLUMN_FLIGHT_NO} = "";
                            if ($row->options['class'] == 'economy')
                                $order_product_turbojet->{OrderProductTurbojet::COLUMN_SEAT_CLASS} = OrderProductTurbojet::CLASS_ECONOMY;
                            else if ($row->options['class'] == 'super')
                                $order_product_turbojet->{OrderProductTurbojet::COLUMN_SEAT_CLASS} = OrderProductTurbojet::CLASS_SUPER;
                            else
                                $order_product_turbojet->{OrderProductTurbojet::COLUMN_SEAT_CLASS} = OrderProductTurbojet::CLASS_PRIMER_GRAND;
                            $order_product_turbojet->save();
                        }
                    }
                }

                $product_index++;
            }

            \DB::commit();

            Cart::destroy();

            $this->dispatch(new ProcessOrderJob($order_id));

            $request->session()->flash('order-id', $order_id);
            return redirect('thankyou');
        }
        catch (\Exception $e)
        {
            \DB::rollBack();

            return redirect('checkout')->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function thankyou(Request $request){
        $cookie = $request->cookie('language');
        if(empty($cookie) && $cookie==""){
            $cookie = "English";
        }
        $data['cookie']=$cookie;
        $data['sitelogo'] = Sitelogo::where('status','active')->where('language','=',$cookie)->get();
        return view('website.cart.thankyou',$data);
    }

    public function turbojetQRCode($qrcode)
    {
        return response(\QrCode::format('png')->size(160)->margin(0)->generate($qrcode), 200, [
            'content-type' => 'image/png'
        ]);
    }

}
