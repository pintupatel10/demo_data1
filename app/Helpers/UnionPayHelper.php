<?php
/**
 * Created by PhpStorm.
 * User: yeelok
 * Date: 10/3/2017
 * Time: 00:38
 */

namespace App\Helpers;

use Config;
use GuzzleHttp\Client;

class UnionPayHelper
{
    public static function getForm($amount)
    {
        $config = Config::get('core.union-pay.' . env('UNIONPAY_ENV', 'dev'));

        $order_id = Toolkit::getUniqueTimestamp();
        $amount = $amount * 100;

        $pkcs12certdata = file_get_contents($config['private-cert']);
        openssl_pkcs12_read($pkcs12certdata, $certs, $config['private-cert-password']);
        $x509data = $certs['cert'];
        $private_key = $certs['pkey'];
        openssl_x509_read($x509data);
        $cert = openssl_x509_parse($x509data);

        $transaction_data = array(
            'txnType' => '01', // Purchase 購買
            'txnSubType' => '01', // Purchase
            'bizType' => '000201', // UnionPay Hosted Method
            'channelType' => '07', // internet, always for this SDK
            'accessType' => '0',
            'orderId' => $order_id,
            'txnTime' => date('YmdHis'),
            'txnAmt' => $amount,
            'currencyCode' => 344,   // HKD
            'signMethod' => "01",
            'version' => "5.0.0",
            'encoding' => "UTF-8",
            'certId' => $cert['serialNumber'],
            'merId' => $config['merchant-id'],
            'frontUrl' => url("notify?type=unionpay"),
        );

        $hashed = self::getHashedParamStr($transaction_data);
        openssl_sign($hashed, $signature, $private_key, OPENSSL_ALGO_SHA1 );
        $transaction_data['signature'] = base64_encode($signature);

        return [
            'data' => $transaction_data,
            'url' => $config['url'],
        ];
    }

    public static function verify($data)
    {
        $config = Config::get('core.union-pay.' . env('UNIONPAY_ENV', 'dev'));

        $hashed = self::getHashedParamStr($data);
        $public_key = file_get_contents($config['public-cert']);

        // Verify
        $signature = base64_decode($data['signature']);
        $verify = openssl_verify($hashed, $signature, $public_key, OPENSSL_ALGO_SHA1);

        return $verify == 1;
    }

    public static function query($order_id)
    {
        $config = Config::get('core.union-pay.' . env('UNIONPAY_ENV', 'dev'));

        $pkcs12certdata = file_get_contents($config['private-cert']);
        openssl_pkcs12_read($pkcs12certdata, $certs, $config['private-cert-password']);
        $x509data = $certs['cert'];
        $private_key = $certs['pkey'];
        openssl_x509_read($x509data);
        $cert = openssl_x509_parse($x509data);

        $transaction_data = array(
            'txnType' => '00', // Purchase 購買
            'txnSubType' => '00', // Purchase
            'bizType' => '000000', // UnionPay Hosted Method
            'channelType' => '07', // internet, always for this SDK
            'accessType' => '0',
            'txnTime' => date('YmdHis'),
            'orderId' => $order_id,
            'signMethod' => "01",
            'version' => "5.0.0",
            'encoding' => "UTF-8",
            'certId' => $cert['serialNumber'],
            'merId' => $config['merchant-id'],
        );

        $hashed = self::getHashedParamStr($transaction_data);
        openssl_sign($hashed, $signature, $private_key, OPENSSL_ALGO_SHA1 );
        $transaction_data['signature'] = base64_encode($signature);

        $client = new Client();
        $client->setDefaultOption('verify', false);

        $response = $client->post("https://101.231.204.80:5000/gateway/api/queryTrans.do", [
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded',
            ],
            'timeout' => 10,
            'body' => $transaction_data
        ]);

        parse_str($response->getBody()->getContents(), $body);
        var_dump($body);

        dd($response);
    }

    private static function getHashedParamStr($params)
    {
        $sign_str = '';
        ksort ( $params );
        foreach ( $params as $key => $val ) {
            if ($key == 'signature') {
                continue;
            }
            $sign_str .= sprintf ( "%s=%s&", $key, $val );
        }
        $sign_str = substr ( $sign_str, 0, strlen ( $sign_str ) - 1 );

        return sha1( $sign_str, FALSE );
    }
}