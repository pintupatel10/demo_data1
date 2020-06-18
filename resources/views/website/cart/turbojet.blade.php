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
    <link href="http://www.jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet" type="text/css">
    <link href="{{ URL::asset('website/assets/backtotop/css/backTop.css')}}" rel="stylesheet" type="text/css" />
    <title>Gray Line Tours | Reserve</title>
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

        <div class="padding-left-0">

            <div class="col-md-4"> {{ trans('reserve.class') }}: </div>
            <div class="col-md-8">
                <select name="class" class="form-control reserve_form_selectfield float-left" style="height:30px; width: 220px;">
                    <option value="" disabled selected>{{ trans('reserve.please-select') }}</option>
                    <option value="economy" @if(old('class') == 'economy') selected="selected" @endif >{{ trans('reserve.economy') }}</option>
                    <option value="super" @if(old('class') == 'super') selected="selected" @endif >{{ trans('reserve.super') }}</option>
                    <option value="primer-grand" @if(old('class') == 'primer-grand') selected="selected" @endif >{{ trans('reserve.primer-grand') }}</option>
                </select>
                &nbsp; <span class="mandatory">*</span>
            </div>
            <div class="clearfix">&nbsp;</div>

            @if ($price_group->turbojet_ticket->departure_city == \App\TurbojetTicket::DEPARTURE_CITY_ANY)
            <div class="col-md-4"> {{ trans('reserve.departure-city') }}: </div>
            <div class="col-md-8">
                <select name="departure_city" class="form-control reserve_form_selectfield float-left" style="height:30px; width: 220px;">
                    <option value="city_1" @if(old('departure_city') == 'city_1') selected="selected" @endif >{{ $price_group->turbojet_ticket->city_1 }}</option>
                    <option value="city_2" @if(old('departure_city') == 'city_2') selected="selected" @endif >{{ $price_group->turbojet_ticket->city_2 }}</option>
                </select>
                &nbsp; <span class="mandatory">*</span>
            </div>
            <div class="clearfix">&nbsp;</div>
            @endif

            <div class="col-md-4"> {{ trans('reserve.no-of-tickets') }}: </div>
            <div class="col-md-8">
                <select name="tickets" class="form-control reserve_form_selectfield float-left" style="height:30px; width: 220px;">
                    <option value="" disabled selected>{{ trans('reserve.please-select') }}</option>
                    @for ($i=1; $i<=20; $i++)
                        <option value="{{ $i }}" @if (old('tickets') == $i) selected="selected" @endif>{{ $i }}</option>
                    @endfor
                </select>
                &nbsp; <span class="mandatory">*</span>
            </div>
            <div class="clearfix">&nbsp;</div>

            <!-- Departure Flight -->
            <div class="blue-color font-eligible-regular" id="departure-direction" style="margin-top: 50px; font-size: 17px;"></div>
            <hr class="grey-bg-hr" style="padding: 0; margin: 5px 0 15px 0;">
            <div class="col-md-4"> {{ trans('reserve.departure-date') }}: </div>
            <div class="col-md-8">
                <input  id="departure-date-input"  name="departure-date" type="text" class="form-control" value="{{ old('departure-date') }}" style="height:26px; width: 220px;float:left" onkeydown="return false">
                &nbsp; <span class="mandatory">*</span>

                @if ($errors->has('departure-date'))<span class="help-block"><strong>{{ $errors->first('departure-date') }}</strong></span> @endif
            </div>
            <div class="clearfix">&nbsp;</div>

            <div class="col-md-4"> {{ trans('reserve.departure-time') }}: </div>
            <div class="col-md-8">
                <input id="departure-time-input" name="departure-time" type="text" class="form-control" value="{{ old('departure-time') }}" style="height:26px; width: 220px;float:left" onkeydown="return false">
                &nbsp; <span class="mandatory">*</span>
                @if ($errors->has('departure-time'))<span class="help-block"><strong>{{ $errors->first('departure-time') }}</strong></span> @endif
            </div>
            <div class="clearfix">&nbsp;</div>

            @if ($errors->has('departure-inventory'))<span class="help-block"><strong>{{ $errors->first('departure-inventory') }}</strong></span> @endif

            <div id="departure-flight-container" class="col-md-12"></div>
            <div class="clearfix"></div>
            <!-- End Departure -->

            @if ($price_group->turbojet_ticket->type == \App\TurbojetTicket::TYPE_ROUND_TRIP)
            <!-- Return Flight -->
            <div class="blue-color font-eligible-regular" id="return-direction" style="margin-top: 50px; font-size: 17px;"></div>
            <hr class="grey-bg-hr" style="padding: 0; margin: 5px 0 15px 0;">
            <div class="col-md-4"> {{ trans('reserve.return-date') }}: </div>
            <div class="col-md-8">
                <input  id="return-date-input"  name="return-date" type="text" class="form-control" value="{{ old('return-date') }}" style="height:26px; width: 220px;float:left" onkeydown="return false">
                &nbsp; <span class="mandatory">*</span>

                @if ($errors->has('return-date'))<span class="help-block"><strong>{{ $errors->first('return-date') }}</strong></span> @endif
            </div>
            <div class="clearfix">&nbsp;</div>

            <div class="col-md-4"> {{ trans('reserve.return-time') }}: </div>
            <div class="col-md-8">
                <input id="return-time-input" name="return-time" type="text" class="form-control" value="{{ old('return-time') }}" style="height:26px; width: 220px;float:left" onkeydown="return false">
                &nbsp; <span class="mandatory">*</span>
                @if ($errors->has('return-time'))<span class="help-block"><strong>{{ $errors->first('return-time') }}</strong></span> @endif
            </div>
            <div class="clearfix">&nbsp;</div>

            @if ($errors->has('return-inventory'))<span class="help-block"><strong>{{ $errors->first('return-inventory') }}</strong></span> @endif

            <div id="return-flight-container" class="col-md-12"></div>
            <div class="clearfix"></div>
            <!-- End Return -->
            @endif

            <!-- Guest Info -->
            <div class="blue-color font-eligible-regular" style="margin-top: 50px; font-size: 17px;">{{ trans('reserve.guest-info') }}</div>
            <hr class="grey-bg-hr" style="padding: 0; margin: 5px 0 15px 0;">
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
                    <option value="" disabled selected>{{ trans('reserve.please-select') }}</option>
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

            <div class="col-md-4"> {{ trans('reserve.remark') }}: </div>
            <div class="col-md-8">
                {!! Form::textarea('remark', null, ['class' => 'form-control','style' => 'height:80px; width: 220px;']) !!}
            </div>
            <div class="clearfix">&nbsp;</div>
            <!-- End Guest Info -->



            <hr class="grey-bg-hr">

             <input type="hidden" name="city-1-code" value="{{ $price_group->turbojet_ticket->city_1_code }}">
             <input type="hidden" name="city-1-name" value="{{ $price_group->turbojet_ticket->city_1 }}">
             <input type="hidden" name="city-2-code" value="{{ $price_group->turbojet_ticket->city_2_code }}">
             <input type="hidden" name="city-2-name" value="{{ $price_group->turbojet_ticket->city_2 }}">
             <input type="hidden" name="departure-city-name" value="">
             <input type="hidden" name="departure-city-code" value="">
             <input type="hidden" name="return-city-name" value="">
             <input type="hidden" name="return-city-code" value="">
             <input type="hidden" name="product-id" value="{{ $product->id }}">
             <input type="hidden" name="price-group-id" value="{{ $price_group->id }}">
             <input type="hidden" name="type" value="{{ $type }}">
             <input type="hidden" name="total-price-ver" value="0">

            @if ($price_group->turbojet_ticket->type == \App\TurbojetTicket::TYPE_ROUND_TRIP)
                 <div class="col-md-4"> {{ trans('reserve.ticket-fee') }} ({{ trans('reserve.departure') }}): </div>
                 <div class="col-md-8"><span class="total-quantity">0</span> x HKD <span id="departure-ticket-fee">0</span></div>
                <div class="clearfix">&nbsp;</div>

                 <div class="col-md-4"> {{ trans('reserve.ticket-fee') }} ({{ trans('reserve.return') }}): </div>
                 <div class="col-md-8"><span class="total-quantity">0</span> x HKD <span id="return-ticket-fee">0</span></div>
                <div class="clearfix">&nbsp;</div>
            @else
                <div class="col-md-4"> {{ trans('reserve.ticket-fee') }}: </div>
                <div class="col-md-8"><span class="total-quantity">0</span> x HKD <span id="departure-ticket-fee">0</span></div>
                <div class="clearfix">&nbsp;</div>
            @endif

             <div class="col-md-4"> {{ trans('reserve.sub-total') }}: </div>
             <div class="col-md-8">HKD <span id="sub-total">0</span></div>
            <div class="clearfix">&nbsp;</div>

             @if ($type != 'tour' && $price_group->servicecharge > 0)
                <div class="col-md-4"> {{ trans('reserve.service-charge') }}: </div>
                <div class="col-md-8"><span class="total-quantity">0</span> x HKD {{ $price_group->servicecharge  }} </div>
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
                    <button id="btn-submit" class="btn btn-submit-jasbir"><span class="glyphicon glyphicon-shopping-cart"></span>{{ trans('reserve.add-to-cart') }}</button>

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
<script src="{{ url('assets/plugins/jQuery/jQuery-2.2.0.min.js') }}"></script>

