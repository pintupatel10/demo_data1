<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="HandheldFriendly" content="true" />
    <meta name="viewport" content="user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, width=device-width" />
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('website/bootstrap/css/bootstrap.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('website/assets/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('website/assets/css/custom-css.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('website/font-awesome/css/font-awesome.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('website/assets/css/custom-css.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('website/css/jquerysctipttop.css')}}">
    <link href="{{ URL::asset('website/assets/backtotop/css/backTop.css')}}" rel="stylesheet" type="text/css" />
    <title>Grayline</title>
    <link rel="icon" href="{!! asset('favicon.ico') !!}"/>
</head>

<body>

@include('website.menu')

<div class="col-md-10 col-lg-10 col-sm-10 padding-0">
    <div class="col-md-2 tour_details_single_banner_bg affix" style="background: url({{url($product->image)}}) no-repeat center center fixed; -webkit-background-size: cover; -moz-background-size: cover;-o-background-size: cover;background-size: cover;">

    </div>
    <div class="col-md-10 col-lg-10 col-sm-10 padding-left-100-desktop margin-left-15per-desktop">
        <h2 class="font-sky-color font-eligible-regular">{{ $product['title'] }}</h2>
        <br>
        <button type="button" class="btn btn-submit-jasbir">
            @if ($type == 'tour')
                {{ trans('reserve.tour-no') }}: {{ $product['tour_code'] }}
            @elseif ($type == 'ticket')
                {{ trans('reserve.ticket-no') }}: {{ $product['ticket_code'] }}
            @elseif ($type == 'transportation')
                {{ trans('reserve.transportation-no') }}: {{ $product['transportation_code'] }}
            @endif
        </button>
        <hr class="grey-bg-hr">

        {!! Form::open(['url' => url('cart'),'class' => 'form-horizontal','files' => true]) !!}

        <div class="col-md-12 padding-left-0">
            @if ($type == 'tour')
                <div class="col-md-4"> {{ trans('reserve.tour-date') }}: </div>
            @else
                <div class="col-md-4"> {{ trans('reserve.travel-date') }}: </div>
            @endif
            <div class="col-md-8">
                <input  id="date-input"  name="date" type="text" class="form-control" value="{{ old('date') }}" style="height:26px; width: 220px;float:left" onkeydown="return false">
                &nbsp; <span class="mandatory">*</span>

                @if ($errors->has('date'))<span class="help-block"><strong>{{ $errors->first('date') }}</strong></span> @endif
            </div>
            <div class="clearfix">&nbsp;</div>

            @if($type == 'transportation' && $price_group->time_table == 'On')
                <div class="col-md-4"> {{ trans('reserve.travel-time') }}: </div>
                <div class="col-md-8">
                    <select name="travel-time" id="travel-time" class="form-control reserve_form_selectfield float-left" style="height:30px; width: 220px;"></select>
                    <span class="mandatory">*</span>
                    @if ($errors->has('travel-time'))<span class="help-block"><strong>{{ $errors->first('travel-time') }}</strong></span> @endif
                </div>

                <div class="clearfix">&nbsp;</div>
            @endif

            @if($type == 'transportation' && $product['transportation_type'] == 'Turbojet')
                <div class="col-md-4"> {{ trans('reserve.class') }}: </div>
                <div class="col-md-8">
                    <select name="class" class="form-control reserve_form_selectfield float-left" style="height:30px; width: 220px;">
                        <option value="">Please Select</option>
                        <option value="economy" @if(old('class') == 'economy') selected="selected" @endif >{{ trans('reserve.economy') }}</option>
                        <option value="super" @if(old('class') == 'super') selected="selected" @endif >{{ trans('reserve.super') }}</option>
                        <option value="primer-grand" @if(old('class') == 'primer-grand') selected="selected" @endif >{{ trans('reserve.primer-grand') }}</option>
                        <option value="primer-vip" @if(old('class') == 'primer-vip') selected="selected" @endif >{{ trans('reserve.primer-vip') }}</option>
                    </select>
                    <span class="mandatory">*</span>
                </div>
                <div class="clearfix">&nbsp;</div>
            @endif

            <div class="col-md-4"> {{ trans('reserve.hotel') }}: </div>
            <div class="col-md-8">
                {!! Form::text('hotel', null, ['class' => 'form-control','style' => 'height:26px; width: 220px;float:left']) !!}
                &nbsp; <span class="mandatory">*</span>

            @if ($errors->has('hotel'))<span class="help-block"><strong>{{ $errors->first('hotel') }}</strong></span> @endif

            </div>
            <div class="clearfix">&nbsp;</div>
            <div class="col-md-4"> {{ trans('reserve.title') }}: </div>
            <div class="col-md-8">

                <select name="title" class="form-control" style="height:30px; width: 220px;float:left">
                    <option value="">{{ trans('reserve.please-select') }}</option>
                    <option value="{{ trans('reserve.mr') }}" @if(old('title') == trans('reserve.mr')) selected="selected" @endif >{{ trans('reserve.mr') }}</option>
                    <option value="{{ trans('reserve.mrs') }}" @if(old('title') == trans('reserve.mrs')) selected="selected" @endif >{{ trans('reserve.mrs') }}</option>
                    <option value="{{ trans('reserve.miss') }}" @if(old('title') == trans('reserve.miss')) selected="selected" @endif >{{ trans('reserve.miss') }}</option>
                </select>
                &nbsp; <span class="mandatory">*</span>

            @if ($errors->has('title'))<span  style="width: 220px;" class="help-block"><strong>{{ $errors->first('title') }}</strong></span> @endif

            </div>
                <div class="clearfix">&nbsp;</div>

            <div class="col-md-4"> {{ trans('reserve.first-name') }}: </div>
            <div class="col-md-8">
                {!! Form::text('first-name', null, ['class' => 'form-control','style' => 'height:26px; width: 220px;float:left']) !!}
                &nbsp; <span class="mandatory">*</span>

            @if ($errors->has('first-name'))<span class="help-block"><strong>{{ $errors->first('first-name') }}</strong></span> @endif

            </div>
            <div class="clearfix">&nbsp;</div>
            <div class="col-md-4"> {{ trans('reserve.last-name') }}: </div>
            <div class="col-md-8">
                {!! Form::text('last-name', null, ['class' => 'form-control','style' => 'height:26px; width: 220px;float:left']) !!}
                &nbsp; <span class="mandatory">*</span>

            @if ($errors->has('last-name'))<span class="help-block"><strong>{{ $errors->first('last-name') }}</strong></span> @endif
            </div>
            <div class="clearfix">&nbsp;</div>
            <div class="col-md-4"> {{ trans('reserve.passport') }}: </div>
            <div class="col-md-8">
                {!! Form::select('passport', \App\Nationality::getList(), null, ['class' => 'form-control', 'style' => 'height:30px; width: 220px;float:left']) !!}
                &nbsp; <span class="mandatory">*</span>

            @if ($errors->has('passport'))<span style="width: 220px;"class="help-block"><strong>{{ $errors->first('passport') }}</strong></span> @endif

            </div>
            <div class="clearfix">&nbsp;</div>
            <div class="col-md-4"> {{ trans('reserve.email') }}: </div>
            <div class="col-md-8">
                {!! Form::text('email', null, ['class' => 'form-control','style' => 'height:26px; width: 220px;float:left']) !!}
                &nbsp; <span class="mandatory">*</span>

            @if ($errors->has('email'))<span class="help-block"><strong>{{ $errors->first('email') }}</strong></span> @endif

            </div>
            <div class="clearfix">&nbsp;</div>
            <div class="col-md-4"> {{ trans('reserve.tel') }}: </div>
            <div class="col-md-8">
                <input name="country-code" type="text" class="form-control reserve_form_txtfield txtfield_small_width_50" value="+852">
                {!! Form::text('telephone', null, ['class' => 'form-control','style' => 'height:26px; width: 165px;']) !!}
            </div>

            <div class="clearfix">&nbsp;</div>

            @if ($type == 'tour')
                <div class="col-md-4"> {{ trans('reserve.promo-code') }}: </div>
                <div class="col-md-8">
                    <input type="text" name="promocode" id="promocode" class="form-control reserve_form_txtfield" value="" >
                    <div id="promocode-error" style="display: none">
                        <span class='help-block'><strong>{{ trans('reserve.invalid-promo-code') }}</strong></span>
                    </div>
                </div>
                <div class="clearfix">&nbsp;</div>
            @endif

            <div class="col-md-4"> {{ trans('reserve.remark') }}: </div>
            <div class="col-md-8">
                {!! Form::textarea('remark', null, ['class' => 'form-control','style' => 'height:80px; width: 220px;']) !!}
            </div>
            <div class="clearfix">&nbsp;</div>
            <span class="blue-color font-eligible-regular">{{ trans('reserve.no-of-packages') }}</span>
            <hr class="grey-bg-hr" style="padding-top:0; margin:0">
            @if ($errors->has('inventory'))<span class="help-block"><strong>{{ $errors->first('inventory') }}</strong></span> @endif

            @foreach ($prices as $key => $price)
                <div class="col-md-4"> {{$price->title}}:</div>
                <div class="col-md-8">
                    <select name="subqty{{$price->id}}" id="subqty{{$price->id}}" onchange="get_price()" class="select-count"></select>

                    &nbsp; x HKD {{$price->price}} </div>
                <div class="clearfix">&nbsp;</div>
                <input type="hidden" name="subprice{{$price->id}}" id="subprice{{$price->id}}" value="{{$price->price}}">
            @endforeach

             <input type="hidden" name="product-id" value="{{ $product->id }}">
             <input type="hidden" name="price-group-id" value="{{ $price_group->id }}">
             <input type="hidden" name="type" value="{{ $type }}">
             <input type="hidden" name="total-price-ver" value="0">

             <div class="col-md-4"> {{ trans('reserve.sub-total') }}: </div>
             <div class="col-md-8">HKD <span id="sub-total">0</span></div>
            <div class="clearfix">&nbsp;</div>

             @if ($type != 'tour' && $price_group->servicecharge > 0)
                <div class="col-md-4"> {{ trans('reserve.service-charge') }}: </div>
                <div class="col-md-8"><span id="total-quantity">0</span> x HKD {{ $price_group->servicecharge  }} </div>
                <div class="clearfix">&nbsp;</div>
             @endif

             @if ($type != 'transportation')
                 <div class="col-md-4"> {{ trans('reserve.discount') }}:</div>
                 <div class="col-md-8">HKD <span id="discounted">0</span></div>
                 <div class="clearfix">&nbsp;</div>
             @endif

             <div class="col-md-4"> {{ trans('reserve.total-amount') }}: </div>
             <div class="col-md-8"><strong>HKD <span id="total-price">0</span></strong></div>
             <div class="clearfix">&nbsp;</div>

             <hr class="grey-bg-hr">

             <div>
                 <input id="option" type="checkbox" name="info" value="1" style="width:0">
                 <label for="option" style="margin-left: 0"><span><span></span></span> {{ trans('reserve.adv') }}</label>
                 <div class="clearfix">&nbsp;</div>
             </div>
             <div>
                 <input id="option1" type="checkbox" name="terms" value="1" style="width:0">
                 <label for="option1" style="margin-left: 0"><span><span></span></span> {!! trans('reserve.terms') !!} </label>
                 @if ($errors->has('terms'))<span class="help-block"><strong>{{ $errors->first('terms') }}</strong></span> @endif
             </div>

             <div class="clearfix" style="padding:10px">&nbsp;</div>

                <div class="col-xs-12">
                    <button class="btn btn-default btn-submit-jasbir"><span class="glyphicon glyphicon-shopping-cart"></span>{{ trans('reserve.add-to-cart') }}</button>

                    <button type="reset" class="btn btn-reset-jasbir">{{ trans('reserve.clear') }}</button>
                </div>

                <div class="col-xs-12">
                    @if ($errors->has('add-to-cart'))<span class="help-block"><strong>{{ $errors->first('add-to-cart') }}</strong></span> @endif
                </div>
         </div>

         <div id="term-modal" class="modal fade" role="dialog">
             <div class="modal-dialog" style="width:50%;">
                 <div class="modal-content">
                     <div class="modal-header">
                         <button type="button" class="close" data-dismiss="modal">&times;</button>
                     </div>
                     <div class="modal-body">
                         {!! $terms !!}
                        <div class="clearfix" style="padding:1px;">&nbsp;</div>
                    </div>
                    <div class="clearfix" style="padding:1px;">&nbsp;</div>
                </div>
            </div>
        </div>
        {!! Form::close() !!}

    </div>
    <div class="clearfix" style="padding:40px;">&nbsp;</div>
</div>

<!-- jQuery library -->
<script src="{{ URL::asset('website/jquery/3.1.1/jquery.min.js')}}"></script>

<!-- Latest compiled JavaScript -->
<script src="{{ URL::asset('website/bootstrap/js/bootstrap.min.js')}}"></script>


<a id="backTop">Back To Top</a>
<script src="{{ URL::asset('website/jquery/1.11.2/jquery.min.js')}}"></script>
<script src="{{ URL::asset('website/assets/backtotop/src/jquery.backTop.js')}}"></script>
<script type="text/javascript" src="{{ URL::asset('website/assets/js/moment.min.js')}}"></script>
<script type="text/javascript" src="{{ URL::asset('website/assets/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js')}}"></script>
<script>
    var price_ids = {!! json_encode($prices->pluck('id')) !!};

    $(document).ready(function () {
        if (0 < $('.product button').length) {
            $('.product button').click(function () {
                var offset = $(this).parent().offset();
                $(this).parent().clone().addClass('product-clone').css({
                    'left': offset.left + 'px',
                    'top': parseFloat(offset.top - $(window).scrollTop()) + 'px',
                    'width': $(this).parent().width() + 'px',
                    'height': $(this).parent().height() + 'px'
                }).appendTo($('.product').parent());

                var cart = $('strong').offset();

                $('.product-clone').animate({top: parseFloat(cart.top - $(window).scrollTop()) + 'px', left: cart.left + 'px', 'height': '0px', 'width': '0px'}, 500, function () {
                    $(this).remove();
                    var price = parseFloat($('.addNumber').attr('data-price'));
                    var productPrice = parseFloat($('.product').attr('data-price'));
                    //var productPrice = parseFloat($(this).attr('data-price'));
                    var cartPrice = parseFloat(price + productPrice);

                    $('strong .badge').html(cartPrice);
                    $('.addNumber').attr('data-price', cartPrice);
                });
            });
        }
        
        $('#backTop').backTop({
            'position' : 100,
            'speed' : 500,
            'color' : 'red',
        });


        $("#date-input").datetimepicker({
            useCurrent: false,
	        format: 'YYYY-MM-DD',
	        minDate: moment(),
	        maxDate: moment().add(1, 'years'),
	        disabledDates: {!! json_encode($disabled_dates) !!}
	    }).on("dp.change", function (e){
		    // Prevent calling quota api if date has not changed
		    if (!e.oldDate || e.date.format("YYYY-MM-DD") != e.oldDate.format("YYYY-MM-DD")) {
		        var date = e.date.format("YYYY-MM-DD");
                show_quota(date);

                @if($type == 'transportation' && $price_group->time_table == 'On')
                    check_time(date);
                @endif
            }
		});
		
		$("#promocode").change(function (){
            get_price();
		});

		// Fill old values for validation error
        @if (old("date"))
            show_quota($("#date-input").val(), function (){
                @foreach ($prices as $price)
                    @if (old("subqty$price->id"))
                        $("#subqty" + {{ $price->id }}).val({{ old("subqty$price->id") }});
                    @endif
                @endforeach

                get_price();
            });

            @if($type == 'transportation' && $price_group->time_table == 'On')
                check_time($("#date-input").val(), function (){
                    @if (old("travel-time"))
                        $("#travel-time").val("{{ old('travel-time') }}");
                    @endif
                });
            @endif
        @endif
    });
    
    function create_option($select, min, max)
    {
	    for (var i=min; i<=max; i++)
	    {
	    	$select.append($("<option></option>").attr("value",i).text(i));
	    }
    }

    function show_quota(date, callback)
    {
        $(".select-count").empty();
        $.post('{{ url("api/reserve/$type/$cid/$price_group_id/quota") }}', {date: date}, function (result){

            price_ids.forEach(function (i){
                var $select = $("#subqty" + i);

                if (result[i] == -1)
                    create_option($select, 0, 99);
                else if (result[i] == 0)
                    create_option($select, 0, 0);
                else
                    create_option($select, 0, result[i]);
            });

            if (callback !== undefined)
                callback();
            else
                get_price();        // date change, so check price again
        });
    }

    function get_price()
    {
        $("#promocode-error").hide();

        var quantity = {};
        price_ids.forEach(function (i){
            quantity[i] = parseInt($("#subqty" + i).val());
        });

        $.post('{{ url("api/reserve/$type/$cid/$price_group_id/price") }}', {
            date: $("#date-input").val(),
            promocode: $("#promocode").val(),
            quantity: quantity,
        }, function (result){
            if (result.discount_type !== undefined && result.discount_type == "invalid")
                $("#promocode-error").show();

            if (result['total-quantity'] !== undefined)
                $("#total-quantity").html(result['total-quantity']);

            if (result['discounted'] !== undefined)
                $("#discounted").html(result['discounted']);

            $("#sub-total").html(result['sub-total']);
            $("#total-price").html(result['total-price']);
            $("input[name=total-price-ver]").val(result['total-price']);
        });
    }

    function check_time(date, callback){
        $("#travel-time").empty();
        $.post('{{ url("api/reserve/transportation/$price_group->id/timetable") }}', {date: date}, function (result){
            result.forEach(function (i){
                $("#travel-time").append($("<option></option>").attr("value",i).text(i));
            });

            if (callback !== undefined)
                callback();
        });
    }


</script>
</body>
</html>


