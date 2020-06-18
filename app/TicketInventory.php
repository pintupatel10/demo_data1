<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TicketInventory extends Model
{
    use SoftDeletes;
    protected $table="ticket_inventorys";
    protected $fillable = [
        'detailid','pricegroupid','title','dquota','displayorder','status'
    ];

    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';

    public static $status = [
        self::STATUS_ACTIVE => 'Active',
        self::STATUS_INACTIVE => 'In Active',
    ];

    public function TicketList()
    {
        return $this->belongsTo('App\TicketList', 'id');
    }

    public function TicketPricegroup()
    {
        return $this->belongsTo('App\TicketPricegroup', 'id');
    }
    
}
