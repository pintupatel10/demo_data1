<?php

namespace App\Http\Controllers\admin;

use App\Audit;
use App\DisneylandReserve;
use App\OceanParkReserve;
use App\Order;
use App\OrderConfirmation;
use App\OrderMessage;
use App\OrderMessageAttachment;
use App\OrderProduct;
use App\OrderProductAttachment;
use App\TurbojetReserve;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role');
        $this->middleware('accessright:Access Transaction');
    }

    public function index(Request $request)
    {
        $filter = (object)[];

        $fields = [
            'start-date', 'end-date', 'search', 'status', 'type', 'payment',
        ];

        foreach ($fields as $field)
        {
            $filter->{$field} = $request->input($field);
        }

        $query = Order::query();

        if ($request->has('start-date'))
            $query->where(Order::CREATED_AT, '>=', Carbon::parse($filter->{'start-date'}));

        if ($request->has('end-date'))
            $query->where(Order::CREATED_AT, '<', Carbon::parse($filter->{'end-date'})->addDay(1));

        if ($request->has('search')) {
            $query->where(function ($advanced) use ($filter) {
                $advanced->where(Order::COLUMN_ID, 'like', '%' . $filter->search . '%');

                $advanced->orWhereHas('products', function ($sub_query) use ($filter) {
                    $sub_query->where(OrderProduct::COLUMN_PRODUCT_PRICE_GROUP_TITLE, 'like', '%' . $filter->search . '%');
                });
            });
        }

        if ($request->has('payment'))
            $query->whereIn(Order::COLUMN_PAYMENT_METHOD, $filter->payment);

        $query->whereHas('products', function ($sub_query) use ($filter) {

            if ($filter->status)
                $sub_query->whereIn(OrderProduct::COLUMN_STATUS, $filter->{'status'});

            if ($filter->type)
            {
                $sub_query->where(function ($advanced) use ($filter)
                {
                    foreach ($filter->type as $type)
                    {
                        if (OrderProduct::$types)
                            $advanced->orWhere(OrderProduct::COLUMN_TYPE, $type);
                        else if ($type == OrderProduct::TYPE_PRIVATE_TOUR)
                        {
                            $advanced->orWhere(function ($inner) {
                                $inner->where(OrderProduct::COLUMN_TYPE, OrderProduct::TYPE_TOUR)->where(OrderProduct::COLUMN_IS_PRIVATE, true);
                            });
                        }
                        else if ($type == OrderProduct::TYPE_PRIVATE_TRANSPORTATION)
                        {
                            $advanced->orWhere(function ($inner) {
                                $inner->where(OrderProduct::COLUMN_TYPE, OrderProduct::TYPE_TRANSPORTATION)->where(OrderProduct::COLUMN_IS_PRIVATE, true);
                            });
                        }
                    }
                });
            }

        });

        $orders = $query->orderBy('created_at', 'DESC')->paginate(15);

        $data['mainmenu'] = "Order";
        $data['menu'] = "Order";
        $data['orders'] = $orders;
        $data['filter'] = $filter;

        return view('admin.orders.index', $data);
    }

    public function show($id)
    {
        $data['mainmenu'] = "Order";
        $data['menu'] = "Order";
        $data['order'] = Order::findOrFail($id);
        return view('admin.orders.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $order->{Order::COLUMN_REMARK} = $request->input('remark');
        $order->save();


        \Activity::log("Order #$order->id has been updated");
        $order->updateNotificationUser();

        $request->session()->flash('success', "Order #$order->id is successfully updated");
        return redirect("admin/order/$id");
    }

    public function show_order_product($order_id, $order_product_id)
    {
        $data['mainmenu'] = "Order";
        $data['menu'] = "Order";
        $data['order_product'] = OrderProduct::findOrFail($order_product_id);
        $data['order'] = $data['order_product']->order;
        return view('admin.orders.products.edit', $data);
    }

    public function update_order_product(Request $request, $order_id, $order_product_id)
    {
        $rules = [
            'date' => 'required|date',
        ];

        $order_product = OrderProduct::findOrFail($order_product_id);
        if ($order_product->{OrderProduct::COLUMN_TIME})
            $rules['time'] = 'required';

        $this->validate($request, $rules);

        $order_product->fill($request->all());

        $reset_status = false;

        if ($order_product->{OrderProduct::COLUMN_DATE} != Carbon::parse($request->input('date')))
        {
            $order_product->{OrderProduct::COLUMN_DATE} = $request->input('date');
            $reset_status = true;
        }

        if ($order_product->{OrderProduct::COLUMN_TIME} && $order_product->{OrderProduct::COLUMN_TIME} != $request->input('time'))
        {
            $order_product->{OrderProduct::COLUMN_TIME} = $request->input('time');
            $reset_status = true;
        }

        if ($reset_status)
            $order_product->{OrderProduct::COLUMN_STATUS} = OrderProduct::STATUS_PENDING;

        $order_product->save();

        \Activity::log("Order #$order_id has been updated");
        $order_product->order->updateNotificationUser();

        $request->session()->flash('success', "Order Product #$order_product->id is successfully updated");
        return redirect("admin/order/$order_id");
    }

    public function download_disneyland_letter($order_id, $order_product_id, $id)
    {
        $reserve = DisneylandReserve::findOrFail($id);
        return response()->file($reserve->getLetterStoragePath());
    }

    public function download_oceanpark_letter($order_id, $order_product_id, $id)
    {
        $reserve = OceanParkReserve::findOrFail($id);
        return response()->file($reserve->getLetterStoragePath());
    }

    public function download_turbojet_letter($order_id, $order_product_id, $id)
    {
        $reserve = TurbojetReserve::findOrFail($id);
        return response()->file($reserve->getLetterStoragePath());
    }

    public function update_order_product_audit_remark(Request $request, $order_id, $order_product_id)
    {
        $this->validate($request, [
            'audit_id' => 'required|integer',
        ]);

        $audit = Audit::findOrFail($request->input('audit_id'));
        $audit->remark = $request->input('remark');
        $audit->save();

        $request->session()->flash('success', "Remark is successfully updated");
        Order::findOrFail($order_id)->updateNotificationUser();
        return redirect("admin/order/$order_id/$order_product_id");
    }

    public function show_order_product_confirm($order_id, $order_product_id)
    {
        $data['mainmenu'] = "Order";
        $data['menu'] = "Order";
        $data['order_product'] = OrderProduct::findOrFail($order_product_id);
        $data['order'] = $data['order_product']->order;
        return view('admin.orders.products.confirm', $data);
    }

    public function update_order_product_confirm(Request $request, $order_id, $order_product_id)
    {
        $order_product = OrderProduct::findOrFail($order_product_id);
        $order_product->{OrderProduct::COLUMN_CONFIRMATION} = $request->input('confirmation');
        $order_product->{OrderProduct::COLUMN_STATUS} = OrderProduct::STATUS_CONFIRM_DRAFTED;
        $order_product->save();

        // Upload new attachments
        foreach ($request->file('upload') as $upload)
        {
            // make sure it contains a file
            if ($upload && $upload->isValid())
            {
                $attachment = new OrderProductAttachment();
                $attachment->{OrderProductAttachment::COLUMN_ORDER_PRODUCT_ID} = $order_product->{OrderProduct::COLUMN_ID};
                $attachment->{OrderProductAttachment::COLUMN_NAME} = $upload->getClientOriginalName();
                $attachment->save();

                // Get the attachment id and then move file to storage
                $attachment = $attachment->fresh();

                $attachment_dir = storage_path(OrderProductAttachment::STORAGE_FOLDER);
                if (!file_exists($attachment_dir))
                    mkdir($attachment_dir, 0777, true);

                $attachment_filename = $attachment->{OrderProductAttachment::COLUMN_ID} . '.' . $upload->getClientOriginalExtension();

                $upload->move($attachment_dir, $attachment_filename);

                // Upload the db record
                $attachment->{OrderProductAttachment::COLUMN_PATH} = $attachment_filename;
                $attachment->save();
            }
        }

        // Delete attachments
        if ($request->has('delete-attachment')) {
            foreach ($request->input('delete-attachment') as $attachment_id) {
                $attachment = $order_product->attachments()->find($attachment_id);
                if ($attachment) {
                    $attachment->delete();
                }
            }
        }

        \Activity::log("Order #$order_id has been updated");
        Order::findOrFail($order_id)->updateNotificationUser();

        $request->session()->flash('success', "Order Product #$order_product->id is successfully updated");
        return redirect("admin/order/$order_id");
    }

    public function send_confirmation(Request $request, $order_id)
    {
        $this->validate($request, [
            'order-product-ids' => 'required'
        ]);

        $ids = json_decode($request->input('order-product-ids'));

        $order = Order::findOrFail($order_id);

        \App::setLocale($order->{Order::COLUMN_LANGUAGE});

        $order_products = collect();
        $attachments = collect();
        foreach ($ids as $order_product_id)
        {
            $order_product = OrderProduct::findOrFail($order_product_id);
            $order_product->{OrderProduct::COLUMN_STATUS} = OrderProduct::STATUS_CONFIRMED;
            $order_product->{OrderProduct::COLUMN_CONFIRMATION_SENT_AT} = Carbon::now();
            $order_product->{OrderProduct::COLUMN_CONFIRMED_BY} = \Auth::user()->id;
            $order_product->save();

            $order_products->push($order_product);

            foreach ($order_product->attachments as $attachment)
            {
                $attachments->push($attachment);
            }
        }

        // Create record for this confirmation
        $confirmation = OrderConfirmation::createConfirmationForOrder($order);
        $confirmation->{OrderConfirmation::COLUMN_USER_ID} = \Auth::user()->id;
        $confirmation->{OrderConfirmation::COLUMN_ORDER_PRODUCT_IDS} = $order_products->pluck('id');
        $confirmation->{OrderConfirmation::COLUMN_ORDER_PRODUCT_ATTACHMENT_IDS} = $attachments->pluck('id');

        // Render the email
        $view = view('admin.orders.emails.confirmation', compact('order', 'order_products', 'confirmation'));
        $confirmation->{OrderConfirmation::COLUMN_CONTENT} = $view->render();

        $confirmation->save();

        $emails = $order_products->pluck(OrderProduct::COLUMN_EMAIL)->unique();
        foreach ($emails as $email)
        {
            \Mail::send('admin.orders.emails.confirmation', compact('order', 'order_products', 'confirmation'), function ($m) use ($email, $order, $attachments) {
                foreach ($attachments as $attachment)
                {
                    $m->attach($attachment->getStoragePath(), ['as' => $attachment->{OrderProductAttachment::COLUMN_NAME}]);
                }

                $m->to($email)->subject(trans('email.confirmation-subject') . ' - ' . trans('email.order-no') . ' ' . $order->{Order::COLUMN_ID});
            });
        }

        \Activity::log("Sent confirmation letter for Order #$order_id");
        $order->updateNotificationUser();

        $request->session()->flash('success', sizeof($order_products) . " confirmation letters have been successfully sent");
        return redirect("admin/order/$order_id");
    }

    public function download_attachment($order_id, $order_product_id, $id)
    {
        $attachment = OrderProductAttachment::findOrFail($id);
        return response()->file($attachment->getStoragePath());
    }

    public function show_order_confirmation($order_id, $id)
    {
        $confirmation = OrderConfirmation::findOrFail($id);
        return response($confirmation->{OrderConfirmation::COLUMN_CONTENT}, 200)->header('Content-Type', 'text/html');
    }

    public function complete_order_product(Request $request, $order_id)
    {
        $this->validate($request, [
            'order-product-id' => 'required'
        ]);

        $order_product = OrderProduct::findOrFail($request->input('order-product-id'));
        $order_product->{OrderProduct::COLUMN_STATUS} = OrderProduct::STATUS_COMPLETED;
        $order_product->{OrderProduct::COLUMN_COMPLETED_AT} = Carbon::now();
        $order_product->save();

        \Activity::log("Order #$order_id has been updated");
        $order_product->order->updateNotificationUser();

        $request->session()->flash('success', "Order Product #$order_product->id is successfully updated");
        return redirect("admin/order/$order_id");
    }

    public function cancel_order_product(Request $request, $order_id)
    {
        $this->validate($request, [
            'order-product-id' => 'required'
        ]);

        $order_product = OrderProduct::findOrFail($request->input('order-product-id'));
        $order_product->{OrderProduct::COLUMN_STATUS} = OrderProduct::STATUS_CANCELED;
        $order_product->{OrderProduct::COLUMN_COMPLETED_AT} = Carbon::now();
        $order_product->save();

        \Activity::log("Order #$order_id has been updated");
        $order_product->order->updateNotificationUser();

        $request->session()->flash('success', "Order Product #$order_product->id is successfully updated");
        return redirect("admin/order/$order_id");
    }

    public function show_reply(Request $request, $order_id)
    {
        $data['mainmenu'] = "Order";
        $data['menu'] = "Order";
        $data['order'] = Order::findOrFail($order_id);
        return view('admin.orders.reply', $data);
    }

    public function send_reply(Request $request, $order_id)
    {
        $this->validate($request, [
            'message' => 'required',
        ]);

        $order = Order::findOrFail($order_id);

        \App::setLocale($order->{Order::COLUMN_LANGUAGE});

        $order_message = new OrderMessage();
        $order_message->{OrderMessage::COLUMN_ORDER_ID} = $order->{Order::COLUMN_ID};
        $order_message->{OrderMessage::COLUMN_CONTACT_VIA} = OrderMessage::CONTACT_VIA_WEB;
        $order_message->{OrderMessage::COLUMN_TYPE} = OrderMessage::TYPE_REPLY;
        $order_message->{OrderMessage::COLUMN_MESSAGE} = $request->input('message');
        $order_message->{OrderMessage::COLUMN_REPLY_BY} = \Auth::user()->id;
        $order_message->save();

        $order_message = $order_message->fresh();

        $attachments = collect();

        // Upload new attachments
        foreach ($request->file('upload') as $upload)
        {
            // make sure it contains a file
            if ($upload && $upload->isValid())
            {
                $attachment = new OrderMessageAttachment();
                $attachment->{OrderMessageAttachment::COLUMN_ORDER_MESSAGE_ID} = $order_message->{OrderMessage::COLUMN_ID};
                $attachment->{OrderMessageAttachment::COLUMN_NAME} = $upload->getClientOriginalName();
                $attachment->save();

                // Get the attachment id and then move file to storage
                $attachment = $attachment->fresh();

                $attachment_dir = storage_path(OrderMessageAttachment::STORAGE_FOLDER);
                if (!file_exists($attachment_dir))
                    mkdir($attachment_dir, 0777, true);

                $attachment_filename = $attachment->{OrderMessageAttachment::COLUMN_ID} . '.' . $upload->getClientOriginalExtension();

                $upload->move($attachment_dir, $attachment_filename);

                // Upload the db record
                $attachment->{OrderMessageAttachment::COLUMN_PATH} = $attachment_filename;
                $attachment->save();

                $attachments->push($attachment);
            }
        }

        $emails = $order->products()->pluck(OrderProduct::COLUMN_EMAIL)->unique();
        foreach ($emails as $email)
        {
            \Mail::send('admin.orders.emails.reply', compact('order', 'order_message'), function ($m) use ($email, $order, $attachments) {
                foreach ($attachments as $attachment)
                {
                    $m->attach($attachment->getStoragePath(), ['as' => $attachment->{OrderMessageAttachment::COLUMN_NAME}]);
                }

                $m->to($email)->subject("[Order $order->id] " . trans('email.reply-subject'));
            });
        }

        \Activity::log("Order #$order_id has been updated");
        $order->updateNotificationUser();

        $request->session()->flash('success', "Order Reply is successfully sent");
        return redirect("admin/order/$order_id");
    }

    public function download_message_attachment($order_id, $id)
    {
        $attachment = OrderMessageAttachment::findOrFail($id);
        return response()->file($attachment->getStoragePath());
    }
}
