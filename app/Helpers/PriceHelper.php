<?php
/**
 * Created by PhpStorm.
 * User: yeelok
 * Date: 8/3/2017
 * Time: 18:20
 */

namespace App\Helpers;


use App\Coupon;
use App\TicketPricegroup;
use App\TicketVolume;
use App\TourPrice;
use App\TourPricegroup;
use App\TransportationPricegroup;
use App\TurbojetTicket;
use Carbon\Carbon;

class PriceHelper
{
    public static function getTourPrice($price_group_id, $date, $promo_code, $quantity_data)
    {
        $price_group = TourPricegroup::where('id', $price_group_id)->where('status', 'active')->first();
        $tour = $price_group->TourList()->where('status', 'active')->first();
        $prices = $price_group->TourPrice()->where('status', 'active')->get();
        $pricesById = $prices->keyBy('id');
        $date = Carbon::parse($date);

        $sub_total = 0;
        $total_quantity = 0;
        $breakdown = [];
        $buyable = true;

        // Calc total price
        $quota = CalendarHelper::getDateQuota($date, $prices, CalendarHelper::TOUR_QUOTA);
        foreach ($quantity_data as $key => $quantity)
        {
            if (!array_key_exists($key, $quota))
            {
                $buyable = false;
                continue;
            }

            if ($quota[$key] != -1 && $quota[$key] < $quantity)
            {
                $buyable = false;
                continue;
            }

            $total_quantity += $quantity;
            $sub_total += $quantity * $pricesById[$key]->price;
            $breakdown[$key] = [
                'quantity' => $quantity,
                'original' => $pricesById[$key]->price,
                'new' => $pricesById[$key]->price,
                'discount' => 0,
                'title' => $pricesById[$key]->title,
                'id' => $key,
            ];
        }

        // Check promo code
        // TODO: How to handle those product with promocode in cart?
        $discount_type = "none";
        $discount = 0;
        if (strlen($promo_code) > 0) {
            $discount_type = 'invalid';
            $coupon = Coupon::where('couponcode', $promo_code)->where('status', 'active')->first();
            if ($coupon && $coupon->quota > 0 && $coupon->quota > $coupon->getUsedCount()) {
                $tour_ids = explode(',', $coupon['tourlist_id']);
                if (in_array($tour->id, $tour_ids)) {
                    if ($coupon->type = Coupon::TYPE_EARLY && Carbon::parse($coupon->earlydate) >= Carbon::today()) {
                        $discount_type = $coupon->discountby;
                        $discount = $coupon->discount;
                    } else if ($coupon->type = Coupon::TYPE_ORDER && Carbon::parse($coupon->orderdate_start) <= $date && Carbon::parse($coupon->orderdate_end) >= $date) {
                        $discount_type = $coupon->discountby;
                        $discount = $coupon->discount;
                    }
                }
            }
        }

        $discounted = 0;
        if ($discount_type == 'price')
        {
            $discounted = $discount * $total_quantity;
            foreach($breakdown as $key => $value)
            {
                $breakdown[$key]['discount'] = $discount;
                $breakdown[$key]['new'] = $breakdown[$key]['original'] - $discount;
            }
        }
        else if ($discount_type == 'percentage')
        {
            $discounted = ($discount * $sub_total * 1.0) / 100;
            foreach($breakdown as $key => $value)
            {
                $breakdown[$key]['discount'] = ($breakdown[$key]['original'] * $discount * 1.0) / 100;
                $breakdown[$key]['new'] = $breakdown[$key]['original'] - (($breakdown[$key]['original'] * $discount * 1.0) / 100);
            }
        }

        return [
            'buyable' => $buyable,
            'sub-total' => $sub_total,
            'discounted' => $discounted,
            'discount_type' => $discount_type,
            'total-price' => ($sub_total - $discounted),
            'total-quantity' => $total_quantity,
            'breakdown' => $breakdown,
        ];
    }

