<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransportationPrice extends Model
{
    use SoftDeletes;
    protected $table="transportation_prices";
    protected $fillable = [
        'detailid','pricegroupid','title','Weekend/Weekday','price','dquota','displayorder','status','report_price_type',
    ];

    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';

    public static $status = [
        self::STATUS_ACTIVE => 'Active',
        self::STATUS_INACTIVE => 'In Active',
    ];

    const TYPE_Weekend = 'Weekend';
    const TYPE_Weekday = 'Weekday';
    const TYPE_Weekend_Weekday= 'Weekend & Weekday';

   

    public static $type = [
        self::TYPE_Weekend => 'Weekend',
        self::TYPE_Weekday => 'Weekday',
        self::TYPE_Weekend_Weekday => 'Weekend & Weekday',
    ];

    public static $report_price_type = [
        self::TYPE_Adult => 'Adult',
        self::TYPE_Child => 'Child',
        self::TYPE_Senior => 'Senior',
    ];

    const TYPE_Adult = 'Adult';
    const TYPE_Child = 'Child';
    const TYPE_Senior= 'Senior';
    
    public function TransportationList()
    {
        return $this->belongsTo('App\TransportationList', 'id');
    }

    public function TransportationPricegroup()
    {
        return $this->belongsTo('App\TransportationPricegroup', 'id');
    }

    public function TransportationQuota()
    {
        return $this->hasMany('App\TransportationQuota', 'quotaid');
    }

    public function order_product_packages()
    {
        return $this->morphMany('App\OrderProductPackage', 'price');
    }

    public function getUsageQuota($start, $end)
    {
        $packages = $this->order_product_packages()->whereHas('product', function ($query) use ($start, $end) {
            $query->where(OrderProduct::COLUMN_DATE, '>=', $start);
            $query->where(OrderProduct::COLUMN_DATE, '<=', $end);
            $query->whereIn(OrderProduct::COLUMN_STATUS, OrderProduct::$valid_status);
        })->get();

        $usage = [];
        foreach ($packages as $package)
        {
            $date_str = $package->product->{OrderProduct::COLUMN_DATE}->format('Y-m-d');
            if (array_key_exists($date_str, $usage))
                $usage[$date_str] += $package->{OrderProductPackage::COLUMN_QUANTITY};
            else
                $usage[$date_str] = $package->{OrderProductPackage::COLUMN_QUANTITY};
        }

        return $usage;
    }
}
