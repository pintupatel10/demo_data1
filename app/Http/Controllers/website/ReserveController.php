<?php

namespace App\Http\Controllers\website;

use App\Coupon;
use App\Helpers\CalendarHelper;
use App\Helpers\PriceHelper;
use App\Helpers\TurbojetHelper;
use App\Helpers\TurbojetTicketHelper;
use App\Sitelogo;
use App\Staticpage;
use App\TicketPrice;
use App\TicketPricegroup;
use App\TourList;
use App\TourPrice;
use App\TourPricegroup;
use App\TransportationList;
use App\TransportationPrice;
use App\TransportationPricegroup;
use App\TurbojetTicket;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ReserveController extends Controller
{
    public function reserve(Request $request, $type, $cid, $price_group_id)
    {
        $cookie = $request->cookie('language');
        if (empty($cookie) && $cookie == "") {
            $cookie = "English";
        }

        if (\App::isLocale('en'))
            $term_column = 'discription';
        else if (\App::isLocale('zh-hk'))
            $term_column = 'discription1';
        else if (\App::isLocale('zh-cn'))
            $term_column = 'discription2';

        $data = [];
        $data['cookie'] = $cookie;
        $data['type'] = $type;
        $data['menuid'] = $cid;
        $data['cid'] = $cid;
        $data['price_group_id'] = $price_group_id;
        $data['sitelogo'] = Sitelogo::where('status', 'active')->where('language', '=', $cookie)->get();
        $data['terms'] = Staticpage::count() > 0 ? Staticpage::first()->{$term_column} : "";

        if ($type == 'tour')
        {
            $data['menuactive'] = "Tour";

            $price_group = TourPricegroup::where('id', $price_group_id)->where('status', 'active')->first();
            if (!$price_group)
                abort(404);

            $product = $price_group->TourList()->where('status', 'active')->first();
            $prices = $price_group->TourPrice()->where('status', 'active')->get();
            $disabled_dates = CalendarHelper::getDisabledDates($prices, CalendarHelper::TOUR_QUOTA);

            if ($product->post == TourList::POST_Private && $product->payment_status != TourList::Payment_STATUS_NOTPAID)
                abort(404);
        }
        else if ($type == 'ticket')
        {
            $data['menuactive'] = "Ticket";

            $price_group = TicketPricegroup::where('id', $price_group_id)->where('status', 'active')->first();
            if (!$price_group)
                abort(404);

            $product = $price_group->TicketList()->where('status', 'active')->first();
            $prices = $price_group->TicketPrice()->where('status', 'active')->get();
            $disabled_dates = CalendarHelper::getDisabledDates($prices, CalendarHelper::TICKET_QUOTA);
        }
        else if ($type == 'transportation')
        {
            $data['menuactive'] = "Transportation";

            $price_group = TransportationPricegroup::where('id', $price_group_id)->where('status', 'active')->first();
            if (!$price_group)
                abort(404);

            $product = $price_group->TransportationList()->where('status', 'active')->first();
            $prices = $price_group->TransportationPrice()->where('status', 'active')->get();
            $disabled_dates = CalendarHelper::getDisabledDates($prices, CalendarHelper::TRANSPORTATION_QUOTA);

            if ($product->post == TransportationList::POST_Private && $product->payment_status != TransportationList::Payment_STATUS_NOTPAID)
                abort(404);

            if ($product->isTurbojetType() && !$price_group->turbojet_ticket)
                abort(404);
        }
        else
            abort(404);

        $data['product'] = $product;
        $data['price_group'] = $price_group;
        $data['prices'] = $prices;
        $data['disabled_dates'] = $disabled_dates;

        if ($type == 'transportation' && $product->transportation_type == 'Turbojet')
            return view('website.cart.turbojet', $data);
        else if ($type == 'tour' && $product->isTurbojetType())
            return view('website.cart.combo', $data);
        else
            return view('website.cart.reserve', $data);
    }

    public function quota(Request $request, $type, $cid, $price_group_id)
    {
        $date = Carbon::parse($request->input('date'));

        if ($type == 'tour')
        {
            $prices = TourPrice::where('pricegroupid', $price_group_id)->get();
            $quota_key = CalendarHelper::TOUR_QUOTA;
        }
        else if ($type == 'ticket')
        {
            $prices = TicketPrice::where('pricegroupid', $price_group_id)->get();
            $quota_key = CalendarHelper::TICKET_QUOTA;
        }
        else if ($type == 'transportation')
        {
            $prices = TransportationPrice::where('pricegroupid', $price_group_id)->get();
            $quota_key = CalendarHelper::TRANSPORTATION_QUOTA;
        }
        else
            abort(404);

        return CalendarHelper::getDateQuota($date, $prices, $quota_key);
    }

    public function price(Request $request, $type, $cid, $price_group_id)
    {
        if ($type == 'tour')
            return PriceHelper::getTourPrice($price_group_id, $request->input('date'), trim($request->input('promocode')), $request->input('quantity'));
        else if ($type == 'ticket')
            return PriceHelper::getTicketPrice($price_group_id, $request->input('date'), $request->input('quantity'));
        else if ($type == 'transportation')
            return PriceHelper::getTransportationPrice($price_group_id, $request->input('date'), $request->input('quantity'));
        else
            abort(404);
    }

    public function timetable(Request $request, $price_group_id){
        $date = Carbon::parse($request->input('date'));
        return CalendarHelper::getTransportationTimetable($date, $price_group_id);
    }

    public function turbojetTimetable(Request $request, $price_group_id)
    {
        $time = Carbon::parse($request->input('date') . ' ' . $request->input('time'));
        $quantity = $request->input('quantity');
        $direction = $request->input('direction');
        $departure = $request->input('departure');
        $class = $request->input('vessel_class');

        $price_group = TransportationPricegroup::findOrFail($price_group_id);
        if (!$price_group->turbojet_ticket)
            abort(404);

        return TurbojetTicketHelper::getVoyage($price_group, $time, $quantity, $direction, $departure, $class);
    }

    public function comboTurbojetTimetable(Request $request, $price_group_id)
    {
        $date = Carbon::parse($request->input('date'));
        $direction = $request->input('direction');
        $departure = $request->input('departure');
        $class = $request->input('vessel_class');

        $price_group = TourPricegroup::findOrFail($price_group_id);
        if (!$price_group->turbojet_ticket)
            abort(404);

        return $price_group->getTurbojetTimetable($direction, $departure, $class, $date);
    }
}
