<!doctype html>
<html>
<head>
   @include('website.header')
    <title>Grayline</title>
    <link rel="icon" href="{!! asset('favicon.ico') !!}"/>
    <style>
        .str_line::after {
            border-color: #173C6B;
            width: 100% ;
        }
        .circle.card, .circle.card > i ,.circle.check, .circle.check > i {
            border-color: #173C6B ;
            color: #173C6B;
        }
        .shopping_payment > p,.shopping_done > p
        {
            color:#173C6B;
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
        <div class="mg-top">
            <h1 class='thanks_h'>
                {{ trans('cart.thank-you-title') }}
            </h1>
            <p class='thanks_p'>
                {{ trans('cart.thank-you-body') }}
                <br /><br />
                @if (Session::has('order-id'))
                {{ trans('email.order-no') }}: {{ Session::get('order-id') }}
                @endif
            </p>
        </div>
    </div>
</div>
@include('website.footer')
<script>
    $('.nav.navbar-nav.side-nav > li').click(function () {

        $("li.active").removeClass("active");

        $(this).addClass('active');
    });
</script>
</body>
</html>