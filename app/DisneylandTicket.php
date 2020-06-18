<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DisneylandTicket extends Model
{
    use SoftDeletes;

    const COLUMN_ID = 'id';
    const COLUMN_PRICE_TYPE = 'price_type';
    const COLUMN_PRICE_ID = 'price_id';
    const COLUMN_EVENT_CODE = 'event_code';
    const COLUMN_TICKET_CODE = 'ticket_code';
    const COLUMN_PICKUP_ID = 'pickup_id';

    protected $fillable = [
        self::COLUMN_EVENT_CODE,
        self::COLUMN_TICKET_CODE,
        self::COLUMN_PICKUP_ID
    ];

    public function price()
    {
        return $this->morphTo();
    }

}
