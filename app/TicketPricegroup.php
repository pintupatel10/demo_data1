<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TicketPricegroup extends Model
{
    use SoftDeletes;
    protected $table="ticket_pricegroups";
    protected $fillable = [
        'detailid','title','description','displayorder','status','servicecharge','report_code'
    ];

    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';

    public static $status = [
        self::STATUS_ACTIVE => 'Active',
        self::STATUS_INACTIVE => 'In Active',
    ];

    public function TicketList()
    {
        return $this->belongsTo('App\TicketList', 'detailid');
    }

    public function TicketPrice()
    {
        return $this->hasMany('App\TicketPrice', 'pricegroupid');
    }
    
    public function TicketVolume()
    {
        return $this->hasMany('App\TicketVolume', 'pricegroupid');
    }
}
