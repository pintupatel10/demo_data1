<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TourPricegroup extends Model
{
    use SoftDeletes;
    protected $table="tour_pricegroups";
    protected $fillable = [
        'detailid','title','description','displayorder','status','report_code'
    ];

    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';

    public static $status = [
        self::STATUS_ACTIVE => 'Active',
        self::STATUS_INACTIVE => 'In Active',
    ];

    public function TourList()
    {
        return $this->belongsTo('App\TourList', 'detailid');
    }

    public function TourPrice()
    {
        return $this->hasMany('App\TourPrice', 'pricegroupid');
    }

    public function TourInventory()
    {
        return $this->hasMany('App\TourInventory', 'pricegroupid');
    }

    public function turbojet_ticket()
    {
        return $this->morphOne('App\TurbojetTicket', 'price_group');
    }

    public function turbojet_timetables()
    {
        return $this->morphMany('App\TurbojetTimetable', 'price_group');
    }

    public function getTurbojetTimetable($direction, $departure, $class, $date)
    {
        if (!$this->turbojet_ticket)
            abort(404);

        if ($this->turbojet_ticket->{TurbojetTicket::COLUMN_DEPARTURE_CITY} == TurbojetTicket::DEPARTURE_CITY_ANY)
        {
            if (!$departure)
                abort(404);

            if ($departure == "city_1")
            {
                $departure_code = $this->turbojet_ticket->{TurbojetTicket::COLUMN_CITY_1_CODE};
                $departure_city = $this->turbojet_ticket->{TurbojetTicket::COLUMN_CITY_1};
                $return_code = $this->turbojet_ticket->{TurbojetTicket::COLUMN_CITY_2_CODE};
                $return_city = $this->turbojet_ticket->{TurbojetTicket::COLUMN_CITY_2};
            }
            else
            {
                $departure_code = $this->turbojet_ticket->{TurbojetTicket::COLUMN_CITY_2_CODE};
                $departure_city = $this->turbojet_ticket->{TurbojetTicket::COLUMN_CITY_2};
                $return_code = $this->turbojet_ticket->{TurbojetTicket::COLUMN_CITY_1_CODE};
                $return_city = $this->turbojet_ticket->{TurbojetTicket::COLUMN_CITY_1};
            }
        }
        else
        {
            $departure_code = $this->turbojet_ticket->departure_city_code;
            $departure_city = $this->turbojet_ticket->departure_city_name;
            $return_code = $this->turbojet_ticket->return_city_code;
            $return_city = $this->turbojet_ticket->return_city_name;
        }

        if ($this->turbojet_ticket->{TurbojetTicket::COLUMN_TYPE} == TurbojetTicket::TYPE_ROUND_TRIP)
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
            $seat_class = TurbojetTimetable::CLASS_ECONOMY;
        else if ($class == "super")
            $seat_class = TurbojetTimetable::CLASS_SUPER;
        else if ($class == "primer-grand")
            $seat_class = TurbojetTimetable::CLASS_PREMIER_GRAND;
        else
            abort(404);

        $query = $this->turbojet_timetables()
            ->where(TurbojetTimetable::COLUMN_CLASS, $seat_class)
            ->where(TurbojetTimetable::COLUMN_FROM, $departure_code)
            ->where(TurbojetTimetable::COLUMN_TO, $return_code);

        if ($date->dayOfWeek == Carbon::SUNDAY || $date->dayOfWeek == Carbon::SATURDAY)
            $query->where(TurbojetTimetable::COLUMN_IS_WEEKEND, true);
        else
            $query->where(TurbojetTimetable::COLUMN_IS_WEEKDAY, true);

        $is_holiday = TurbojetHoliday::where(TurbojetHoliday::COLUMN_DATE, $date->format('Y-m-d'))->count() > 0;
        if ($is_holiday)
            $query->where(TurbojetTimetable::COLUMN_IS_HOLIDAY, true);
        else
            $query->where(TurbojetTimetable::COLUMN_IS_NON_HOLIDAY, true);

        return $query->orderBy(TurbojetTimetable::COLUMN_TIME)->get()->pluck(TurbojetTimetable::COLUMN_TIME);
    }
}
