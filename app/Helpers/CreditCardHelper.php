<?php
/**
 * Created by PhpStorm.
 * User: yeelok
 * Date: 9/3/2017
 * Time: 23:40
 */

namespace App\Helpers;

use Carbon\Carbon;
use GuzzleHttp\Client;
use Config;


class CreditCardHelper
{
    const VISA = "visa";
    const MASTERCARD = "mastercard";

    public static function getForm($type, $amount)
    {
        $config = Config::get('core.wirecard.' . env('WIRECARD_ENV', 'dev'));

        $data = [
            'request_time_stamp' => Carbon::now()->tz('UTC')->format('YmdHis'),
            'request_id' => Toolkit::getUniqueTimestamp(),
            'merchant_account_id' => $config['merchant-id'],
            'transaction_type' => 'purchase',
            'requested_amount' => $amount,
            'requested_amount_currency' => 'HKD',
            'redirect_url' => url("notify?type=$type"),
            'ip_address' => \Request::ip(),
        ];

        $param_str = '';
        foreach ($data as $key => $value)
        {
            $param_str .= $value;
        }

        $data['attempt_three_d'] = 'true';
        $data['request_signature'] = hash('sha256', $param_str . $config['secret-key']);

        return [
            'data' => $data,
            'url' => $config['url']
        ];
    }

    public static function verify($data)
    {
        $config = Config::get('core.wirecard.' . env('WIRECARD_ENV', 'dev'));

        $fields = [
            "merchant_account_id",
            "transaction_id",
            "request_id",
            "transaction_type",
            "transaction_state",
            "completion_time_stamp",
            "token_id",
            "masked_account_number",
            "ip_address",
            "authorization_code"
        ];

        $param_str = '';
        foreach ($fields as $field)
        {
            $param_str .= $data[$field];
        }

        $signature = hash('sha256', $param_str . $config['secret-key']);
        return $signature == $data['response_signature'];
    }
}