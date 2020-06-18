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
use App\OceanParkReserve;
use App\OceanParkTicket;
use App\OrderProduct;
use App\OrderProductPackage;

class OceanParkTicketHelper
{
    public static function reserveTicket($order_product)
    {
        $tickets = collect();
        foreach ($order_product->packages as $package)
        {
            foreach ($package->price->oceanpark_tickets as $ticket)
            {
                for ($i = 0; $i < $package->{OrderProductPackage::COLUMN_QUANTITY}; $i++)
                {
                    $tickets->push([
                        'event-id' => $ticket->{OceanParkTicket::COLUMN_EVENT_ID},
                        'type' => $ticket->{OceanParkTicket::COLUMN_TYPE},
                        'type-id' => $ticket->{OceanParkTicket::COLUMN_TYPE_ID},
                    ]);
                }
            }
        }

        $reserve_records = [];

        try {
            $max_ticket = OceanParkApiHelper::getMaxTicket();
            $language = OceanParkApiHelper::LANG_EN;
            if ($order_product->{OrderProduct::COLUMN_LANGUAGE} == 'zh-hk')
                $language = OceanParkApiHelper::LANG_HK;
            else if ($order_product->{OrderProduct::COLUMN_LANGUAGE} == 'zh-cn')
                $language = OceanParkApiHelper::LANG_CN;

            $events = $tickets->groupBy('event-id');            // Tickets in different events have to be reserved in separated request
            foreach ($events as $event) {
                $chunks = $event->chunk($max_ticket);           // Create chunks to reserve tickets
                foreach ($chunks as $chunk) {
                    $ticket_groups = $chunk->groupBy('type-id');      // Group and count the tickets

                    $key = $chunk->first()['type'] == OceanParkTicket::TYPE_TICKET ? "ticketId" : "packageId";
                    $item_key = $chunk->first()['type'] == OceanParkTicket::TYPE_TICKET ? "items" : "packageItems";

                    $items = [];
                    foreach ($ticket_groups as $ticket_code => $ticket_group) {
                        $items[] = [
                            $key => $ticket_code,
                            'qty' => $ticket_group->count()
                        ];
                    }

                    // Reserve
                    $event_code = $chunk->first()['event-id'];

                    try {
                        $response = OceanParkApiHelper::reserveOrder($event_code, $order_product->name, $language, $item_key, $items);

                        $reserve = new OceanParkReserve();
                        $reserve->{OceanParkReserve::COLUMN_ORDER_PRODUCT_ID} = $order_product->{OrderProduct::COLUMN_ID};
                        $reserve->{OceanParkReserve::COLUMN_IS_SUCCESS} = true;
                        $reserve->{OceanParkReserve::COLUMN_RESERVATION_NO} = $response['reservationNo'];
                        $reserve->{OceanParkReserve::COLUMN_VOUCHER_NO} = $response['voucherNo'];
                        $reserve->{OceanParkReserve::COLUMN_CONFIRMATION_LETTER} = OceanParkApiHelper::saveConfirmationLetter($response);
                        $reserve->save();

                        $reserve = $reserve->fresh();
                        $reserve_records[] = $reserve;

                    }
                    catch (\Exception $e)
                    {
                        $reserve = new OceanParkReserve();
                        $reserve->{OceanParkReserve::COLUMN_ORDER_PRODUCT_ID} = $order_product->{OrderProduct::COLUMN_ID};
                        $reserve->{OceanParkReserve::COLUMN_IS_SUCCESS} = false;
                        $reserve->{OceanParkReserve::COLUMN_ERROR_MESSAGE} = $e->getMessage();
                        $reserve->save();
                    }
                }
            }
        }
        catch (\Exception $e)
        {
            $reserve = new OceanParkReserve();
            $reserve->{OceanParkReserve::COLUMN_ORDER_PRODUCT_ID} = $order_product->{OrderProduct::COLUMN_ID};
            $reserve->{OceanParkReserve::COLUMN_IS_SUCCESS} = false;
            $reserve->{OceanParkReserve::COLUMN_ERROR_MESSAGE} = $e->getMessage();
            $reserve->save();
        }

        return $reserve_records;
    }
}