<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Grayline</title>
    <link rel="icon" href="{!! asset('favicon.ico') !!}"/>

    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
</head>

<body style="color: #000000; font-size: 16px; font-weight: 400; font-family: PingFangHK, sans-serif; margin: 0;">

<table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%">
    <tr>
        <td height="20"></td>
    </tr>
    <tr>
        <td align="center" valign="top">
            <table cellpadding="0" cellspacing="0" width="700px" style="border:thin;border-color:#183D6B; border-style:solid;">
                <tr>
                    <td height="15"></td>
                </tr>
                <tr>
                    <td>
                        <center>
                            <img src="{{ URL::asset('assets/logo.jpg') }}"  width="250px">
                        </center>
                        <hr style="border: 0; width: 40%; color: #183D6B; background-color: #183D6B;	height: 2px;">

                        <div style="padding:10px 0px 10px 20px">
                            <p>{{ trans('email.greeting') }}, {{ $order->products[0]->name }}</p>
                            <p>{{ trans('email.thank-you') }}</p>
                        </div>

                        <div style="padding:10px 20px; background-color:#183D6B; color:#ffffff; font-size:16px; font-family: PingFangHK-SemiBold, sans-serif;">{{ trans('email.order-info') }}</div>
                        <div style="padding:10px 20px;">
                            <table>
                                <tr>
                                    <td style="width:50%">{{ trans('email.order-no') }}:</td>
                                    <td>{{ $order->products[0]->{App\OrderProduct::COLUMN_ORDER_ID} }}</td>
                                </tr>
                                <tr>
                                    <td style="width:50%">{{ trans('email.order-date') }}:</td>
                                    <td>{{ $order->products[0]->{App\OrderProduct::CREATED_AT} }}</td>
                                </tr>
                                <tr>
                                    <td style="width:50%">{{ trans('email.payment-status') }}:</td>
                                    <td>{{ trans('email.paid') }}</td>
                                </tr>
                                <tr>
                                    <td style="width:50%">{{ trans('email.paid-by') }}:</td>
                                    <td>{{ App\Order::$payment_methods[$order->{App\Order::COLUMN_PAYMENT_METHOD}] }} {{ $order->{App\Order::COLUMN_ACCOUNT_NO} }}</td>
                                </tr>
                                <tr>
                                    <td style="width:50%">{{ trans('email.merchant-id') }}:</td>
                                    <td>
                                        @if ($order->{App\Order::COLUMN_PAYMENT_METHOD} == App\Order::PAYMENT_METHOD_UNIONPAY)
                                            84833011
                                        @else
                                            28703098
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width:50%">{{ trans('email.merchant-name') }}:</td>
                                    <td>Gray Line Tours – Online</td>
                                </tr>
                                @if ($order->{App\Order::COLUMN_PAYMENT_METHOD} == App\Order::PAYMENT_METHOD_UNIONPAY)
                                    <tr>
                                        <td style="width:50%">{{ trans('email.acquirer-name') }}:</td>
                                        <td>DBS Bank (Hong Kong) Limited</td>
                                    </tr>
                                    <tr>
                                        <td style="width:50%">{{ trans('email.response-code') }}:</td>
                                        <td>Approve</td>
                                    </tr>
                                    <tr>
                                        <td style="width:50%">{{ trans('email.delivery-status') }}:</td>
                                        <td>In Progress</td>
                                    </tr>
                                @else
                                    <tr>
                                        <td style="width:50%">{{ trans('email.transaction-type') }}:</td>
                                        <td>Purchase</td>
                                    </tr>
                                    <tr>
                                        <td style="width:50%">{{ trans('email.authorization-code') }}:</td>
                                        <td>{{ $order->payment_history->{\App\PaymentHistory::COLUMN_AUTHORIZATION_CODE} }}</td>
                                    </tr>
                                @endif
                                <tr>
                                    <td height="15"></td>
                                </tr>
                            </table>
                        </div>

                        <div style="padding:10px 20px; background-color:#183D6B; color:#ffffff; font-size:16px; font-family: PingFangHK-SemiBold, sans-serif;">{{ trans('email.guest-info') }}</div>
                        <div style="padding:10px 20px;">
                            <table>
                                @foreach ($order->getDistinctCustomers() as $customer)
                                <tr>
                                    <td style="width:50%">{{ trans('email.name') }} :</td>
                                    <td>{{ $customer->name }}</td>
                                </tr>
                                <tr>
                                    <td style="width:50%">{{ trans('email.nationality') }} :</td>
                                    <td>{{ $customer->nationality->name }}</td>
                                </tr>
                                <tr>
                                    <td style="width:50%">{{ trans('reserve.email') }}:</td>
                                    <td>{{ $customer->{App\OrderProduct::COLUMN_EMAIL} }}</td>
                                </tr>
                                @if (strlen($customer->{App\OrderProduct::COLUMN_TEL}) > 0)
                                <tr>
                                    <td>{{ trans('email.contact-no') }}:</td>
                                    <td>{{ $customer->{App\OrderProduct::COLUMN_COUNTRY_CODE} . ' ' . $customer->{App\OrderProduct::COLUMN_TEL} }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <td height="15"></td>
                                </tr>
                                @endforeach
                            </table>
                        </div>

                        <div style="padding:10px 20px; background-color:#183D6B; color:#ffffff; font-size:16px; font-family: PingFangHK-SemiBold, sans-serif;">{{ trans('email.order-summary') }}</div>
                        <div style="padding:10px 20px;">
                            <table width="100%">
                                <thead>
                                    <tr>
                                        <th style="width:70%" align="left">{{ trans('email.product') }}</th>
                                        <th style="width:30%" align="right">{{ trans('email.price') }}</th>
                                    </tr>
                                </thead>

                                <tbody>
                                <?php $total=0; ?>
                                @foreach($order->products as $order_product)
                                    <tr>
                                        <td valign="top"><br><span style="font-size:18px;color:#183D6B;"><strong>{{ $order_product->{App\OrderProduct::COLUMN_PRODUCT_PRICE_GROUP_TITLE} }}</strong></span><br></td>
                                        <td valign="top" align="right"></td>
                                    </tr>
                                    <tr>
                                        <td style="font-size:14px;">
                                            {{ $order_product->{App\OrderProduct::COLUMN_TYPE} == "tour" ? trans('reserve.tour-date') : trans('reserve.travel-date') }}: {{ $order_product->{App\OrderProduct::COLUMN_DATE}->format('Y-m-d') }}<br />
                                            @foreach ($order_product->packages as $package)
                                                {{ $package->{App\OrderProductPackage::COLUMN_TITLE} }}: {{ $package->{App\OrderProductPackage::COLUMN_QUANTITY} }} x
                                                @if ($package->{App\OrderProductPackage::COLUMN_UNIT_DISCOUNT} == 0)
                                                    HKD {{ number_format($package->{App\OrderProductPackage::COLUMN_UNIT_FINAL_PRICE}, 0) }}
                                                @else
                                                    <span style="text-decoration: line-through; opacity: 0.5;">HKD {{ number_format($package->{App\OrderProductPackage::COLUMN_UNIT_ORIGINAL_PRICE}, 0) }}</span>
                                                    HKD {{ number_format($package->{App\OrderProductPackage::COLUMN_UNIT_FINAL_PRICE}, 0) }}
                                                @endif
                                                <br />
                                            @endforeach

                                            @if ($order_product->{App\OrderProduct::COLUMN_PROMOCODE})
                                                {{ trans('reserve.promo-code') }}: {{ $order_product->{App\OrderProduct::COLUMN_PROMOCODE} }}<br />
                                            @endif

                                            @if ($order_product->{App\OrderProduct::COLUMN_UNIT_SERVICE_CHARGE} > 0)
                                                {{ trans('reserve.service-charge') }}: {{ $order_product->{App\OrderProduct::COLUMN_PACKAGE_QUANTITY} }} x HKD {{ number_format($order_product->{App\OrderProduct::COLUMN_UNIT_SERVICE_CHARGE}, 0) }}<br />
                                            @endif
                                        </td>
                                        <td valign="bottom" align="right"></td>
                                    </tr>
                                    <tr>
                                        <td style="font-size:16px;" height="20" valign="bottom" align="right">{{ trans('reserve.sub-total') }}:</td>
                                        <td height="20" valign="bottom" align="right"><strong>HKD {{ number_format($order_product->{App\OrderProduct::COLUMN_TOTAL_AMOUNT}, 0) }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td height="10"></td>
                                        <td></td>
                                    </tr>
                                    <?php $total += $order_product->{App\OrderProduct::COLUMN_TOTAL_AMOUNT}; ?>
                                @endforeach
                                </tbody>

                            </table>
                            <hr style="border: 0; width: 100%; color: #183D6B; background-color: #183D6B;	height: 1px;">
                            <div class="total" align="right">
                                {{ trans('email.grand-total') }}: <span style="font-size:20px;font-family: PingFangHK-SemiBold, sans-serif;color:#183D6B">HKD {{ number_format($total, 0) }}</span>
                            </div>

                            <hr style="border: 0; width: 100%; color: #183D6B; background-color: #183D6B;	height: 4px;">
                            <div style="font-size: 14px;">
                                <p>{{ trans('email.footer') }}{{ trans('email.footer-hour') }}</p>

                                <div>
                                    <table width="100%" border="0" cellpadding="0" cellspacing="0">
                                        <tr><td height="30px"></td></tr>
                                        <tr>
                                            <td width="100%" align="center">
                                                <a href="{{ url('') }}" style="text-decoration: none; width: 200px; height: 40px; background: #183D6B; color: white; display: block; line-height: 40px;">
                                                    {{ trans('email.contact-us') }}
                                                </a>
                                            </td>
                                        </tr>
                                        <tr><td height="40px"></td></tr>
                                    </table>
                                </div>


                                {!! trans('email.footer-contact')  !!}
                                <br >
                                <?php $address = App\Contactus::where('language', trans('email.cookie-language'))->first(); ?>
                                @if ($address)
                                    {{ trans('email.footer-address') . $address->address_map }}
                                @endif
                                <p style="font-size: 12px; margin-top: 10px;">
                                    {!! sprintf(trans('email.footer-refund'), url('terms') . '/' . App::getLocale()) !!}
                                </p>
                            </div>
                        </div>

                    </td>
                </tr>
            </table></td>
    </tr>
    <tr>
        <td height="20"></td>
    </tr>
</table>

</body>
</html>
