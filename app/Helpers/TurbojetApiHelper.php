<?php

namespace App\Helpers;
use Config;

class TurbojetApiHelper
{
    public static function getVoyage($route, $flight_no, $time, $seat_class, $premier, $quantity)
    {
        $config = Config::get('core.turbojet.' . env('TURBOJET_ENV', 'dev'));

        $client = new \SoapClient($config['url']);
        $response = $client->getVoyage([
            'route' => $route,
            'flight_no' => $flight_no,
            'departure_date_time' => $time,
            'seat_class' => $seat_class,
            'no_of_ticket' => $quantity,
            'premier' => $premier,
            'login_id' => $config['login-id'],
            'password' => $config['password'],
            'member_id' => $config['member-id'],
        ]);

        if (is_array($response->getVoyageResult->voyage))
            return $response->getVoyageResult->voyage;
        else
            return [];
    }

    public static function doPayment($collector, $booking, $promo_code = '')
    {
        $config = Config::get('core.turbojet.' . env('TURBOJET_ENV', 'dev'));

        $client = new \SoapClient($config['url']);
        $response = $client->doPayment([
            'booking' => $booking,
            'collector' => $collector,
            'PNR' => '',
            'login_id' => $config['login-id'],
            'password' => $config['password'],
            'member_id' => $config['member-id'],
        ]);

        $result = $response->doPaymentResult;
        if ($result->return_code != 1)
            throw new \Exception("API Response Code is " . $result->return_code);

        return $result;
    }

    public static function getTransactionDetail()
    {
        $config = Config::get('core.turbojet.' . env('TURBOJET_ENV', 'dev'));

        $client = new \SoapClient($config['url']);
        $response = $client->getTransactionDetail([
            'trans_id' => 'WEB4003286806',
            'query_date' => '',
            'login_id' => $config['login-id'],
            'password' => $config['password'],
            'member_id' => $config['member-id'],
        ]);

        dd($response);
    }

}