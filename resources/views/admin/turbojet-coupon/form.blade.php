<div class="form-group{{ $errors->has('type') ? ' has-error' : '' }}">
    <label class="col-sm-2 control-label" for="type">Type <span class="text-red">*</span></label>
    <div class="col-sm-5">
        {!! Form::select('type', App\TurbojetCoupon::$types, null, ['class' => 'form-control']) !!}
        @if ($errors->has('type'))
            <span class="help-block">
                <strong>{{ $errors->first('type') }}</strong>
            </span>
        @endif
    </div>
</div>

<div class="form-group{{ $errors->has('route_id') ? ' has-error' : '' }}">
    <label class="col-sm-2 control-label" for="route_id">Route <span class="text-red">*</span></label>
    <div class="col-sm-5">
        {!! Form::select('route_id', App\TurbojetCouponRoute::getDropdown(), null, ['class' => 'form-control']) !!}
        @if ($errors->has('route_id'))
            <span class="help-block">
                <strong>{{ $errors->first('route_id') }}</strong>
            </span>
        @endif
    </div>
</div>

<div class="form-group{{ $errors->has('seat_class') ? ' has-error' : '' }}">
    <label class="col-sm-2 control-label" for="seat_class">Seat Class <span class="text-red">*</span></label>
    <div class="col-sm-5">
        {!! Form::select('seat_class', App\TurbojetCoupon::$classes, null, ['class' => 'form-control']) !!}
        @if ($errors->has('seat_class'))
            <span class="help-block">
                <strong>{{ $errors->first('seat_class') }}</strong>
            </span>
        @endif
    </div>
</div>

<div class="form-group{{ $errors->has('code') ? ' has-error' : '' }}">
    <label class="col-sm-2 control-label" for="ticket_code">Coupon Code <span class="text-red">*</span></label>
    <div class="col-sm-5">
        {!! Form::text('code', null, ['class' => 'form-control', 'placeholder' => 'Coupon Code']) !!}
        @if ($errors->has('code'))
            <span class="help-block">
                <strong>{{ $errors->first('code') }}</strong>
            </span>
        @endif
    </div>
</div>

<div class="form-group">
    <label class="col-sm-2 control-label" for="is_weekday">Weekday</label>
    <div class="col-sm-5">
        <div class="checkbox">
            <label>
                {!! Form::checkbox('is_weekday', null) !!} Yes
            </label>
        </div>
    </div>
</div>

<div class="form-group">
    <label class="col-sm-2 control-label" for="is_weekend">Weekend</label>
    <div class="col-sm-5">
        <div class="checkbox">
            <label>
                {!! Form::checkbox('is_weekend', null) !!} Yes
            </label>
        </div>
    </div>
</div>

<div class="form-group">
    <label class="col-sm-2 control-label" for="is_day">Day Sail</label>
    <div class="col-sm-5">
        <div class="checkbox">
            <label>
                {!! Form::checkbox('is_day', null) !!} Yes
            </label>
        </div>
    </div>
</div>

<div class="form-group">
    <label class="col-sm-2 control-label" for="is_night">Night Sail</label>
    <div class="col-sm-5">
        <div class="checkbox">
            <label>
                {!! Form::checkbox('is_night', null) !!} Yes
            </label>
        </div>
    </div>
</div>