<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderProductTurbojet extends Model
{
    const COLUMN_ID = 'id';
    const COLUMN_ORDER_PRODUCT_ID = 'order_product_id';
    const COLUMN_ROUTE = 'route';
    const COLUMN_TIME = 'time';
    const COLUMN_FROM_NAME = 'from_name';
    const COLUMN_FROM_CODE = 'from_code';
    const COLUMN_TO_NAME = 'to_name';
    const COLUMN_TO_CODE = 'to_code';
    const COLUMN_QUANTITY = 'quantity';
    const COLUMN_FLIGHT_NO = 'flight_no';
    const COLUMN_SEAT_CLASS = 'seat_class';
    const COLUMN_PROMO_CODE = 'promo_code';

    const CLASS_ECONOMY = "economy";
    const CLASS_SUPER = "super";
    const CLASS_PRIMER_GRAND = "primer-grand";

    public static $classes = [
        self::CLASS_ECONOMY => "Economy",
        self::CLASS_SUPER => "Super",
        self::CLASS_PRIMER_GRAND => "Primer Grand",
    ];

    public function order_product()
    {
        return $this->belongsTo('App\OrderProduct');
    }
}
