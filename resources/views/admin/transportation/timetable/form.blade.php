{!! Form::hidden('redirects_to', URL::previous()) !!}

{{--<div class="form-group{{ $errors->has('alltime') ? ' has-error' : '' }}">--}}
    {{--<label class="col-sm-2 control-label" for="alltime">All Time/specific time: <span class="text-red">*</span></label>--}}

    {{--<div class="col-sm-8">--}}

        {{--@foreach (\App\TransportationTimetable::$post as $key => $value)--}}
            {{--<label>--}}
                {{--{!! Form::radio('alltime', $key, null, ['onclick'=>'calltype(this.value);']) !!} <span style="margin-right: 10px">{{ $value }}</span>--}}
            {{--</label>--}}
        {{--@endforeach--}}

        {{--@if ($errors->has('alltime'))--}}
            {{--<span class="help-block">--}}
             {{--<strong>{{ $errors->first('alltime') }}</strong>--}}
            {{--</span>--}}
        {{--@endif--}}
    {{--</div>--}}
{{--</div>--}}


<div class="form-group{{ $errors->has('time') ? ' has-error' : '' }}">
   {{--style="@if($time->alltime == "Specific time") visibility:visible; @else visibility: hidden;@endif" id="stock_type">--}}
    <label class="col-sm-2 control-label" for="title">Time </label>
    <div class="col-sm-5">
        <div class="bootstrap-timepicker">
        {!! Form::text('time', null, ['class' => 'form-control timepicker','placeholder' => 'Time']) !!}
        </div>
        @if ($errors->has('time'))
            <span class="help-block">
                <strong>{{ $errors->first('time') }}</strong>
                  </span>
        @endif
    </div>
</div>



<div class="form-group{{ $errors->has('Weekend/Weekday') ? ' has-error' : '' }}">
    <label class="col-sm-2 control-label" for="Weekend/Weekday">Weekend/Weekday <span class="text-red">*</span></label>
    <div class="col-sm-5">
        {!! Form::select('Weekend/Weekday[]',['please Select']+\App\TransportationTimetable::$type, !empty($modes_selected)?$modes_selected:null, ['class' => 'select2 select2-hidden-accessible form-control', 'style' => 'width: 100%']) !!}
        @if ($errors->has('Weekend/Weekday'))
            <span class="help-block">
                <strong>{{ $errors->first('Weekend/Weekday') }}</strong>
            </span>
        @endif
    </div>
</div>



<div class="form-group{{ $errors->has('status') ? ' has-error' : '' }}">
    <label class="col-sm-2 control-label" for="role">Status <span class="text-red">*</span></label>

    <div class="col-sm-5">

        @foreach (\App\TransportationTimetable::$status as $key => $value)
            <label>
                {!! Form::radio('status', $key, null, ['class' => 'flat-red']) !!} <span style="margin-right: 10px">{{ $value }}</span>
            </label>
        @endforeach

        @if ($errors->has('status'))
            <span class="help-block">
             <strong>{{ $errors->first('status') }}</strong>
            </span>
        @endif
    </div>
</div>


{{--<script>--}}
    {{--function calltype(val) {--}}
        {{--if (val == 'Specific time') {--}}
            {{--document.getElementById('stock_type').style.visibility = "visible";--}}
        {{--}--}}
        {{--else {--}}
            {{--document.getElementById('stock_type').style.visibility = "hidden";--}}
        {{--}--}}
    {{--}--}}
{{--</script>--}}