<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderConfirmation extends Model
{
    const COLUMN_ID = 'id';
    const COLUMN_ORDER_ID = 'order_id';
    const COLUMN_CONTENT = 'content';
    const COLUMN_ORDER_PRODUCT_IDS = 'order_product_ids';
    const COLUMN_ORDER_PRODUCT_ATTACHMENT_IDS = 'order_product_attachment_ids';
    const COLUMN_USER_ID = 'user_id';

    protected $casts = [
        self::COLUMN_ORDER_PRODUCT_ATTACHMENT_IDS => 'json',
        self::COLUMN_ORDER_PRODUCT_IDS => 'json',
    ];

    public $incrementing = false;

    public function order()
    {
        return $this->belongsTo('App\Order');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public static function createConfirmationForOrder($order)
    {
        $confirmation = new OrderConfirmation();
        $confirmation->{self::COLUMN_ID} = $order->{Order::COLUMN_ID} . '-C' . ($order->confirmations->count() + 1);
        $confirmation->{self::COLUMN_ORDER_ID} = $order->{Order::COLUMN_ID};
        return $confirmation;
    }

    public function getAttachments()
    {
        $attachments = [];
        foreach ($this->{self::COLUMN_ORDER_PRODUCT_ATTACHMENT_IDS} as $id)
        {
            $attachments[] = OrderProductAttachment::withTrashed()->findOrFail($id);
        }
        return $attachments;
    }
}
