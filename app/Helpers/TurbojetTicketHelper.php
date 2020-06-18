<?php
/**
 * Created by PhpStorm.
 * User: yeelok
 * Date: 29/3/2017
 * Time: 17:29
 */

namespace App\Helpers;


use App\OrderProduct;
use App\OrderProductTurbojet;
use App\TurbojetCoupon;
use App\TurbojetReserve;
use App\TurbojetTicket;
use Carbon\Carbon;

class TurbojetTicketHelper
{
    public static function getVoyage($price_group, $time, $quantity, $direction, $departure, $class, $flight_no = '')
    {
        if (!$price_group->turbojet_ticket)
            abort(404);

        if ($price_group->turbojet_ticket->{TurbojetTicket::COLUMN_DEPARTURE_CITY} == TurbojetTicket::DEPARTURE_CITY_ANY)
        {
            if (!$departure)
                abort(404);

            if ($departure == "city_1")
            {
                $departure_code = $price_group->turbojet_ticket->{TurbojetTicket::COLUMN_CITY_1_CODE};
                $departure_city = $price_group->turbojet_ticket->{TurbojetTicket::COLUMN_CITY_1};
                $return_code = $price_group->turbojet_ticket->{TurbojetTicket::COLUMN_CITY_2_CODE};
                $return_city = $price_group->turbojet_ticket->{TurbojetTicket::COLUMN_CITY_2};
            }
            else
            {
                $departure_code = $price_group->turbojet_ticket->{TurbojetTicket::COLUMN_CITY_2_CODE};
                $departure_city = $price_group->turbojet_ticket->{TurbojetTicket::COLUMN_CITY_2};
                $return_code = $price_group->turbojet_ticket->{TurbojetTicket::COLUMN_CITY_1_CODE};
                $return_city = $price_group->turbojet_ticket->{TurbojetTicket::COLUMN_CITY_1};
            }
        }
        else
        {
            $departure_code = $price_group->turbojet_ticket->departure_city_code;
            $departure_city = $price_group->turbojet_ticket->departure_city_name;
            $return_code = $price_group->turbojet_ticket->return_city_code;
            $return_city = $price_group->turbojet_ticket->return_city_name;
        }

        if ($price_group->turbojet_ticket->{TurbojetTicket::COLUMN_TYPE} == TurbojetTicket::TYPE_ROUND_TRIP)
        {
            if (!$direction)
                abort(404);

            if ($direction == "return")
            {
                list($departure_code, $return_code) = [$return_code, $departure_code];      // Swap departure and return code
                list($departure_city, $return_city) = [$return_city, $departure_city];      // Swap departure and return city
            }
        }

        if ($class == "economy")
        {
            $seat_class = "E";
            $premier = "E";
        }
        else if ($class == "super")
        {
            $seat_class = "S";
            $premier = "E";
        }
        else if ($class == "primer-grand")
        {
            $seat_class = "S";
            $premier = "P";
        }
        else
            abort(404);

        $route = $departure_code . $return_code;
        $response = TurbojetApiHelper::getVoyage($route, $flight_no, $time->format("d/m/Y H:i"), $seat_class, $premier, $quantity);

        $data = [];
        foreach ($response as $schedule)
        {
            $fee = floatval($schedule->fuel_surcharge) +
                floatval($schedule->ticket_fare) +
                floatval($schedule->ticket_fee) +
                $price_group->turbojet_ticket->{TurbojetTicket::COLUMN_TOP_UP_FEE};

            if ($schedule->seat_class == "E" && $schedule->vessel == "O")
                $seat_class = trans('reserve.economy');
            else if ($schedule->seat_class == "S" && $schedule->vessel == "O")
                $seat_class = trans('reserve.super');
            else
                $seat_class = trans('reserve.primer-grand');

            $data[] = [
                'flight_no' => $schedule->flight_no,
                'time' => Carbon::parse($schedule->departure_date_time)->format('Y-m-d H:i'),
                'seat_class' => $seat_class,
                'class' => $schedule->seat_class,
                'vessel' => $schedule->vessel,
                'fee' => $fee,
                'from' => [
                    'code' => $departure_code,
                    'name' => $departure_city
                ],
                'to' => [
                    'code' => $return_code,
                    'name' => $return_city,
                ],
                'route' => $route,
            ];
        }
        return $data;
    }

