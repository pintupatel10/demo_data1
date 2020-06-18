<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticketcheckpoint extends Model
{
    use SoftDeletes;
    protected $table="ticket_checkpoints";
    protected $fillable = [
        'detailid','title','image','description','displayorder','status'
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
}
