<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TurbojetTimetable extends Model
{
    const COLUMN_ID = 'id';
    const COLUMN_PRICE_GROUP_ID = 'price_group_id';
    const COLUMN_PRICE_GROUP_TYPE = 'price_group_type';
    const COLUMN_FROM = 'from';
    const COLUMN_TO = 'to';
    const COLUMN_CLASS = 'class';
    const COLUMN_TIME = 'time';
    const COLUMN_IS_WEEKDAY = 'is_weekday';
    const COLUMN_IS_WEEKEND = 'is_weekend';
    const COLUMN_IS_HOLIDAY = 'is_holiday';
    const COLUMN_IS_NON_HOLIDAY = 'is_non_holiday';

    const CLASS_ECONOMY = "E";
    const CLASS_SUPER = "S";
    const CLASS_PREMIER_GRAND = "SP";

    public static $classes = [
        self::CLASS_ECONOMY, self::CLASS_SUPER, self::CLASS_PREMIER_GRAND
    ];

    public function price_group()
    {
        return $this->morphTo();
    }
}