    public static function reserve($order_product)
    {
        if ($order_product->{OrderProduct::COLUMN_TYPE} == OrderProduct::TYPE_TRANSPORTATION)
            return self::realTimeReserve($order_product);
        else
            return self::virtualReserve($order_product);
    }

    // For Combo Virtual Seating Only
    public static function virtualReserve($order_product)
    {
        $coupon_type = $order_product->turbojets->count() == 1 ? TurbojetCoupon::TYPE_ONE_WAY : TurbojetCoupon::TYPE_ROUND_TRIP;
        $reservations = [];
        $count = 1;
        foreach ($order_product->turbojets as $turbojet)
        {
            for ($i = 0; $i < $order_product->{OrderProduct::COLUMN_PACKAGE_QUANTITY}; $i++) {
                $reservations[] = [
                    'departure_date_time' => $turbojet->{OrderProductTurbojet::COLUMN_TIME},
                    'reservation_no' => $order_product->{OrderProduct::COLUMN_ID} . '-' . $count,
                ];
            }

            if ($turbojet->{OrderProductTurbojet::COLUMN_SEAT_CLASS} == OrderProductTurbojet::CLASS_ECONOMY)
                $coupon_seat_class = TurbojetCoupon::CLASS_ECONOMY;
            else if ($turbojet->{OrderProductTurbojet::COLUMN_SEAT_CLASS} == OrderProductTurbojet::CLASS_SUPER)
                $coupon_seat_class = TurbojetCoupon::CLASS_SUPER;
            else if ($turbojet->{OrderProductTurbojet::COLUMN_SEAT_CLASS} == OrderProductTurbojet::CLASS_PRIMER_GRAND)
                $coupon_seat_class = TurbojetCoupon::CLASS_PRIMER_GRAND;

            $code = TurbojetCoupon::getCode($coupon_type, $coupon_seat_class, $turbojet->{OrderProductTurbojet::COLUMN_TIME}, $turbojet->{OrderProductTurbojet::COLUMN_FROM_CODE}, $turbojet->{OrderProductTurbojet::COLUMN_TO_CODE});
            if (strlen($code) > 0)
            {
                $turbojet->{OrderProductTurbojet::COLUMN_PROMO_CODE} = $code;
                $turbojet->save();
            }

            $count++;
        }

        $reserve = new TurbojetReserve();
        $reserve->{TurbojetReserve::COLUMN_ORDER_PRODUCT_ID} = $order_product->{OrderProduct::COLUMN_ID};
        $reserve->{TurbojetReserve::COLUMN_IS_SUCCESS} = true;
        $reserve->{TurbojetReserve::COLUMN_TRANSACTION_ID} = $order_product->{OrderProduct::COLUMN_ID};
        $reserve->{TurbojetReserve::COLUMN_RESERVATIONS} = $reservations;
        $reserve->{TurbojetReserve::COLUMN_TOTAL_PRICE} = 0;
        $reserve->{TurbojetReserve::COLUMN_CREDIT_REMAIN} = 0;
        $reserve->save();

        // Save before generating confirmation letter
        $reserve = $reserve->fresh();
        $reserve->{TurbojetReserve::COLUMN_CONFIRMATION_LETTER} = self::generateConfirmationLetter($order_product);
        $reserve->save();

        return $reserve;
    }

