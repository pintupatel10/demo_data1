<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransportationPricegroup extends Model
{
    use SoftDeletes;
    protected $table="transportation_pricegroups";
    protected $fillable = [
        'detailid','title','description','charge','time_table','displayorder','status','report_code'
    ];

    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';

    public static $status = [
        self::STATUS_ACTIVE => 'Active',
        self::STATUS_INACTIVE => 'In Active',
    ];


    const TIME_ON = 'On';
    const TIME_OFF = 'Off';

    public static $time = [
        self::TIME_ON => 'On',
        self::TIME_OFF => 'Off',
    ];

    public function TransportationList()
    {
        return $this->belongsTo('App\TransportationList', 'detailid');
    }


    public function TransportationPrice()
    {
        return $this->hasMany('App\TransportationPrice', 'pricegroupid');
    }

    public function TransportationTimetable()
    {
        return $this->hasMany('App\TransportationTimetable', 'pricegroupid');
    }

    public function turbojet_ticket()
    {
        return $this->morphOne('App\TurbojetTicket', 'price_group');
    }

    // Ticket's Service Charge column is named as "Servicecharge", to make reserve form easy to use add a alias on transportation
    public function getServicechargeAttribute()
    {
        return $this->charge;
    }
}
