<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TicketPrice extends Model
{
    use SoftDeletes;
    protected $table="ticket_prices";
    protected $fillable = [
        'detailid','pricegroupid','title','price','volume','dquota','displayorder','status','report_price_type',
    ];

    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';

    public static $status = [
        self::STATUS_ACTIVE => 'Active',
        self::STATUS_INACTIVE => 'In Active',
    ];

    const VOLUME_Yes = 'Yes';
    const VOLUME_No = 'No';

    public static $volume = [
        self::VOLUME_Yes => 'Yes',
        self::VOLUME_No => 'No',
    ];

    const Please = '';
    const Title_Mr = 'Mr';
    const Title_Mrs = 'Mrs';
    const Title_Miss = 'Miss';
    public static $title_eng = [
        self::Please => 'Please select',
        self::Title_Mr => 'Mr',
        self::Title_Mrs => 'Mrs',
        self::Title_Miss => 'Miss',
    ];

    const Title_先生 = '先生';
    const Title_小姐 = '小姐';
    const Title_太太 = '太太';
    public static $title_td = [
        self::Please => 'Please select',
        self::Title_先生 => '先生',
        self::Title_小姐 => '小姐',
        self::Title_太太 => '太太',
    ];


    public static $title_sp = [
        self::Please => 'Please select',
        self::Title_先生 => '先生',
        self::Title_小姐 => '小姐',
        self::Title_太太 => '太太',
    ];

    public static $report_price_type = [
        self::TYPE_Adult => 'Adult',
        self::TYPE_Child => 'Child',
        self::TYPE_Senior => 'Senior',
    ];

    const TYPE_Adult = 'Adult';
    const TYPE_Child = 'Child';
    const TYPE_Senior= 'Senior';
    
    public function TicketList()
    {
        return $this->belongsTo('App\TicketList', 'detailid');
    }

    public function TicketPricegroup()
    {
        return $this->belongsTo('App\TicketPricegroup', 'id');
    }


    public function TicketVolume()
    {
        return $this->hasMany('App\TicketVolume', 'title1');
    }

    public function TicketQuota()
    {
        return $this->hasMany('App\TicketQuota', 'quotaid');
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
