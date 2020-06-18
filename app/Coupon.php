<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Coupon extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'tourlist_id','title','couponcode','type','earlydate','orderdate_start','orderdate_end','discountby','discount','quota','status',
    ];
    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'in-active';

    public static $status = [
        self::STATUS_ACTIVE => 'Active',
        self::STATUS_INACTIVE => 'In Active',
    ];
    const DISCOUNT_PRICE = 'percentage';
    const DISCOUNT_PERCENT= 'price';

    public static $type = [
        self::TYPE_EARLY => 'Eearly',
        self::TYPE_ORDER => 'Order',
    ];
    const TYPE_EARLY = 'Eearly';
    const TYPE_ORDER= 'Order';

    public static $discountby = [
        self::DISCOUNT_PRICE => 'Percentage',
        self::DISCOUNT_PERCENT => 'Fix Price',
    ];

    public function getUsedCount()
    {
        return OrderProduct::whereIn(OrderProduct::COLUMN_STATUS, OrderProduct::$valid_status)
            ->where(OrderProduct::COLUMN_TYPE, OrderProduct::TYPE_TOUR)
            ->where(OrderProduct::COLUMN_PROMOCODE, $this->couponcode)
            ->count();
    }
}
