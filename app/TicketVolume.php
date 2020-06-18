<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TicketVolume extends Model
{
    use SoftDeletes;
    //protected $table="ticket_volumes";
    protected $fillable = [
        'detailid','pricegroupid','title','title1','type','volume','discount','discount1','date','to','displayorder','status',
    ];

    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';

    public static $status = [
        self::STATUS_ACTIVE => 'Active',
        self::STATUS_INACTIVE => 'In Active',
    ];

    const VOLUME_Multiple = 'Multiple';
    const VOLUME_Single = 'Single';

    public static $volume = [
        self::VOLUME_Multiple => 'Multiple',
        self::VOLUME_Single => 'Single',
    ];

    public function TicketList()
    {
        return $this->belongsTo('App\TicketList', 'id');
    }

    public function TicketPricegroup()
    {
        return $this->belongsTo('App\TicketPricegroup', 'id');
    }

    public function TicketPrice()
    {
        return $this->belongsTo('App\TicketPrice', 'title1');
    }
}
