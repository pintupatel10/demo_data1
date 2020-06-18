<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class TurbojetCoupon extends Model
{
    const COLUMN_ID = 'id';
    const COLUMN_TYPE = 'type';
    const COLUMN_SEAT_CLASS = 'seat_class';
    const COLUMN_IS_WEEKDAY = 'is_weekday';
    const COLUMN_IS_WEEKEND = 'is_weekend';
    const COLUMN_IS_DAY = 'is_day';
    const COLUMN_IS_NIGHT = 'is_night';
    const COLUMN_CODE = 'code';
    const COLUMN_ROUTE_ID = 'route_id';

    const TYPE_ONE_WAY = 'one-way';
    const TYPE_ROUND_TRIP = 'round-trip';

    const CLASS_ECONOMY = "economy";
    const CLASS_SUPER = "super";
    const CLASS_PRIMER_GRAND = "primer-grand";

    public static $types = [
        self::TYPE_ONE_WAY => 'One Way',
        self::TYPE_ROUND_TRIP => 'Round Trip',
    ];

    public static $classes = [
        self::CLASS_ECONOMY => "Economy",
        self::CLASS_SUPER => "Super",
        self::CLASS_PRIMER_GRAND => "Primer Grand",
    ];

    protected $fillable = [
        self::COLUMN_TYPE, self::COLUMN_SEAT_CLASS, self::COLUMN_CODE, self::COLUMN_ROUTE_ID
    ];

    public function route()
    {
        return $this->belongsTo('App\TurbojetCouponRoute', self::COLUMN_ROUTE_ID);
    }

    public static function getCode($type, $class, $time, $from_code, $to_code)
    {
        $time = Carbon::parse($time);

        $query = TurbojetCoupon::where(self::COLUMN_TYPE, $type)->where(self::COLUMN_SEAT_CLASS, $class);

        if ($time->dayOfWeek == Carbon::SUNDAY || $time->dayOfWeek == Carbon::SATURDAY)
            $query->where(self::COLUMN_IS_WEEKEND, true);
        else
            $query->where(self::COLUMN_IS_WEEKDAY, true);

        if (self::isNightSail($time))
            $query->where(self::COLUMN_IS_NIGHT, true);
        else
            $query->where(self::COLUMN_IS_DAY, true);

        foreach ($query->get() as $coupon)
        {
            if (!$coupon->route || $coupon->route->isValidCity($from_code, $to_code))
                return $coupon->{self::COLUMN_CODE};
        }

        return "";
    }

    private static function isNightSail($datetime)
    {
        $date = $datetime->copy()->hour(0)->minute(0)->second(0);       // Only keeps the date value for comparison
        $time = Carbon::parse($datetime->format("H:i"));                // Parse only the time so to make new Carbon object on Today with specific time for comparison
        $year = Carbon::today()->year;

        if (($date >= Carbon::parse("$year-01-01") && $date <= Carbon::parse("$year-01-31")) ||
            ($date >= Carbon::parse("$year-10-08") && $date <= Carbon::parse("$year-12-31")))
        {
            if ($time >= Carbon::parse("17:10") || $time <= Carbon::parse("06:30"))
                return true;
        }
        else if (($date >= Carbon::parse("$year-02-01") && $date <= Carbon::parse("$year-03-31")) ||
                 ($date >= Carbon::parse("$year-09-01") && $date <= Carbon::parse("$year-10-07")))
        {
            if ($time >= Carbon::parse("17:40") || $time <= Carbon::parse("06:30"))
                return true;
        }
        else if ($date >= Carbon::parse("$year-04-01") && $date <= Carbon::parse("$year-08-31"))
        {
            if ($time >= Carbon::parse("18:10") || $time <= Carbon::parse("06:30"))
                return true;
        }

        return false;
    }
}
