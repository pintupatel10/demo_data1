<!doctype html>
<html>
<head>
@include('website.header')
    <title>Grayline</title>
    <link rel="icon" href="{!! asset('favicon.ico') !!}"/>
    <style>
        .str_line::after {
            border-color: #173C6B;
        }
        .circle.card, .circle.card > i {
            border-color: #173C6B;
            color: #173C6B
        }
        .shopping_payment > p
        {
            color:#173C6B;
        }

        #payment-method-container {
            max-width: 550px;
            margin: 0 auto;
            display: flex;
        }

        #payment-method-container > div {
            flex: 1;
            text-align: center;
        }

        #payment-method-container img:hover {
            cursor: pointer;
        }

        @media only screen and (max-width: 560px) {
            #payment-method-container {
                display: block;
            }

            #payment-method-container > div {
                margin-bottom: 40px;
            }
        }

        #payment-redirecting {
            text-align: center;
        }
    </style>
</head>
<body>
@include('website.menu')
<div class="col-md-10 col-lg-10 col-sm-10 padding-0 xs-s-class shopping_cart_next">

    <div class="col-xs-offset-1 col-sm-offset-1 col-md-offset-1 col-md-10 col-lg-10 col-sm-10 col-xs-10 pd0">
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
        <div class="mg-top shortdiv">
            @if ($errors->has('error'))
                <div class="alert alert-danger">
                    <strong>Whoops!</strong> There were some problems.<br><br>
                    {{ $errors->first('error') }}
                </div>
            @endif

            <div class="row">
                <div class="col-xs-6"><p class="blue-color">{{ trans('cart.payment-method') }}</p></div>
                <div class="col-xs-6 text-right">HKD ${{ $total }}</div>
            </div>
            <hr class="grey-bg-hr" style="padding-top:0; margin:0">
            {!! Form::open(['url' => url('checkout'),'class' => 'form-horizontal','files'=>true]) !!}
                <div class="form-group group1">
                    <div id="payment-method-container">
                        <div style="padding-top: 5px;"><img src="{{ url('website/img/visa-logo.svg') }}" height="45px" data-type="visa"/></div>
                        <div><img src="{{ url('website/img/mastercard-logo-new.svg') }}" height="50px" data-type="mastercard"/></div>
                        <div><img src="{{ url('website/img/union-pay-logo.svg') }}" height="50px" data-type="unionpay"/></div>
                    </div>

                    <div id="payment-redirecting" style="display: none">
                        <i class="fa fa-spinner fa-spin" style="font-size: 36px"></i>
                        <div style="margin-top: 10px">{{ trans('cart.payment-redirect') }}</div>
                    </div>
                </div>
                <div class="form-group group1 group2">
                    <div class="col-sm-6 col-xs-6">
                        <img src="{{url('website/assets/img/left-chevron.png')}}" style="padding-right:0px; float: left; cursor: pointer" alt="back" onclick="window.location.href = '{{url('cart')}}'"><span class="backTo" onclick="window.location.href = '{{url('cart')}}'">{{ trans('cart.back-to-cart') }}</span>
                    </div>
                </div>
            {!! Form::close() !!}

            <form id="payment-form" action="" method="post">

            </form>

        </div>
    </div>
</div>

@include('website.footer')
<script>
    $('.nav.navbar-nav.side-nav > li').click(function () {

        $("li.active").removeClass("active");

        $(this).addClass('active');
    });

    $(function (){
        $("#payment-method-container img").click(function (){
            $("#payment-method-container").hide();
            $("#payment-redirecting").show();

            $.get("{{ url('/api/checkout') }}/" + $(this).attr("data-type"), function (result) {
                var $form = $("#payment-form");
                $form.attr("action", result.url);
                $.each(result.data, function (key, value) {
                    $("<input>").attr("type", "hidden").attr("name", key).attr("id", key).attr("value", value).appendTo($form);
                });
                $form.submit();
            });
        });
    });
</script>
</body>
</html>