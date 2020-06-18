<div class="form-group{{ $errors->has('event_id') ? ' has-error' : '' }}">
    <label class="col-sm-2 control-label" for="event_id">Event Code <span class="text-red">*</span></label>
    <div class="col-sm-5">
        {!! Form::text('event_id', null, ['class' => 'form-control', 'placeholder' => 'Event ID']) !!}
        @if ($errors->has('event_id'))
            <span class="help-block">
                <strong>{{ $errors->first('event_id') }}</strong>
            </span>
        @endif
    </div>
</div>

<div class="form-group{{ $errors->has('type') ? ' has-error' : '' }}">
    <label class="col-sm-2 control-label" for="type">Type<span class="text-red">*</span></label>
    <div class="col-sm-5">
        {!! Form::select('type', App\OceanParkTicket::$types, null, ['class' => 'form-control']) !!}
        @if ($errors->has('type'))
            <span class="help-block">
                <strong>{{ $errors->first('type') }}</strong>
            </span>
        @endif
    </div>
</div>

<div class="form-group{{ $errors->has('type_id') ? ' has-error' : '' }}">
    <label class="col-sm-2 control-label" for="type_id">Type ID<span class="text-red">*</span></label>
    <div class="col-sm-5">
        {!! Form::text('type_id', null, ['class' => 'form-control', 'placeholder' => 'Type ID']) !!}
        @if ($errors->has('type_id'))
            <span class="help-block">
                <strong>{{ $errors->first('type_id') }}</strong>
            </span>
        @endif
    </div>
</div>