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

                        <div style="padding:10px 20px; background-color:#183D6B; color:#ffffff; font-size:16px; font-family: PingFangHK-SemiBold, sans-serif; margin-top: 20px;">PAYMENT FAILED RECORD</div>
                        <div style="padding:10px 20px;">
                            <table style="width:100%">
                                <tr>
                                    <td style="width:50%">Type:</td>
                                    <td>{{ $payment_history->{\App\PaymentHistory::COLUMN_TYPE} }}</td>
                                </tr>
                                <tr>
                                    <td style="width:50%">Is Success:</td>
                                    <td>@if ($payment_history->{App\PaymentHistory::COLUMN_IS_SUCCESS}) Yes @else No @endif</td>
                                </tr>
                                <tr>
                                    <td style="width:50%">Is Valid:</td>
                                    <td>@if ($payment_history->{App\PaymentHistory::COLUMN_IS_VALID}) Yes @else No @endif</td>
                                </tr>
                                <tr>
                                    <td style="width:50%">Is Amount Match:</td>
                                    <td>@if ($payment_history->{App\PaymentHistory::COLUMN_IS_AMOUNT_MATCH}) Yes @else No @endif</td>
                                </tr>
                                <tr>
                                    <td style="width:50%">Transaction ID:</td>
                                    <td>{{ $payment_history->{\App\PaymentHistory::COLUMN_TRANSACTION_ID} }}</td>
                                </tr>
                                <tr>
                                    <td style="width:50%">Account No.:</td>
                                    <td>{{ $payment_history->{\App\PaymentHistory::COLUMN_ACCOUNT_NO} }}</td>
                                </tr>
                                <tr>
                                    <td style="width:50%">Requested Amount.:</td>
                                    <td>{{ $payment_history->{\App\PaymentHistory::COLUMN_REQUESTED_AMOUNT} }}</td>
                                </tr>
                                <tr>
                                    <td style="width:50%">Settled Amount.:</td>
                                    <td>{{ $payment_history->{\App\PaymentHistory::COLUMN_SETTLED_AMOUNT} }}</td>
                                </tr>
                                <tr>
                                    <td style="width:50%">Authorization Code:</td>
                                    <td>{{ $payment_history->{\App\PaymentHistory::COLUMN_AUTHORIZATION_CODE} }}</td>
                                </tr>
                                <tr>
                                    <td style="width:50%">Time:</td>
                                    <td>{{ $payment_history->{\App\PaymentHistory::CREATED_AT} }}</td>
                                </tr>
                            </table>
                        </div>

                        <div style="padding:10px 20px; background-color:#183D6B; color:#ffffff; font-size:16px; font-family: PingFangHK-SemiBold, sans-serif; margin-top: 20px;">PAYMENT RESPONSE</div>
                        <div style="padding:10px 20px; font-size: 12px;">
                            {!! nl2br(str_replace(' ', '&nbsp;', json_encode(json_decode($payment_history->{App\PaymentHistory::COLUMN_RESPONSE_DATA}), JSON_PRETTY_PRINT))) !!}
                        </div>


                        <div style="padding:10px 20px; background-color:#183D6B; color:#ffffff; font-size:16px; font-family: PingFangHK-SemiBold, sans-serif; margin-top: 20px;">SHOPPING CART</div>
                        <div style="padding:10px 20px; font-size: 12px;">
                            {!! nl2br(str_replace(' ', '&nbsp;', json_encode(json_decode($payment_history->{App\PaymentHistory::COLUMN_CART_DATA}), JSON_PRETTY_PRINT))) !!}
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
