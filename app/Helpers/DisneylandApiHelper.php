<?php
/**
 * Created by PhpStorm.
 * User: yeelok
 * Date: 10/3/2017
 * Time: 19:45
 */

namespace App\Helpers;

use Carbon\Carbon;
use GuzzleHttp\Client;
use Config;

class DisneylandApiHelper
{
    const LANG_EN = "en_US";
    const LANG_HK = "zh_TW";
    const LANG_CN = "zh_CN";

    public static function getEvents()
    {
        return self::call('OTA/GetEvents');
    }

    public static function getTickets($event_id, $show_id = null)
    {
        $data = [
            'eventId' => $event_id,
        ];

        if ($show_id)
            $data['showId'] = $show_id;

        return self::call('OTA/GetTickets', $data);
    }

    public static function getPickupDetails($event_id)
    {
        return self::call('OTA/GetPickupDetails', [
            'eventId' => $event_id,
        ]);
    }

    public static function getMaxTicket()
    {
        $event_response = self::getEvents();
        $events = $event_response['events'];
        $max_ticket = 999;
        foreach ($events as $event)
        {
            $max_ticket = min($max_ticket, $event['maxTicket']);
        }
        return $max_ticket;
    }

    public static function reserveOrder($ref_no, $event_id, $name, $lang, $pickup_id, $items)
    {
        return self::call('OTA/ReserveOrder', [
            'requestId' => Toolkit::getUniqueTimestamp(),
            'eventId' => $event_id,
            'guestName' => $name,
            'lang' => $lang,
            'pickupId' => $pickup_id,
            'referenceNo' => $ref_no,
            'items' => $items
        ]);
    }

    public static function getOrderStatus($reservation_no)
    {
        return self::call('OTA/GetOrderStatus', [
            'reservationNo' => $reservation_no,
        ]);
    }

    public static function cancelOrder($reservation_no)
    {
        return self::call('OTA/CancelOrder', [
            'reservationNo' => $reservation_no,
        ]);
    }

    public static function saveConfirmationLetter($response)
    {
        $letter = base64_decode($response['confirmationLetter']);
        $storage_path = 'disneyland/' . $response['reservationNo'] . ".pdf";
        \Storage::put($storage_path, $letter);
        return $storage_path;
    }

    private static function call($url, $message = [])
    {
        $config = Config::get('core.disneyland.' . env('DISNEYLAND_ENV', 'dev'));

        $message['agentId'] = $config['agent-id'];
        $message['requestTime'] = Carbon::now()->format('Y-m-d H:i:s');
        $message = json_encode($message);

        $signature = hash('sha256', $message . '||' . $config['secret-key']);

        $client = new Client();
        $client->setDefaultOption('verify', false);

        $response = $client->post($config['url'] . "/" . $url, [
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded',
            ],
            'timeout' => 30,
            'body' => [
                'message' => $message,
                'signature' => $signature,
            ]
        ]);

        if ($response->getStatusCode() != 200)
            throw new \Exception("HTTP Response Code is " . $response->getStatusCode());

        $result = $response->json();

        if ($result['responseCode'] != "0000")
            throw new \Exception("API Response Code is " . $result['responseCode']);

        return $result;
    }
}