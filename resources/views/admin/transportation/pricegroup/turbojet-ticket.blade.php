<div class="form-group{{ $errors->has('turbojet_ticket.type') ? ' has-error' : '' }}">
    <label class="col-sm-2 control-label" for="title">Type <span class="text-red">*</span></label>
    <div class="col-sm-5">
        {!! Form::select('turbojet_ticket[type]', \App\TurbojetTicket::$types, null, ['class' => 'form-control']) !!}
        @if ($errors->has('turbojet_ticket.type'))
            <span class="help-block">
                <strong>{{ $errors->first('turbojet_ticket.type') }}</strong>
            </span>
        @endif
    </div>
</div>

<div class="form-group{{ $errors->has('turbojet_ticket.departure_city') ? ' has-error' : '' }}">
    <label class="col-sm-2 control-label" for="title">Departure City <span class="text-red">*</span></label>
    <div class="col-sm-5">
        {!! Form::select('turbojet_ticket[departure_city]', \App\TurbojetTicket::$departures, null, ['class' => 'form-control']) !!}
        @if ($errors->has('turbojet_ticket.departure_city'))
            <span class="help-block">
                <strong>{{ $errors->first('turbojet_ticket.departure_city') }}</strong>
            </span>
        @endif
    </div>
</div>

<div class="form-group{{ $errors->has('turbojet_ticket.city_1') ? ' has-error' : '' }}">
    <label class="col-sm-2 control-label" for="title">City 1 Name <span class="text-red">*</span></label>
    <div class="col-sm-5">
        {!! Form::text('turbojet_ticket[city_1]', null, ['class' => 'form-control', 'placeholder' => 'City 1 Name']) !!}
        @if ($errors->has('turbojet_ticket.city_1'))
            <span class="help-block">
                <strong>{{ $errors->first('turbojet_ticket.city_1') }}</strong>
            </span>
        @endif
    </div>
</div>

<div class="form-group{{ $errors->has('turbojet_ticket.city_1_code') ? ' has-error' : '' }}">
    <label class="col-sm-2 control-label" for="title">City 1 Code <span class="text-red">*</span></label>
    <div class="col-sm-5">
        {!! Form::text('turbojet_ticket[city_1_code]', null, ['class' => 'form-control', 'placeholder' => 'City 2 Code']) !!}
        @if ($errors->has('turbojet_ticket.city_1_code'))
            <span class="help-block">
                <strong>{{ $errors->first('turbojet_ticket.city_1_code') }}</strong>
            </span>
        @endif
    </div>
</div>

<div class="form-group{{ $errors->has('turbojet_ticket.city_2') ? ' has-error' : '' }}">
    <label class="col-sm-2 control-label" for="title">City 2 Name <span class="text-red">*</span></label>
    <div class="col-sm-5">
        {!! Form::text('turbojet_ticket[city_2]', null, ['class' => 'form-control', 'placeholder' => 'City 2 Name']) !!}
        @if ($errors->has('turbojet_ticket.city_2'))
            <span class="help-block">
                <strong>{{ $errors->first('turbojet_ticket.city_2') }}</strong>
            </span>
        @endif
    </div>
</div>

<div class="form-group{{ $errors->has('turbojet_ticket.city_2_code') ? ' has-error' : '' }}">
    <label class="col-sm-2 control-label" for="title">City 2 Code <span class="text-red">*</span></label>
    <div class="col-sm-5">
        {!! Form::text('turbojet_ticket[city_2_code]', null, ['class' => 'form-control', 'placeholder' => 'City 2 Code']) !!}
        @if ($errors->has('turbojet_ticket.city_2_code'))
            <span class="help-block">
                <strong>{{ $errors->first('turbojet_ticket.city_2_code') }}</strong>
            </span>
        @endif
    </div>
</div>

<div class="form-group{{ $errors->has('turbojet_ticket.top_up_fee') ? ' has-error' : '' }}">
    <label class="col-sm-2 control-label" for="title">Top Up Fee</label>
    <div class="col-sm-5">
        {!! Form::number('turbojet_ticket[top_up_fee]', null, ['class' => 'form-control', 'placeholder' => '0']) !!}
        @if ($errors->has('turbojet_ticket.top_up_fee'))
            <span class="help-block">
                <strong>{{ $errors->first('turbojet_ticket.top_up_fee') }}</strong>
            </span>
        @endif
    </div>
</div>