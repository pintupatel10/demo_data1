<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TurbojetTicket extends Model
{
    const COLUMN_ID = 'id';
    const COLUMN_PRICE_GROUP_ID = 'price_group_id';
    const COLUMN_PRICE_GROUP_TYPE = 'price_group_type';
    const COLUMN_TYPE = 'type';
    const COLUMN_DEPARTURE_CITY = 'departure_city';
    const COLUMN_CITY_1 = 'city_1';
    const COLUMN_CITY_1_CODE = 'city_1_code';
    const COLUMN_CITY_2 = 'city_2';
    const COLUMN_CITY_2_CODE = 'city_2_code';
    const COLUMN_TOP_UP_FEE = 'top_up_fee';

    const TYPE_ONE_WAY = 'one-way';
    const TYPE_ROUND_TRIP = 'round-trip';

    const DEPARTURE_CITY_1 = 'city-1';
    const DEPARTURE_CITY_2 = 'city-2';
    const DEPARTURE_CITY_ANY = 'any';

    public static $types = [
        self::TYPE_ONE_WAY => 'One Way',
        self::TYPE_ROUND_TRIP => 'Round Trip',
    ];

    public static $departures = [
        self::DEPARTURE_CITY_ANY => 'Any',
        self::DEPARTURE_CITY_1 => 'City 1',
        self::DEPARTURE_CITY_2 => 'City 2',
    ];

    protected $fillable = [
        self::COLUMN_TYPE, self::COLUMN_DEPARTURE_CITY, self::COLUMN_CITY_1, self::COLUMN_CITY_1_CODE,
        self::COLUMN_CITY_2, self::COLUMN_CITY_2_CODE, self::COLUMN_TOP_UP_FEE,
    ];

    public function price_group()
    {
        return $this->morphTo();
    }

    public function getDepartureCityCodeAttribute()
    {
        if ($this->{self::COLUMN_DEPARTURE_CITY} == self::DEPARTURE_CITY_ANY)
            return "";
        else if ($this->{self::COLUMN_DEPARTURE_CITY} == self::DEPARTURE_CITY_1)
            return $this->{self::COLUMN_CITY_1_CODE};
        else
            return $this->{self::COLUMN_CITY_2_CODE};
    }

    public function getDepartureCityNameAttribute()
    {
        if ($this->{self::COLUMN_DEPARTURE_CITY} == self::DEPARTURE_CITY_ANY)
            return "";
        else if ($this->{self::COLUMN_DEPARTURE_CITY} == self::DEPARTURE_CITY_1)
            return $this->{self::COLUMN_CITY_1};
        else
            return $this->{self::COLUMN_CITY_2};
    }

    public function getReturnCityCodeAttribute()
    {
        if ($this->{self::COLUMN_DEPARTURE_CITY} == self::DEPARTURE_CITY_ANY)
            return "";
        else if ($this->{self::COLUMN_DEPARTURE_CITY} == self::DEPARTURE_CITY_1)
            return $this->{self::COLUMN_CITY_2_CODE};
        else
            return $this->{self::COLUMN_CITY_1_CODE};
    }

    public function getReturnCityNameAttribute()
    {
        if ($this->{self::COLUMN_DEPARTURE_CITY} == self::DEPARTURE_CITY_ANY)
            return "";
        else if ($this->{self::COLUMN_DEPARTURE_CITY} == self::DEPARTURE_CITY_1)
            return $this->{self::COLUMN_CITY_2};
        else
            return $this->{self::COLUMN_CITY_1};
    }

}
