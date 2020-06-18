
<div class="form-group{{ $errors->has('type') ? ' has-error' : '' }}">
    <label class="col-sm-1 control-label" for="type">Mail Type <span class="text-red">*</span></label>
    <div class="col-sm-5">
        {!! Form::select('type[]',['please Select']+\App\Emailset::$type, !empty($modes_selected)?$modes_selected:null, ['class' => 'select2 select2-hidden-accessible form-control', 'style' => 'width: 100%']) !!}
        @if ($errors->has('type'))
            <span class="help-block">
                <strong>{{ $errors->first('type') }}</strong>
            </span>
        @endif
    </div>
</div>

@include('admin.site.emailset.email')

<div class="form-group{{ $errors->has('status') ? ' has-error' : '' }}">
    <label class="col-sm-1 control-label" for="role">Status <span class="text-red">*</span></label>

    <div class="col-sm-5">

        @foreach (\App\Emailset::$status as $key => $value)
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
