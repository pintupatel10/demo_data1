<!doctype html>
<html>
<head>
    @include('website.header')
    <title>Grayline</title>
    <link rel="icon" href="{!! asset('favicon.ico') !!}"/>
    <style>
        #addrow, #addrow1 , #addrow2
        {
            cursor: pointer;
        }

        .cart-section-heading {
            margin-top: 80px;
            padding-bottom: 10px;
            color: #333;
            border-bottom: 1px solid #D1D1D1;
            font-size: 16px;
            margin-bottom: 20px;
        }

        #terms {
            height: 200px;
            overflow: scroll;
            border: 1px solid #d4d4d4;
            padding: 10px;
        }

        #logos {
            display: flex;
            justify-content: flex-end;
            margin-top: 20px;
        }

        #logos > div {
            margin-left: 20px;
        }

        #dbs-message {
            font-size: 11px;
            text-align: right;
            padding-top: 5px;
        }
    </style>
</head>

<body>
@include('website.menu')

<div class="col-md-10 col-lg-10 col-sm-10 padding-0 xs-s-class">
    <div class="pd0 col-xs-offset-1 col-sm-offset-1 col-md-offset-1 col-md-10 col-lg-10 col-sm-10 col-xs-10">
        <div class="shortdiv">
            <div class="str_line">
                <div class="shopping_cart">
                    <span class="circle cart">
                        <i class="fa fa-shopping-cart"></i>
                    </span>
                    <p>{{ trans('cart.shopping-cart') }}</p>
                </div>
                <div class="shopping_payment ">
                    <span class="circle card">
                        <i class="fa fa-credit-card"></i>
                    </span>
                    <p>{{ trans('cart.payment') }}</p>
                </div>
                <div class="shopping_done">
                    <span class="circle check">
                        <i class="fa fa-check"></i>
                    </span>
                    <p>{{ trans('cart.done') }}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="pd0 col-xs-offset-1 col-sm-offset-1 col-md-offset-1 col-md-10 col-lg-10 col-sm-10 col-xs-10">

        @if (sizeof($carts) != 0)
            <div>
                <ul class="ul_body">
                    <li class="first_div" style="border-bottom:1px solid #D1D1D1; padding-top: 20px">
                        <div class="img_div name_title">{{ trans('cart.product') }}</div>
                        <div class="content_div"><span class="price name_title" style="padding-right: 18px;">{{ trans('cart.price') }}</span></div>
                        <span class="dlt_icon"></span>
                    </li>

                    @foreach ($carts as $row)
                        <li class="first_div list1">
                            <div class="img_div">
                                <img src="{{url($row->options['product-image'])}}" class="img-responsive" />
                            </div>
                            <div class="content_div">
                                <div class="price1">
                                    <p class="td_head">{{ $row->name }}</p>

                                    @if ($row->options['type'] != 'transportation' || !$row->options['turbojet'])
                                    <p class="td_p">{{ $row->options['type'] == 'tour' ? trans('reserve.tour-date') : trans('reserve.travel-date') }}: {{ $row->options['date'] }} </p>
                                    @endif

                                    @foreach ($row->options['price-data']['breakdown'] as $price_id => $breakdown)
                                        @if ($breakdown['quantity'] > 0)
                                            <p class="td_p">
                                                {{ $breakdown['title'] }}: {{ $breakdown['quantity'] }} x
                                                @if ($breakdown['original'] == $breakdown['new'])
                                                    HKD {{ $breakdown['original'] }}
                                                @else
                                                    <span style="text-decoration: line-through; opacity: 0.5;">HKD {{ $breakdown['original'] }}</span>
                                                    HKD {{ $breakdown['new'] }}
                                                @endif
                                            </p>

                                            @if ($row->options['type'] == 'transportation' && $row->options['turbojet'])
                                                <p class="td_p">{{ trans('reserve.travel-date') }}: {{ $breakdown['flight-info']['time'] }}</p>
                                            @endif

                                            @if ($row->options->offsetExists('departure-datetime'))
                                                <p class="td_p">{{ trans('reserve.departure') }}: {{ $row->options['departure-datetime']->format('Y-m-d H:i') }}</p>
                                            @endif
                                            @if ($row->options->offsetExists('return-datetime'))
                                                <p class="td_p">{{ trans('reserve.return') }}: {{ $row->options['return-datetime']->format('Y-m-d H:i') }}</p>
                                            @endif


                                        @endif
                                    @endforeach

                                    @if ($row->options->offsetExists('promocode'))
                                        <p class="td_p">{{ trans('reserve.promo-code') }}: {{ $row->options['promocode'] }} </p>
                                    @endif

                                    @if ($row->options->offsetExists('service-charge') && $row->options['service-charge'] > 0)
                                        <p class="td_p">{{ trans('reserve.service-charge') }}: {{ $row->options['price-data']['total-quantity'] }} x HKD {{ $row->options['service-charge'] }} </p>
                                    @endif
                                </div>
                                <span class="price font_res">
                                    HKD {{ $row->subtotal() }}
                                </span>

                            </div>
                            <span class="dlt_icon">
                            <a href="{{url('cart/remove/'.$row->rowId)}}"><i class="fa fa-trash-o" id="" aria-hidden="true" style="color:#D8D8D8;"></i></a>
                        </span>
                        </li>
                    @endforeach

                    <li class="first_div lis4" style="border-top:1px solid #D1D1D1; padding: 10px 0;">
                        <div class="img_div name_title"></div>
                        <div class="content_div"><p class='p_foot'>{{ trans('cart.total-amount') }}:</p><p class='total_price' style="display: inline; float:right;">HKD {{ $total }}</p></div>
                    </li>
                </ul>
            </div>

            <div class="cart-section-heading">{{ trans('cart.terms') }}</div>
            <div id="terms">
                {!! $terms !!}
            </div>
            <div class="text-right">
                <div class="checkbox">
                    <label>
                        <input id="cb-terms" type="checkbox" style="margin: 4px 0 0 -20px; opacity: 1;"> {{ trans('cart.accept-terms') }}
                    </label>
                </div>
                <button id="btn-checkout" class="btn checkout_btn">{{ trans('cart.checkout') }}</button>
            </div>

            <div id="logos">
                <div style="padding-top: 5px;"><img src="{{ url('website/img/visa-logo.svg') }}" height="25px"/></div>
                <div><img src="{{ url('website/img/visa-verified.jpg') }}" height="30px"/></div>
                <div><img src="{{ url('website/img/mastercard-logo-new.svg') }}" height="30px"/></div>
                <div><img src="{{ url('website/img/mastercard-sc.svg') }}" height="30px"/></div>
                <div><img src="{{ url('website/img/union-pay-logo.svg') }}" height="30px"/></div>
            </div>

            <div class="cart-section-heading">{{ trans('cart.contact-us') }}</div>
            <div>
                <dl class="dl-horizontal">
                    <dt>{{ trans('cart.address-title') }}</dt>
                    <dd>{{ trans('cart.address') }}</dd>
                    <dt>{{ trans('cart.email-title') }}</dt>
                    <dd>{{ trans('cart.email') }}</dd>
                    <dt>{{ trans('cart.phone-title') }}</dt>
                    <dd>{{ trans('cart.phone') }}</dd>
                </dl>
            </div>

        @else
            <div class="alert alert-danger" style="margin-top: 100px;">
                @if($cookie == 'English')
                    Your shopping cart is empty.
                @elseif($cookie == '繁中')
                    你的購物車是空的。
                @elseif($cookie == '簡')
                    你的购物车是空的。
                @endif
            </div>
        @endif
    </div>
 </div>
    @include('website.footer')


    <script type="text/javascript">
        $(function (){
            $("#btn-checkout").prop("disabled", true);

            $("#cb-terms").change(function (){
                $("#btn-checkout").prop("disabled", !$(this).is(":checked"));
            });

            $("#btn-checkout").click(function (){
                if ($("#cb-terms").is(":checked"))
                {
                    window.location.href = '{{url('checkout')}}';
                }
            });
        });
    </script>
</body>
</html>