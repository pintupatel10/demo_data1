<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DisneylandReserve extends Model
{
    const COLUMN_ID = 'id';
    const COLUMN_ORDER_PRODUCT_ID = 'order_product_id';
    const COLUMN_IS_SUCCESS = 'is_success';
    const COLUMN_CONFIRMATION_LETTER = 'confirmation_letter';
    const COLUMN_RESERVATION_NO = 'reservation_no';
    const COLUMN_VOUCHER_NO = 'voucher_no';
    const COLUMN_ERROR_MESSAGE = 'error_message';

    public function order_product()
    {
        return $this->belongsTo('App\OrderProduct');
    }

    public function getLetterStoragePath()
    {
        return storage_path('app/' . $this->{self::COLUMN_CONFIRMATION_LETTER});
    }

    public function getAttachmentName()
    {
        return "Disneyland-eTicket-" . $this->{self::COLUMN_RESERVATION_NO} . ".pdf";
    }
}
