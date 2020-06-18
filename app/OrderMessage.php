<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderMessage extends Model
{
    const COLUMN_ID = 'id';
    const COLUMN_ORDER_ID = 'order_id';
    const COLUMN_TYPE = 'type';
    const COLUMN_CONTACT_VIA = 'contact_via';
    const COLUMN_MESSAGE = 'message';
    const COLUMN_FROM_ADDRESS = 'from_address';
    const COLUMN_RAW_EMAIL = 'raw_email';
    const COLUMN_NOTIFICATION_SENT_AT = 'notification_sent_at';
    const COLUMN_REPLY_BY = 'reply_by';

    const TYPE_ENQUIRE = 'enquire';         // message from customer
    const TYPE_REPLY = 'reply';             // message from staff

    const CONTACT_VIA_EMAIL = 'email';
    const CONTACT_VIA_WEB = 'web';

    protected $dates = [
        self::COLUMN_NOTIFICATION_SENT_AT,
    ];

    public function order()
    {
        return $this->belongsTo('App\Order');
    }

    public function attachments()
    {
        return $this->hasMany('App\OrderMessageAttachment');
    }

    public function reply_user()
    {
        return $this->belongsTo('App\User', self::COLUMN_REPLY_BY);
    }
}
