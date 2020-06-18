<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TurbojetCouponRoute extends Model
{
    const COLUMN_ID = 'id';
    const COLUMN_NAME = 'name';
    const COLUMN_CITY_1 = 'city_1';
    const COLUMN_CITY_2 = 'city_2';

    protected $casts = [
        self::COLUMN_CITY_1 => 'json',
        self::COLUMN_CITY_2 => 'json',
    ];

    public static function getDropdown()
    {
        $data = [];
        foreach (TurbojetCouponRoute::all() as $route)
        {
            $data[$route->id] = $route->{self::COLUMN_NAME};
        }
        return $data;
    }

    public function isValidCity($from, $to)
    {
        if (in_array($from, $this->{self::COLUMN_CITY_1}) && in_array($to, $this->{self::COLUMN_CITY_2}))
            return true;

        if (in_array($from, $this->{self::COLUMN_CITY_2}) && in_array($to, $this->{self::COLUMN_CITY_1}))
            return true;

        return false;
    }
}
