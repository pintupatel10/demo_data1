<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class TurbojetReserve extends Model
{
    const COLUMN_ID = 'id';
    const COLUMN_ORDER_PRODUCT_ID = 'order_product_id';
    const COLUMN_IS_SUCCESS = 'is_success';
    const COLUMN_CONFIRMATION_LETTER = 'confirmation_letter';
    const COLUMN_TRANSACTION_ID = 'transaction_id';
    const COLUMN_RESERVATIONS = 'reservations';
    const COLUMN_TOTAL_PRICE = 'total_price';
    const COLUMN_ERROR_MESSAGE = 'error_message';
    const COLUMN_CREDIT_REMAIN = 'credit_remain';

    protected $casts = [
        self::COLUMN_RESERVATIONS => 'json'
    ];

    public function order_product()
    {
        return $this->belongsTo('App\OrderProduct');
    }

    public function getReservationTable()
    {
        if (!$this->{self::COLUMN_RESERVATIONS})
            return [];

        $tickets = $this->order_product->turbojets->groupBy(OrderProductTurbojet::COLUMN_TIME);

        $data = [];
        foreach ($this->{self::COLUMN_RESERVATIONS} as $reservation)
        {
            $time = Carbon::parse($reservation['departure_date_time'])->format('Y-m-d H:i');
            if ($tickets->has($time))
            {
                $ticket = $tickets->get($time)->first();

                $data[] = [
                    'time' => $time,
                    'route' => $ticket->{OrderProductTurbojet::COLUMN_FROM_CODE} . ' >> ' . $ticket->{OrderProductTurbojet::COLUMN_TO_CODE},
                    'from-code' => $ticket->{OrderProductTurbojet::COLUMN_FROM_CODE},
                    'to-code' => $ticket->{OrderProductTurbojet::COLUMN_TO_CODE},
                    'class' => OrderProductTurbojet::$classes[$ticket->{OrderProductTurbojet::COLUMN_SEAT_CLASS}],
                    'class-code' => $ticket->{OrderProductTurbojet::COLUMN_SEAT_CLASS},
                    'reservation-no' => $reservation['reservation_no'],
                    'promotion' => $ticket->{OrderProductTurbojet::COLUMN_PROMO_CODE} ?: trans('turbojet.adult'),
                    'promotion-code' => $ticket->{OrderProductTurbojet::COLUMN_PROMO_CODE},
                    'type' => 'LOC',
                ];
            }
        }
        return $data;
    }

    public function generateQRCode()
    {
        return \QrCode::format('png')->size(160)->margin(0)->generate($this->{self::COLUMN_TRANSACTION_ID});
    }

    public function getLetterStoragePath()
    {
        return storage_path('app/' . $this->{self::COLUMN_CONFIRMATION_LETTER});
    }

    public function getAttachmentName()
    {
        return "Turbojet-eTicket-" . $this->{self::COLUMN_TRANSACTION_ID} . ".pdf";
    }
}
