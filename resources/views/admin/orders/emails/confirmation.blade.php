<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Grayline</title>
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

                        <div style="padding:10px 20px">
                            <p>{{ trans('email.greeting') }}, {{ $order->products[0]->name }}</p>
                            <p>{{ trans('email.confirm-intro') }}</p>
                        </div>

                        <div style="padding:10px 20px; background-color:#183D6B; color:#ffffff; font-size:16px; font-family: PingFangHK-SemiBold, sans-serif;">{{ trans('email.order-info') }}</div>
                        <div style="padding:10px 20px;">
                            <table>
                                <tr>
                                    <td style="width:50%">{{ trans('email.confirmation-no') }}:</td>
                                    <td>{{ $confirmation->{\App\OrderConfirmation::COLUMN_ID} }}</td>
                                </tr>
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
                                    <td style="width:50%">{{ trans('email.handled-by') }}:</td>
                                    <td>{{ \Auth::user()->name }}</td>
                                </tr>
                                <tr>
                                    <td height="15"></td>
                                </tr>
                            </table>
                        </div>

                        <div style="padding:10px 20px; background-color:#183D6B; color:#ffffff; font-size:16px; font-family: PingFangHK-SemiBold, sans-serif;">{{ trans('email.guest-info') }}</div>
                        <div style="padding:10px 20px;">
                            <table>
                                @foreach ($order->getDistinctCustomersForProducts($order_products) as $customer)
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
                                        <th style="width:80%" align="left">{{ trans('email.product') }}</th>
                                        <th style="width:20%" align="right">{{ trans('email.price') }}</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>

                        <?php $total=0; ?>
                        @foreach($order_products as $order_product)
                            <?php $total += $order_product->{App\OrderProduct::COLUMN_TOTAL_AMOUNT}; ?>
                            <div style="padding:10px 20px;">
                                <span style="font-size:18px;color:#183D6B;"><strong>{{ $order_product->{App\OrderProduct::COLUMN_PRODUCT_PRICE_GROUP_TITLE} }}</strong></span>
                                <table width="100%">
                                    <tr>
                                        <td colspan="2" height="5px"></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">{{ $order_product->{App\OrderProduct::COLUMN_TYPE} == "tour" ? trans('reserve.tour-date') : trans('reserve.travel-date') }}: {{ $order_product->{App\OrderProduct::COLUMN_DATE}->format('Y-m-d') }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">{{ trans('email.voucher-no') }}: {{ $order_product->{App\OrderProduct::COLUMN_VOUCHER_NO} }}</td>
                                    </tr>
                                    <tr>
                                        <td width="80%">
                                            @foreach ($order_product->packages as $package)
                                                {{ $package->{App\OrderProductPackage::COLUMN_TITLE} }}: {{ $package->{App\OrderProductPackage::COLUMN_QUANTITY} }}<br />
                                            @endforeach
                                        </td>
                                        <td width="20%"><strong>HKD {{ number_format($order_product->{App\OrderProduct::COLUMN_TOTAL_AMOUNT}, 0) }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" height="10px"></td>
                                    </tr>
                                </table>
                            </div>
                            <div>{!! $order_product->{\App\OrderProduct::COLUMN_CONFIRMATION} !!}</div>
                        @endforeach
                        <div style="padding:10px 20px;">
                            <div style="text-align: center; padding: 10px;">
                                @if (App::getLocale() == "en")
                                {!! sprintf(trans('email.confirm-total'), "HKD " . number_format($total, 0), $order_product->{App\OrderProduct::CREATED_AT}->format('Y-m-d')) !!}
                                @else
                                {!! sprintf(trans('email.confirm-total'), $order_product->{App\OrderProduct::CREATED_AT}->format('Y-m-d'), "HKD " . number_format($total, 0)) !!}
                                @endif
                            </div>

                            <hr style="border: 0; width: 100%; color: #183D6B; background-color: #183D6B;	height: 4px;">
                            <div style="font-size: 14px;">
                                <p>{{ trans('email.footer-hour') }}</p>
                                {!! trans('email.footer-contact')  !!}
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
