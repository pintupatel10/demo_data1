<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Grayline</title>
    <link rel="icon" href="{!! asset('favicon.ico') !!}"/>

    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <style>

        @font-face {
            font-family: 'noto-regular';
            font-style: normal;
            font-weight: normal;

            @if (\App::isLocale("zh-hk"))
            src: url({{ url('assets/font/NotoSansCJKtc-Regular.ttf') }}) format("truetype");
            @elseif (\App::isLocale("zh-cn"))
            src: url({{ url('assets/font/NotoSansCJKsc-Regular.ttf') }}) format("truetype");
            @endif
        }

        @font-face {
            font-family: 'noto-medium';
            font-style: normal;
            font-weight: normal;

            @if (\App::isLocale("zh-hk"))
            src: url({{ url('assets/font/NotoSansCJKtc-Medium.ttf') }}) format("truetype");
            @elseif (\App::isLocale("zh-cn"))
            src: url({{ url('assets/font/NotoSansCJKsc-Medium.ttf') }}) format("truetype");
            @endif
        }

        * {
            @if (\App::isLocale("en"))
            font-family: "Helvetica";
            @else
            font-family: "noto-regular";
            @endif
            font-size: 12px;
        }

        .bold {
            @if (\App::isLocale("en"))
            font-weight: 700;
            @else
            font-family: "noto-medium" !important;
            @endif
        }

        table {
            border-collapse: collapse;
        }

        #booking-table th
        {
            background-color:#183D6B;
            color:#ffffff;
            padding: 8px;


            @if (\App::isLocale("en"))
            font-weight: 700;
            @else
            font-family: "noto-medium" !important;
            font-weight: 400;
            @endif
    }

        #booking-table td
        {
            padding: 8px;
            border: 1px solid #ccc;
        }

        #code-table th
        {
            padding: 8px;
            border: 1px solid #ccc;


            @if (\App::isLocale("en"))
            font-weight: 700;
            @else
            font-family: "noto-medium" !important;
            font-weight: 400;
            @endif
        }

        #code-table td
        {
            padding: 8px;
            border: 1px solid #ccc;
        }

        ul li {
            margin-top: 4px;
        }

        #office li {
            list-style: none;
        }

        footer {
            position: fixed;
            bottom: 0px;
            right: 0px;
            height: 75px;
            width: 150px;
        }
    </style>
</head>

