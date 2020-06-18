<div class="form-group{{ $errors->has('event_code') ? ' has-error' : '' }}">
    <label class="col-sm-2 control-label" for="event_code">Event Code <span class="text-red">*</span></label>
    <div class="col-sm-5">
        {!! Form::text('event_code', null, ['class' => 'form-control', 'placeholder' => 'Event Code']) !!}
        @if ($errors->has('event_code'))
            <span class="help-block">
                <strong>{{ $errors->first('event_code') }}</strong>
            </span>
        @endif
    </div>
</div>

<div class="form-group{{ $errors->has('ticket_code') ? ' has-error' : '' }}">
    <label class="col-sm-2 control-label" for="ticket_code">Ticket Code <span class="text-red">*</span></label>
    <div class="col-sm-5">
        {!! Form::text('ticket_code', null, ['class' => 'form-control', 'placeholder' => 'Ticket Code']) !!}
        @if ($errors->has('ticket_code'))
            <span class="help-block">
                <strong>{{ $errors->first('ticket_code') }}</strong>
            </span>
        @endif
    </div>
</div>

<div class="form-group{{ $errors->has('pickup_id') ? ' has-error' : '' }}">
    <label class="col-sm-2 control-label" for="pickup_id">Pickup ID<span class="text-red">*</span></label>
    <div class="col-sm-5">
        {!! Form::number('pickup_id', null, ['class' => 'form-control', 'placeholder' => 'Pickup ID']) !!}
        @if ($errors->has('pickup_id'))
            <span class="help-block">
                <strong>{{ $errors->first('pickup_id') }}</strong>
            </span>
        @endif
    </div>
</div>