    public static function realTimeReserve($order_product)
    {
        $booking = [];
        $coupon_type = $order_product->turbojets->count() == 1 ? TurbojetCoupon::TYPE_ONE_WAY : TurbojetCoupon::TYPE_ROUND_TRIP;

        foreach ($order_product->turbojets as $turbojet)
        {
            if ($turbojet->{OrderProductTurbojet::COLUMN_SEAT_CLASS} == OrderProductTurbojet::CLASS_ECONOMY)
            {
                $seat_class = "E";
                $premier = "O";
                $coupon_seat_class = TurbojetCoupon::CLASS_ECONOMY;
            }
            else if ($turbojet->{OrderProductTurbojet::COLUMN_SEAT_CLASS} == OrderProductTurbojet::CLASS_SUPER)
            {
                $seat_class = "S";
                $premier = "O";
                $coupon_seat_class = TurbojetCoupon::CLASS_SUPER;
            }
            else if ($turbojet->{OrderProductTurbojet::COLUMN_SEAT_CLASS} == OrderProductTurbojet::CLASS_PRIMER_GRAND)
            {
                $seat_class = "S";
                $premier = "P";
                $coupon_seat_class = TurbojetCoupon::CLASS_PRIMER_GRAND;
            }

            $code = TurbojetCoupon::getCode($coupon_type, $coupon_seat_class, $turbojet->{OrderProductTurbojet::COLUMN_TIME}, $turbojet->{OrderProductTurbojet::COLUMN_FROM_CODE}, $turbojet->{OrderProductTurbojet::COLUMN_TO_CODE});
            if (strlen($code) > 0)
            {
                $turbojet->{OrderProductTurbojet::COLUMN_PROMO_CODE} = $code;
                $turbojet->save();
            }

            $booking[] = [
                'route' => $turbojet->{OrderProductTurbojet::COLUMN_ROUTE},
                'departure_date_time' => $turbojet->{OrderProductTurbojet::COLUMN_TIME},
                'seat_class' => $seat_class,
                'no_of_ticket' => $turbojet->{OrderProductTurbojet::COLUMN_QUANTITY},
                'premier' => $premier,
                'promotion_code' => $code,
            ];
        }

        try
        {
            $result = TurbojetApiHelper::doPayment($order_product->name, $booking);
            $reserve = new TurbojetReserve();
            $reserve->{TurbojetReserve::COLUMN_ORDER_PRODUCT_ID} = $order_product->{OrderProduct::COLUMN_ID};
            $reserve->{TurbojetReserve::COLUMN_IS_SUCCESS} = true;
            $reserve->{TurbojetReserve::COLUMN_TRANSACTION_ID} = $result->trans_id;
            if (is_array($result->reservation->reservation))
                $reserve->{TurbojetReserve::COLUMN_RESERVATIONS} = $result->reservation->reservation;
            else
                $reserve->{TurbojetReserve::COLUMN_RESERVATIONS} = [$result->reservation->reservation];
            $reserve->{TurbojetReserve::COLUMN_TOTAL_PRICE} = $result->total_price;
            $reserve->{TurbojetReserve::COLUMN_CREDIT_REMAIN} = $result->credit_remain;
            $reserve->save();

            // Save before generating confirmation letter
            $reserve = $reserve->fresh();
            $reserve->{TurbojetReserve::COLUMN_CONFIRMATION_LETTER} = self::generateConfirmationLetter($order_product);
            $reserve->save();

            return $reserve;
        }
        catch (\Exception $e)
        {
            $reserve = new TurbojetReserve();
            $reserve->{TurbojetReserve::COLUMN_ORDER_PRODUCT_ID} = $order_product->{OrderProduct::COLUMN_ID};
            $reserve->{TurbojetReserve::COLUMN_IS_SUCCESS} = false;
            $reserve->{TurbojetReserve::COLUMN_ERROR_MESSAGE} = $e->getMessage();
            $reserve->save();

            return null;
        }
    }

    public static function generateConfirmationLetter($order_product)
    {
        $config = \Config::get('core.turbojet.' . env('TURBOJET_ENV', 'dev'));

        \App::setLocale($order_product->{OrderProduct::COLUMN_LANGUAGE});
        $html = \View::make('website.cart.turbojet-confirmation', compact('order_product', 'config'))->render();

        if (!file_exists(storage_path('app/turbojet')))
            \Storage::makeDirectory("turbojet");

        $storage_path = 'turbojet/' . $order_product->turbojet_reserve->{TurbojetReserve::COLUMN_TRANSACTION_ID} . ".pdf";

        \PDF::loadHTML($html)->save(storage_path("app/$storage_path"));

        return $storage_path;
    }
}