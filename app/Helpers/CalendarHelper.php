<?php

namespace App\Helpers;

use App\TransportationTimetable;
use Carbon\Carbon;


class CalendarHelper
{
    const TOUR_QUOTA = 'Quota';
    const TICKET_QUOTA = 'TicketQuota';
    const TRANSPORTATION_QUOTA = 'TransportationQuota';

    public static function getDateQuota($date, $prices, $quota_key)
    {
        $quota = [];
        foreach ($prices as $price)
        {
            $quota[$price->id] = $price->dquota;
            foreach ($price->{$quota_key} as $special)
            {
                $from = Carbon::parse($special->date);
                $to = Carbon::parse($special->to);

                if (isset($special->day_of_week) && $special->day_of_week != "all" && $date->dayOfWeek != $special->getCarbonDayOfWeek())
                {
                    continue;
                }

                if ($date >= $from && $date <= $to)
                    $quota[$price->id] = $special->quota;
            }

            // deduct used quota
            if ($quota[$price->id] == -1)
                continue;

            $usage = $price->getUsageQuota($date, $date);
            $date_str = $date->format('Y-m-d');
            if (array_key_exists($date_str, $usage))
            {
                $quota[$price->id] = max($quota[$price->id] - $usage[$date_str], 0);
            }
        }

        return $quota;
    }

    public static function getDisabledDates($price, $quota_key)
    {
        $tmp_disabledDates = [];
        $format = 'Y-m-d';

        // Group all special quota from all tour price
        foreach ($price as $key => $tourprice)
        {
            $tmp_quota = [];
            foreach($tourprice->{$quota_key} as $quota)   		// Tour has special quota
            {
                $start = Carbon::parse($quota->date);
                $end = Carbon::parse($quota->to);

                while ($start <= $end)
                {
                    $add = true;

                    if (isset($quota->day_of_week) && $quota->day_of_week != "all")
                    {
                        $add = $start->dayOfWeek == $quota->getCarbonDayOfWeek();
                    }

                    if ($add)
                        $tmp_quota[$start->format($format)] = $quota->quota;

                    $start = $start->addDay();
                }
            }

            // Get used quota
            $end = Carbon::today()->addYear();
            $start = Carbon::today();
            $usage = $tourprice->getUsageQuota($start, $end);

            // Generate a list of disabled dates
            while ($start != $end)
            {
                $date_str = $start->format($format);
                $start = $start->addDay();

                $quota = $tourprice->dquota;
                if (array_key_exists($date_str, $tmp_quota))
                    $quota = $tmp_quota[$date_str];

                // deduct used quota
                if ($quota == -1)
                    continue;

                $used_quota = 0;
                if (array_key_exists($date_str, $usage))
                    $used_quota = $usage[$date_str];

                if ($quota <= $used_quota)
                {
                    // increase or set as 1 to count the disable date occurrence
                    if (array_key_exists($date_str, $tmp_disabledDates))
                        $tmp_disabledDates[$date_str]++;
                    else
                        $tmp_disabledDates[$date_str] = 1;
                }

            }
        }

        // Filter only those date is disabled in all price
        $disabledDates = [];
        foreach ($tmp_disabledDates as $date => $count)
        {
            if ($count == count($price))
                $disabledDates[] = $date;
        }

        return $disabledDates;
    }

    public static function getTransportationTimetable($date, $price_group_id){
        $today = Carbon::today();
        if ($today->dayOfWeek == Carbon::SUNDAY || $today->dayOfWeek == Carbon::SATURDAY)
            $dayType = "Weekend";
        else
            $dayType = "Weekday";


        $times = TransportationTimetable::where('pricegroupid',$price_group_id)->where('status','active')->where('Weekend/Weekday','like', '%'.$dayType.'%' )->orderBy('time','ASC')->get();
        return $times->pluck('time');
    }
}