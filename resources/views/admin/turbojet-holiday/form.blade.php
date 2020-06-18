<div class="form-group{{ $errors->has('date') ? ' has-error' : '' }}">
    <label class="col-sm-2 control-label" for="type">Date <span class="text-red">*</span></label>
    <div class="col-sm-3">
        {!! Form::text('date', null, ['class' => 'form-control']) !!}
        @if ($errors->has('date'))
            <span class="help-block">
                <strong>{{ $errors->first('date') }}</strong>
            </span>
        @endif
    </div>
</div>


<link rel="stylesheet" type="text/css" href="{{ URL::asset('website/assets/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css')}}">
<script type="text/javascript" src="{{ URL::asset('website/assets/js/moment.min.js')}}"></script>
<script type="text/javascript" src="{{ URL::asset('website/assets/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js')}}"></script>

<script type="text/javascript">
    $(function (){
        $("input[name=date]").datetimepicker({
            useCurrent: false,
            format: 'YYYY-MM-DD',
        });
    });
</script>