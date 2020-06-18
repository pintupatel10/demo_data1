<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderProductPackage extends Model
{
    const COLUMN_ID = 'id';
    const COLUMN_ORDER_ID = 'order_id';
    const COLUMN_ORDER_PRODUCT_ID = 'order_product_id';
    const COLUMN_PRICE_TYPE = 'price_type';
    const COLUMN_PRICE_ID = 'price_id';
    const COLUMN_QUANTITY = 'quantity';
    const COLUMN_TITLE = 'title';
    const COLUMN_UNIT_ORIGINAL_PRICE = 'unit_original_price';
    const COLUMN_UNIT_DISCOUNT = 'unit_discount';
    const COLUMN_UNIT_FINAL_PRICE = 'unit_final_price';

    public $incrementing = false;

    public function product()
    {
        return $this->belongsTo('App\OrderProduct', self::COLUMN_ORDER_PRODUCT_ID);
    }

    public function price()
    {
        return $this->morphTo()->withTrashed();
    }
}
