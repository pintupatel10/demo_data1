{!! Form::hidden('redirects_to', URL::previous()) !!}

<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
    <label class="col-sm-2 control-label" for="name">Name<span class="text-red">*</span></label>

    <div class="col-sm-5">
        {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Name']) !!}
        @if ($errors->has('name'))
            <span class="help-block">
                <strong>{{ $errors->first('name') }}</strong>
            </span>
        @endif
    </div>
</div>
<div class="form-group{{ $errors->has('accessright') ? ' has-error' : '' }}">
    <label class="col-sm-2 control-label" for="accessright">Access Right <span class="text-red">*</span></label>

    <div class="col-sm-10">
        <div class="row">
        @foreach (\App\Group::$access as  $key1 => $value1)
            <label class="col-sm-4">
                {!! Form::checkbox('accessright[]', $key1,!empty($accessselected)?$accessselected:null, ['class' => 'flat-red']) !!} <span style="margin-right: 10px">{{ $value1 }}</span>
            </label>
        @endforeach
        @if ($errors->has('accessright'))
            <span class="help-block">
             <strong>{{ $errors->first('accessright') }}</strong>
            </span>
        @endif
        </div>
    </div>
</div>

<div class="form-group{{ $errors->has('status') ? ' has-error' : '' }}">
    <label class="col-sm-2 control-label" for="role">Status <span class="text-red">*</span></label>

    <div class="col-sm-5">
        @foreach (\App\Group::$status as $key => $value)
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