<body>
    <footer>
        <img src="{{ url('assets/logo.jpg') }}" width="150px" />
        <div style="margin-top: 10px; font-size: 10px;">{{ trans('cart.phone-title') }}: {{ trans('cart.phone') }}</div>
    </footer>

    <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
            <td width="50%">
                <img src="{{ url('turbojet-qrcode/' . $order_product->turbojet_reserve->{App\TurbojetReserve::COLUMN_TRANSACTION_ID}) }}" width="80px" height="80px" />
            </td>
            <td width="50%" align="right">
                <img src="{{ url('website/img/turbojet-logo.png') }}" width="200px" />
            </td>
        </tr>
    </table>

    <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
            <td>
                <div style="font-size: 18px; margin: 20px 0 40px 0;" class="bold">{{ trans('turbojet.title') }}</div>
            </td>
        </tr>

        <tr>
            <td>
                <table width="100%" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                        <td width="50%">{{ trans('turbojet.collector') }}: {{ $order_product->name }}</td>
                        <td width="50%">{{ trans('turbojet.agent-id') }}: {{ $config['login-id'] }}</td>
                    </tr>
                    <tr>
                        <td width="50%">{{ trans('turbojet.transaction-id') }}: {{ $order_product->turbojet_reserve->{App\TurbojetReserve::COLUMN_TRANSACTION_ID} }}</td>
                        <td width="50%">{{ trans('turbojet.membership-no') }}: {{ $config['member-id'] }}</td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr>
            <td>
                <div style="font-size: 16px; margin: 40px 0 10px 0;" class="bold">{{ trans('turbojet.booking-details') }}</div>
            </td>
        </tr>

        <tr>
            <td>
                <table id="booking-table" width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <th align="center" valign="center">{{ trans('turbojet.departure') }}</th>
                        <th align="center" valign="center">{{ trans('turbojet.route') }}</th>
                        <th align="center" valign="center">{{ trans('turbojet.class') }}</th>
                        <th align="center" valign="center">{{ trans('turbojet.reservation-no') }}</th>
                        <th align="center" valign="center">{{ trans('turbojet.promotion') }}</th>
                        <th align="center" valign="center">{{ trans('turbojet.payment') }}</th>
                    </tr>
                    @foreach ($order_product->turbojet_reserve->getReservationTable() as $reservation)
                        <tr>
                            <td>{{ $reservation['time'] }}</td>
                            <td>{{ $reservation['route'] }}</td>
                            <td>{{ trans('reserve.' . $reservation['class-code']) }}</td>
                            <td>{{ $reservation['reservation-no'] }}</td>
                            <td>{{ $reservation['promotion'] }}</td>
                            <td>{{ $reservation['type'] }}</td>
                        </tr>
                    @endforeach
                </table>
            </td>
        </tr>

        <tr>
            <td>
                <div style="font-size: 16px; margin: 40px 0 10px 0;" class="bold">{{ trans('turbojet.reminder-title') }}:</div>
                {!! trans('turbojet.reminder-body') !!}
            </td>
        </tr>

        <tr>
            <td>
                <div style="margin: 40px 0 10px 0;">{{ trans('turbojet.note-title') }}:</div>
                <ul id="note">
                    <li>{{ trans('turbojet.note-body-1') }}</li>
                    <li>{{ trans('turbojet.note-body-2') }}</li>
                    <li>{{ trans('turbojet.note-body-3') }}</li>
                    <li>{{ trans('turbojet.note-body-4') }}</li>
                    <li>{{ trans('turbojet.note-body-5') }}</li>
                </ul>
            </td>
        </tr>

        <tr>
            <td>
                <div style="margin: 40px 0 10px 0;">{{ trans('turbojet.office-title') }}</div>
                <ul id="office">
                    <li>{{ trans('turbojet.office-body-1') }}</li>
                    <li>{{ trans('turbojet.office-body-2') }}</li>
                    <li>{{ trans('turbojet.office-body-3') }}</li>
                    <li>{{ trans('turbojet.office-body-4') }}</li>
                    <li>{{ trans('turbojet.office-body-5') }}</li>
                    <li>{{ trans('turbojet.office-body-6') }}</li>
                    <li>{{ trans('turbojet.office-body-7') }}</li>
                </ul>
            </td>
        </tr>

        <tr>
            <td>
                <div style="margin: 40px 0 10px 0;">{{ trans('turbojet.enquiry') }}: (+852) 2859 3333</div>
                <table id="code-table" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <th>{{ trans('turbojet.route-abbr') }}</th>
                        <th>{{ trans('turbojet.route-description') }}</th>
                    </tr>
                    <tr>
                        <td>CLK</td>
                        <td>{{ trans('turbojet.route-CLK') }}</td>
                    </tr>
                    <tr>
                        <td>HKG</td>
                        <td>{{ trans('turbojet.route-HKG') }}</td>
                    </tr>
                    <tr>
                        <td>KLN</td>
                        <td>{{ trans('turbojet.route-KLN') }}</td>
                    </tr>
                    <tr>
                        <td>TFT</td>
                        <td>{{ trans('turbojet.route-TFT') }}</td>
                    </tr>
                    <tr>
                        <td>MAC</td>
                        <td>{{ trans('turbojet.route-MAC') }}</td>
                    </tr>
                    <tr>
                        <td>NSZ</td>
                        <td>{{ trans('turbojet.route-NSZ') }}</td>
                    </tr>
                    <tr>
                        <td>SZA</td>
                        <td>{{ trans('turbojet.route-SZA') }}</td>
                    </tr>
                    <tr>
                        <td>YFT</td>
                        <td>{{ trans('turbojet.route-YFT') }}</td>
                    </tr>
                    <tr>
                        <td>ZYK</td>
                        <td>{{ trans('turbojet.route-ZYK') }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>