    public static function getTicketPrice($price_group_id, $date, $quantity_data)
    {
        $price_group = TicketPricegroup::where('id', $price_group_id)->where('status', 'active')->first();
        $product = $price_group->TicketList()->where('status', 'active')->first();
        $prices = $price_group->TicketPrice()->where('status', 'active')->get();
        $pricesById = $prices->keyBy('id');
        $date = Carbon::parse($date);

        $sub_total = 0;
        $total_quantity = 0;
        $buyable = true;
        $discount_data = [];
        $breakdown = [];

        // Calc total price
        $quota = CalendarHelper::getDateQuota($date, $prices, CalendarHelper::TICKET_QUOTA);
        foreach ($quantity_data as $key => $quantity)
        {
            if (!array_key_exists($key, $quota))
            {
                $buyable = false;
                continue;
            }

            if ($quota[$key] != -1 && $quota[$key] < $quantity)
            {
                $buyable = false;
                continue;
            }

            $total_quantity += $quantity;
            $discount_data[$key] = 0;
            $sub_total += $quantity * $pricesById[$key]->price;
            $breakdown[$key] = [
                'quantity' => $quantity,
                'original' => $pricesById[$key]->price,
                'new' => $pricesById[$key]->price,
                'discount' => 0,
                'title' => $pricesById[$key]->title,
                'id' => $key,
            ];
        }

        // Check ticket volume discount
        $ticket_volumes = TicketVolume::where('pricegroupid', $price_group_id)->where('status','active')->orderBy('volume','DESC')->get();
        foreach($ticket_volumes as $ticket_volume)
        {
            if ($date >= Carbon::parse($ticket_volume->date) && $date <= Carbon::parse($ticket_volume->to))
            {
                if ($ticket_volume->type == "Single")
                {
                    $vol_price_id = $ticket_volume->title1;

                    if (in_array($vol_price_id, array_keys($quantity_data))) {
                        if ($quantity_data[$vol_price_id] >= $ticket_volume->volume) {
                            if ($discount_data[$vol_price_id] == 0)
                                $discount_data[$vol_price_id] = intval($ticket_volume->discount);
                        }
                    }
                }
                else if ($ticket_volume->type == "Multiple")
                {
                    $vol_total_quantity = 0;
                    $vol_price_ids = explode(',', $ticket_volume->title);
                    foreach ($vol_price_ids as $vol_price_id)
                    {
                        if (in_array($vol_price_id, array_keys($quantity_data))) {
                            $vol_total_quantity += $quantity_data[$vol_price_id];
                        }
                    }

                    if ($vol_total_quantity >= $ticket_volume->volume) {
                        foreach ($vol_price_ids as $vol_price_id)
                        {
                            if (in_array($vol_price_id, array_keys($quantity_data))) {
                                $discount_data[$vol_price_id] = intval($ticket_volume->discount1);
                            }
                        }
                    }
                }
            }
        }

        // Calculate the price with volume discount
        $discounted = 0;
        foreach ($quantity_data as $key => $quantity)
        {
            if ($discount_data[$key] > 0)
            {
                $discounted += ($discount_data[$key] * $quantity * $pricesById[$key]->price * 1.0) / 100;
                $breakdown[$key]['discount'] = ($discount_data[$key] * $pricesById[$key]->price * 1.0) / 100;
                $breakdown[$key]['new'] =  $breakdown[$key]['original'] - (($discount_data[$key] * $pricesById[$key]->price * 1.0) / 100);
            }
        }

        // Add service charge
        $service_charge = $price_group->servicecharge * $total_quantity;

        return [
            'buyable' => $buyable,
            'sub-total' => $sub_total,
            'discounted' => $discounted,
            'service-charge' => $service_charge,
            'total-price' => ($sub_total - $discounted + $service_charge),
            'total-quantity' => $total_quantity,
            'breakdown' => $breakdown,
        ];
    }



