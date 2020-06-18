<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TicketFilter extends Model
{
    use SoftDeletes;
    protected $table="ticket_filters";
    protected $fillable = [
        'cid','name','group_list','ticket_list','displayorder','status'
    ];

    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';

    public static $status = [
        self::STATUS_ACTIVE => 'Active',
        self::STATUS_INACTIVE => 'In Active',
    ];

    public function Ticketcollection()
    {
        return $this->belongsTo('App\Ticketcollection', 'id');
    }
}
