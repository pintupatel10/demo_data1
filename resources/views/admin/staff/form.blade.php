{!! Form::hidden('redirects_to', URL::previous()) !!}

<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
    <label class="col-sm-2 control-label" for="name">Staff Name <span class="text-red">*</span></label>
    <div class="col-sm-5">
        {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Staff Name']) !!}
        @if ($errors->has('name'))
            <span class="help-block">
                <strong>{{ $errors->first('name') }}</strong>
            </span>
        @endif
    </div>
</div>

<div class="form-group{{ $errors->has('number') ? ' has-error' : '' }}">
    <label class="col-sm-2 control-label" for="number">Staff Number<span class="text-red"> *</span></label>
    <div class="col-sm-5">
        {!! Form::text('number', null, ['class' => 'form-control', 'placeholder' => 'Staff Number']) !!}
        @if ($errors->has('number'))
            <span class="help-block">
                <strong>{{ $errors->first('number') }}</strong>
            </span>
        @endif
    </div>
</div>

<div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
    <label class="col-sm-2 control-label" for="password">Password<span class="text-red">*</span></label>
    <div class="col-sm-5">
        <input type="password" placeholder="Password" id="password" name="password" class="form-control" >
        @if ($errors->has('password'))
            <span class="help-block">
                <strong>{{ $errors->first('password') }}</strong>
            </span>
        @endif
    </div>
</div>



<div class="form-group{{ $errors->has('group_id') ? ' has-error' : '' }}">
    <label class="col-sm-2 control-label" for="group_id">Staff Group<span class="text-red">*</span></label>
    <div class="col-sm-5">
        {!! Form::select('group_id[]', $name, !empty($modes_selected)?$modes_selected:null, ['class' => 'select2 select2-hidden-accessible form-control', 'style' => 'width: 100%','multiple']) !!}
        @if ($errors->has('group_id'))
            <span class="help-block">
                <strong>{{ $errors->first('group_id') }}</strong>
            </span>
        @endif
    </div>
</div>


<div class="form-group{{ $errors->has('status') ? ' has-error' : '' }}">
    <label class="col-sm-2 control-label" for="role">Status <span class="text-red">*</span></label>

    <div class="col-sm-5">

        @foreach (\App\Staff::$status as $key => $value)
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