<!-- Latest compiled JavaScript -->
<script src="{{ URL::asset('website/bootstrap/js/bootstrap.min.js')}}"></script>


<a id="backTop">Back To Top</a>
<script src="{{ URL::asset('website/assets/backtotop/src/jquery.backTop.js')}}"></script>
<script type="text/javascript" src="{{ URL::asset('website/assets/js/moment.min.js')}}"></script>
<script type="text/javascript" src="{{ URL::asset('website/assets/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js')}}"></script>
<script>

    var is_round_trip = false;
    @if ($price_group->turbojet_ticket->type == \App\TurbojetTicket::TYPE_ROUND_TRIP)
        is_round_trip = true;
    @endif

    var is_city_any = false;
    @if ($price_group->turbojet_ticket->departure_city == \App\TurbojetTicket::DEPARTURE_CITY_ANY)
        is_city_any = true;
    @endif

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

        $("#btn-submit").prop('disabled', true);


        $("#departure-date-input").datetimepicker({
            useCurrent: false,
	        format: 'YYYY-MM-DD',
	        minDate: moment(),
	        maxDate: moment().add(30, 'days'),
	    }).on("dp.change", function (e){
		    // Prevent calling quota api if date has not changed
		    if (!e.oldDate || e.date.format("YYYY-MM-DD") != e.oldDate.format("YYYY-MM-DD")) {
                get_flights('departure');
            }
		});

        var departure_old_time = null;

        $("#departure-time-input").datetimepicker({
            useCurrent: false,
            format: 'HH:mm',
            stepping: 15
        }).on("dp.hide", function (e){
            if (!departure_old_time || departure_old_time.format("HH:mm") != e.date.format("HH:mm"))
            {
                departure_old_time = e.date;
                get_flights('departure');
            }
        });

        if (is_round_trip) {
            $("#return-date-input").datetimepicker({
                useCurrent: false,
                format: 'YYYY-MM-DD',
                minDate: moment(),
                maxDate: moment().add(30, 'days'),
            }).on("dp.change", function (e){
                // Prevent calling quota api if date has not changed
                if (!e.oldDate || e.date.format("YYYY-MM-DD") != e.oldDate.format("YYYY-MM-DD")) {
                    get_flights('return');
                }
            });

            var return_old_time = null;

            $("#return-time-input").datetimepicker({
                useCurrent: false,
                format: 'HH:mm',
                stepping: 15
            }).on("dp.hide", function (e){
                if (!return_old_time || return_old_time.format("HH:mm") != e.date.format("HH:mm"))
                {
                    return_old_time = e.date;
                    get_flights('return');
                }
            });
        }

        $("select[name=tickets]").change(function (){
            $(".total-quantity").text($(this).val());
            get_flights();
        });

        $("select[name=class]").change(function (){
            get_flights();
        });

        if (is_city_any)
        {
            $("select[name=departure_city]").change(function (){
                $("#departure-date-input").val("");
                $("#departure-time-input").val("");
                $("#return-date-input").val("");
                $("#return-time-input").val("");
                update_flight_info();
            });
        }
        else
        {
            $("input[name=departure-city-code]").val("{{ $price_group->turbojet_ticket->departure_city_code }}");
            $("input[name=departure-city-name]").val("{{ $price_group->turbojet_ticket->departure_city_name }}");
            $("input[name=return-city-code]").val("{{ $price_group->turbojet_ticket->return_city_code }}");
            $("input[name=return-city-name]").val("{{ $price_group->turbojet_ticket->return_city_name }}");
        }

		// Fill old values for validation error
        @if (old("passport"))
            update_flight_info('departure', function (){
                @if (old('departure-flight-no'))
                    $("input[name=departure-flight-no][value={{ old('departure-flight-no') }}]").prop('checked', true);
                    calc_price();
                @endif
            });

            if (is_round_trip) {
                update_flight_info('return', function () {
                    @if (old('return-flight-no'))
                        $("input[name=return-flight-no][value={{ old('return-flight-no') }}]").prop('checked', true);
                        calc_price();
                    @endif
                });
            }

            @if (old("tickets"))
                $(".total-quantity").text($("select[name=tickets]").val());
            @endif
        @else
            update_flight_info();
        @endif
    });

    function update_flight_info(direction, callback)
    {
        if (direction === undefined)
        {
            update_flight_info('departure', callback);
            if (is_round_trip)
                update_flight_info('return', callback);
            return;
        }

        if (is_city_any) {
            if ($("select[name=departure_city]").val() == "city_1") {
                $("input[name=departure-city-code]").val($("input[name=city-1-code]").val());
                $("input[name=departure-city-name]").val($("input[name=city-1-name]").val());
                $("input[name=return-city-code]").val($("input[name=city-2-code]").val());
                $("input[name=return-city-name]").val($("input[name=city-2-name]").val());
            }
            else {
                $("input[name=departure-city-code]").val($("input[name=city-2-code]").val());
                $("input[name=departure-city-name]").val($("input[name=city-2-name]").val());
                $("input[name=return-city-code]").val($("input[name=city-1-code]").val());
                $("input[name=return-city-name]").val($("input[name=city-1-name]").val());
            }
        }

        if (direction == "departure")
            $("#" + direction + "-direction").html($("input[name=departure-city-name]").val() + " >>> " + $("input[name=return-city-name]").val());
        else
            $("#" + direction + "-direction").html($("input[name=return-city-name]").val() + " >>> " + $("input[name=departure-city-name]").val());

        get_flights(direction, callback);
    }

    function get_flights(direction, callback)
    {
        if (direction === undefined)
        {
            get_flights('departure', callback);
            if (is_round_trip)
                get_flights('return', callback);
            return;
        }

        var date = $("#" + direction + "-date-input").val();
        var time = $("#" + direction + "-time-input").val();
        var vessel_class = $("select[name=class]").val();
        var tickets = $("select[name=tickets]").val();

        var $container = $("#" + direction + "-flight-container");
        $container.empty().html("{{ trans('reserve.flight-placeholder') }}");

        if (date == "" || time == "" || tickets == null || vessel_class == null)
            return;

        // empty and load spinner
        $container.empty().html('<div style="text-align: center; padding: 30px;"><i class="fa fa-spinner fa-spin" style="font-size: 36px"></i></div>');

        // reset
        calc_price();

        var data = {
            date: date,
            time: time,
            quantity: tickets,
            departure: is_city_any ? $("select[name=departure_city]").val() : null,
            direction: direction,
            vessel_class: vessel_class,
        };

        $.post('{{ url("api/reserve/transportation/$price_group->id/turbojet") }}', data, function (result){

            var html = "<table class='table table-striped'>" +
                       "    <thead>" +
                       "        <tr>" +
                       "            <th>Select</th>" +
                       "            <th>Flight No.</th>" +
                       "            <th>Time</th>" +
                       "            <th>Class</th>" +
                       "            <th>Fee Per Ticket</th>" +
                       "        </tr>" +
                       "    </thead>" +
                       "<tbody>";

            $.each(result, function (key, item){
                html += "<tr>" +
                        "    <td><div class='radio'><label><input type='radio' name='"+direction+"-flight-no' value='" + item.flight_no + "' style='opacity: 1' data-fee='"+item.fee+"'></label></div></td>" +
                        "    <td style='padding-top: 16px;'>" + item.flight_no + "</td>" +
                        "    <td style='padding-top: 16px;'>" + item.time + "</td>" +
                        "    <td style='padding-top: 16px;'>" + item.seat_class + "</td>" +
                        "    <td style='padding-top: 16px;'>HKD $" + item.fee  + "</td>" +
                        "</tr>";
            });

            if (result.length == 0)
            {
                html += "<tr><td colspan='5' align='center'>{{ trans('reserve.flight-empty') }}</td></tr>";
            }

            html += "</tbody></table>";

            var $html = $(html);

            $html.find("input[name=" + direction + "-flight-no]").change(function (){
                calc_price();
            });

            $container.empty();
            $container.append($html);

            if (callback !== undefined)
            {
                callback();
            }
        });
    }

    function calc_price()
    {
        var fee = 0;

        var departure_fee = $("input[name=departure-flight-no]:checked").attr("data-fee");
        if (departure_fee !== undefined)
        {
            fee += parseFloat(departure_fee);
            $("#departure-ticket-fee").html(departure_fee);
        }
        else
        {
            $("#departure-ticket-fee").html("0");
        }

        var return_fee = $("input[name=return-flight-no]:checked").attr("data-fee");
        if (return_fee !== undefined)
        {
            fee += parseFloat(return_fee);
            $("#return-ticket-fee").html(return_fee);
        }
        else
        {
            $("#return-ticket-fee").html("0");
        }


        var quantity = parseInt($("select[name=tickets]").val());
        var sub_total = fee * quantity;
        var service_charge = {{ $price_group->servicecharge }} * quantity;
        var total = sub_total + service_charge;

        $("#sub-total").html(sub_total);
        $("#total-price").html(total);
        $("input[name=total-price-ver]").val(total);

        $("#btn-submit").prop('disabled', total == 0);
    }


</script>
</body>
</html>


