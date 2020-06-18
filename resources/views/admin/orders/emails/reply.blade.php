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

                        <div style="padding:10px 0px 10px 20px">
                            <p>{{ trans('email.greeting') }}, {{ $order->products[0]->name }}</p>
                            <div>
                                {!! nl2br($order_message->{\App\OrderMessage::COLUMN_MESSAGE}) !!}
                            </div>
                        </div>

                        <div style="font-size: 14px; padding:10px 20px;">
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
