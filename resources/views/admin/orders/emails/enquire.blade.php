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

                        <div style="padding:10px 20px; background-color:#183D6B; color:#ffffff; font-size:16px; font-family: PingFangHK-SemiBold, sans-serif; margin-top: 20px;">ENQUIRE FROM GUEST</div>
                        <div style="padding:10px 20px;">
                            <table>
                                <tr>
                                    <td style="width:50%">Order ID:</td>
                                    <td>{{ $order_message->{\App\OrderMessage::COLUMN_ORDER_ID} }}</td>
                                </tr>
                                <tr>
                                    <td style="width:50%">Message:</td>
                                    <td>{!! nl2br($order_message->{\App\OrderMessage::COLUMN_MESSAGE}) !!}</td>
                                </tr>
                                <tr>
                                    <td height="15"></td>
                                </tr>
                            </table>
                        </div>

                        <div>
                            <table width="100%" border="0" cellpadding="0" cellspacing="0">
                                <tr><td height="30px"></td></tr>
                                <tr>
                                    <td width="100%" align="center">
                                        <a href="{{ url("admin/order/$order_message->order_id") }}" style="text-decoration: none; width: 200px; height: 40px; background: #183D6B; color: white; display: block; line-height: 40px;">
                                            Open Order
                                        </a>
                                    </td>
                                </tr>
                                <tr><td height="40px"></td></tr>
                            </table>
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
