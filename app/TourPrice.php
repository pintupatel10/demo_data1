<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TourPrice extends Model
{
    use SoftDeletes;
    protected $table="tour_prices";
    protected $fillable = [
        'detailid','pricegroupid','title','price','dquota','displayorder','status','report_price_type',
    ];

    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';

    public static $status = [
        self::STATUS_ACTIVE => 'Active',
        self::STATUS_INACTIVE => 'In Active',
    ];

    public static $report_price_type = [
        self::TYPE_Adult => 'Adult',
        self::TYPE_Child => 'Child',
        self::TYPE_Senior => 'Senior',
    ];

    const TYPE_Adult = 'Adult';
    const TYPE_Child = 'Child';
    const TYPE_Senior= 'Senior';
    
    public function TourList()
    {
        return $this->belongsTo('App\TourList', 'detailid');
    }

    public function TourPricegroup()
    {
        return $this->belongsTo('App\TourPricegroup', 'id');
    }

    public function Quota()
    {
        return $this->hasMany('App\Quota', 'quotaid');
    }

    public function order_product_packages()
    {
        return $this->morphMany('App\OrderProductPackage', 'price');
    }

    public function disneyland_tickets()
    {
        return $this->morphMany('App\DisneylandTicket', 'price');
    }

    public function oceanpark_tickets()
    {
        return $this->morphMany('App\OceanParkTicket', 'price');
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
