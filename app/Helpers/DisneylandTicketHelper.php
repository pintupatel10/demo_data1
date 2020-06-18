<?php
/**
 * Created by PhpStorm.
 * User: yeelok
 * Date: 15/3/2017
 * Time: 20:28
 */

namespace App\Helpers;


use App\DisneylandReserve;
use App\DisneylandTicket;
use App\OrderProduct;
use App\OrderProductPackage;

class DisneylandTicketHelper
{
    public static function reserveTicket($order_product)
    {
        $tickets = collect();
        foreach ($order_product->packages as $package)
        {
            foreach ($package->price->disneyland_tickets as $ticket)
            {
                for ($i = 0; $i < $package->{OrderProductPackage::COLUMN_QUANTITY}; $i++)
                {
                    $event_code = $ticket->{DisneylandTicket::COLUMN_EVENT_CODE};
                    $pickup_id = $ticket->{DisneylandTicket::COLUMN_PICKUP_ID};

                    $tickets->push([
                        'event-code' => $event_code,
                        'ticket-code' => $ticket->{DisneylandTicket::COLUMN_TICKET_CODE},
                        'pickup-id' => $pickup_id,
                        'identify' => $event_code . $pickup_id,         // Use to group up tickets with same event_code and pickup_id
                    ]);
                }
            }
        }

        $reserve_records = [];

        try {
            $max_ticket = DisneylandApiHelper::getMaxTicket();
            $language = DisneylandApiHelper::LANG_EN;
            if ($order_product->{OrderProduct::COLUMN_LANGUAGE} == 'zh-hk')
                $language = DisneylandApiHelper::LANG_HK;
            else if ($order_product->{OrderProduct::COLUMN_LANGUAGE} == 'zh-cn')
                $language = DisneylandApiHelper::LANG_CN;

            $ref_no = str_replace('-', '', $order_product->{OrderProduct::COLUMN_ID});
            $ref_no = substr($ref_no, 0, 3) . substr($ref_no, 5) . 'A';

            $events = $tickets->groupBy('identify');          // Tickets in different events have to be reserved in separated request
            foreach ($events as $event) {
                $chunks = $event->chunk($max_ticket);           // Create chunks to reserve tickets
                foreach ($chunks as $chunk) {
                    $ticket_groups = $chunk->groupBy('ticket-code');      // Group and count the tickets

                    $items = [];
                    foreach ($ticket_groups as $ticket_code => $ticket_group) {
                        $items[] = [
                            'ticketId' => $ticket_code,
                            'qty' => $ticket_group->count()
                        ];
                    }

                    // Reserve
                    $event_code = $chunk->first()['event-code'];
                    $pickup_id = $chunk->first()['pickup-id'];

                    try {
                        $response = DisneylandApiHelper::reserveOrder($ref_no, $event_code, $order_product->name, $language, $pickup_id, $items);

                        $reserve = new DisneylandReserve();
                        $reserve->{DisneylandReserve::COLUMN_ORDER_PRODUCT_ID} = $order_product->{OrderProduct::COLUMN_ID};
                        $reserve->{DisneylandReserve::COLUMN_IS_SUCCESS} = true;
                        $reserve->{DisneylandReserve::COLUMN_RESERVATION_NO} = $response['reservationNo'];
                        $reserve->{DisneylandReserve::COLUMN_VOUCHER_NO} = $response['voucherNo'];
                        $reserve->{DisneylandReserve::COLUMN_CONFIRMATION_LETTER} = DisneylandApiHelper::saveConfirmationLetter($response);
                        $reserve->save();

                        $reserve = $reserve->fresh();
                        $reserve_records[] = $reserve;

                    }
                    catch (\Exception $e)
                    {
                        $reserve = new DisneylandReserve();
                        $reserve->{DisneylandReserve::COLUMN_ORDER_PRODUCT_ID} = $order_product->{OrderProduct::COLUMN_ID};
                        $reserve->{DisneylandReserve::COLUMN_IS_SUCCESS} = false;
                        $reserve->{DisneylandReserve::COLUMN_ERROR_MESSAGE} = $e->getMessage();
                        $reserve->save();
                    }

                    // Increase the reference number
                    $ref_no++;
                }
            }
        }
        catch (\Exception $e)
        {
            $reserve = new DisneylandReserve();
            $reserve->{DisneylandReserve::COLUMN_ORDER_PRODUCT_ID} = $order_product->{OrderProduct::COLUMN_ID};
            $reserve->{DisneylandReserve::COLUMN_IS_SUCCESS} = false;
            $reserve->{DisneylandReserve::COLUMN_ERROR_MESSAGE} = $e->getMessage();
            $reserve->save();
        }

        return $reserve_records;
    }
}