    public static function getTransportationPrice($price_group_id, $date, $quantity_data)
    {
        $price_group = TransportationPricegroup::where('id', $price_group_id)->where('status', 'active')->first();
        $product = $price_group->TransportationList()->where('status', 'active')->first();
        $prices = $price_group->TransportationPrice()->where('status', 'active')->get();
        $pricesById = $prices->keyBy('id');
        $date = Carbon::parse($date);

        $sub_total = 0;
        $total_quantity = 0;
        $buyable = true;
        $breakdown = [];

        // Calc total price
        $quota = CalendarHelper::getDateQuota($date, $prices, CalendarHelper::TRANSPORTATION_QUOTA);
        foreach ($quantity_data as $key => $quantity)
        {
            if (!array_key_exists($key, $quota))
            {
                $buyable = false;
                continue;
            }

            if ($quota[$key] != -1 && $quota[$key] < $quantity)
            {
                $buyable = false;
                continue;
            }

            $total_quantity += $quantity;
            $discount_data[$key] = 0;
            $sub_total += $quantity * $pricesById[$key]->price;
            $breakdown[$key] = [
                'quantity' => $quantity,
                'original' => $pricesById[$key]->price,
                'new' => $pricesById[$key]->price,
                'discount' => 0,
                'title' => $pricesById[$key]->title,
                'id' => $key,
            ];
        }

        // Add service charge
        $service_charge = $price_group->servicecharge * $total_quantity;

        return [
            'buyable' => $buyable,
            'sub-total' => $sub_total,
            'service-charge' => $service_charge,
            'total-price' => ($sub_total + $service_charge),
            'total-quantity' => $total_quantity,
            'breakdown' => $breakdown,
        ];
    }

    public static function getTurbojetPrice($price_group, $request)
    {
        $ticket_fee = 0;
        $buyable = true;

        $quantity = $request->input('tickets');
        $departure = $request->input('departure_city');
        $class = $request->input('class');

        $breakdown = [];

        $departure_time = Carbon::parse($request->input('departure-date') . ' ' . $request->input('departure-time'));
        $departure_flight_no = $request->input('departure-flight-no');
        $schedule = TurbojetTicketHelper::getVoyage($price_group, $departure_time, $quantity, "departure", $departure, $class, $departure_flight_no);
        if (sizeof($schedule) >= 1 && $schedule[0]['flight_no'] == $departure_flight_no)
        {
            $ticket_fee += $schedule[0]['fee'];
            $breakdown[] = [
                'quantity' => $quantity,
                'original' => $schedule[0]['fee'],
                'new' => $schedule[0]['fee'],
                'discount' => 0,
                'title' => $schedule[0]['from']['name'] . ' >>> ' . $schedule[0]['to']['name'],
                'flight-info' => $schedule[0],
            ];
        }
        else
        {
            $buyable = false;
        }

        if ($price_group->turbojet_ticket->type == TurbojetTicket::TYPE_ROUND_TRIP)
        {
            $return_time = Carbon::parse($request->input('return-date') . ' ' . $request->input('return-time'));
            $return_flight_no = $request->input('return-flight-no');
            $schedule = TurbojetTicketHelper::getVoyage($price_group, $return_time, $quantity, "return", $departure, $class, $return_flight_no);
            if (sizeof($schedule) >= 1 && $schedule[0]['flight_no'] == $return_flight_no) {
                $ticket_fee += $schedule[0]['fee'];
                $breakdown[] = [
                    'quantity' => $quantity,
                    'original' => $schedule[0]['fee'],
                    'new' => $schedule[0]['fee'],
                    'discount' => 0,
                    'title' => $schedule[0]['from']['name'] . ' >>> ' . $schedule[0]['to']['name'],
                    'flight-info' => $schedule[0],
                ];
            }
            else
            {
                $buyable = false;
            }
        }

        $sub_total = $quantity * $ticket_fee;
        $service_charge = $price_group->servicecharge * $quantity;

        return [
            'buyable' => $buyable,
            'sub-total' => $sub_total,
            'service-charge' => $service_charge,
            'total-price' => ($sub_total + $service_charge),
            'total-quantity' => $quantity,
            'breakdown' => $breakdown,
        ];

    }
}