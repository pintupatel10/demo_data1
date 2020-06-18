{!! Form::hidden('redirects_to', URL::previous()) !!}

<div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
    <label class="col-sm-2 control-label" for="title">Title <span class="text-red">*</span></label>
    <div class="col-sm-5">
        {!! Form::text('title', null, ['class' => 'form-control', 'placeholder' => 'Title']) !!}
        @if ($errors->has('title'))
            <span class="help-block">
                <strong>{{ $errors->first('title') }}</strong>
            </span>
        @endif
    </div>
</div>

<div class="form-group{{ $errors->has('price') ? ' has-error' : '' }}">
    <label class="col-sm-2 control-label" for="price">Default Price <span class="text-red">*</span></label>
    <div class="col-sm-5">
        {!! Form::text('price', null, ['class' => 'form-control', 'placeholder' => 'Price']) !!}
        @if ($errors->has('price'))
            <span class="help-block">
                <strong>{{ $errors->first('price') }}</strong>
            </span>
        @endif
    </div>
</div>

<div class="form-group{{ $errors->has('dquota') ? ' has-error' : '' }}">
    <label class="col-sm-2 control-label" for="dquota">Default Quota <span class="text-red">*</span></label>
    <div class="col-sm-5">
        {!! Form::number('dquota', null, ['class' => 'form-control', 'placeholder' => 'Default Quota']) !!}
        @if ($errors->has('dquota'))
            <span class="help-block">
                <strong>{{ $errors->first('dquota') }}</strong>
            </span>
        @endif
    </div>
</div>


<div class="form-group{{ $errors->has('status') ? ' has-error' : '' }}">
    <label class="col-sm-2 control-label" for="role">Status <span class="text-red">*</span></label>

    <div class="col-sm-5">

        @foreach (\App\TourPrice::$status as $key => $value)
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
