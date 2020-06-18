<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OceanParkTicket extends Model
{
    use SoftDeletes;

    protected $table = 'oceanpark_tickets';

    const COLUMN_ID = 'id';
    const COLUMN_PRICE_TYPE = 'price_type';
    const COLUMN_PRICE_ID = 'price_id';
    const COLUMN_EVENT_ID = 'event_id';
    const COLUMN_TYPE = 'type';
    const COLUMN_TYPE_ID = 'type_id';

    const TYPE_TICKET = 'ticket';
    const TYPE_PACKAGE = 'package';

    protected $fillable = [
        self::COLUMN_EVENT_ID,
        self::COLUMN_TYPE,
        self::COLUMN_TYPE_ID
    ];

    public static $types = [
        self::TYPE_TICKET => 'Ticket',
        self::TYPE_PACKAGE => 'Package',
    ];

    public function price()
    {
        return $this->morphTo();
    }
}
