{!! Form::hidden('redirects_to', URL::previous()) !!}

<style>
    @import url("{{ URL::asset('website/assets/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css')}}");
</style>

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

<div class="form-group{{ $errors->has('report_price_type') ? ' has-error' : '' }}">
    <label class="col-sm-2 control-label" for="report_price_type">Report Price Type <span class="text-red">*</span></label>
    <div class="col-sm-5">
        {!! Form::select('report_price_type',[''=>'please Select']+\App\TourPrice::$report_price_type,null, ['class' => 'select2 select2-hidden-accessible form-control', 'style' => 'width: 100%']) !!}
        @if ($errors->has('report_price_type'))
            <span class="help-block">
                <strong>{{ $errors->first('report_price_type') }}</strong>
            </span>
        @endif
    </div>
</div>


<div class="form-group{{ $errors->has('price') ? ' has-error' : '' }}">
    <label class="col-sm-2 control-label" for="price">Price <span class="text-red">*</span></label>
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


<div class="form-group">
    <label class="col-sm-2 control-label" for="dquota">Special Quota </label>
    <div class="col-sm-6">
        <button id="btn-add" class="btn btn-info" type="button"><i class="fa fa-plus"></i> Add</button>
    </div>
</div>

<div id="quota-container" style="padding-bottom: 20px"></div>

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

{!! Form::hidden('special-quota') !!}

<script type="text/javascript" src="{{ URL::asset('website/assets/js/moment.min.js')}}"></script>
<script type="text/javascript" src="{{ URL::asset('website/assets/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js')}}"></script>
<script>

    $(function (){
        var add_quota = function (id, quota, from, to, day_of_week) {
            var html = "<div class='form-group quota-row'>" +
                "<label class='col-sm-1 control-label'>Quota</label>" +
                "<div class='col-sm-1'><input type='text' class='quota form-control'></div>"+
                "<label class='col-sm-1 control-label'>Date</label>" +
                "<div class='col-sm-2'><input type='text'  class='from form-control'></div>" +
                "<label class='col-sm-1 control-label'>To</label>" +
                "<div class='col-sm-2'><input type='text' class='to form-control'></div>" +
                "<label class='col-sm-1 control-label'>Day</label>" +
                "<div class='col-sm-2'>" +
                "    <select class='day-of-week form-control'>" +
                "    <option value='all'>All</option>" +
                "    <option value='mon'>Monday</option>" +
                "    <option value='tue'>Tuesday</option>" +
                "    <option value='wed'>Wednesday</option>" +
                "    <option value='thu'>Thursday</option>" +
                "    <option value='fri'>Friday</option>" +
                "    <option value='sat'>Saturday</option>" +
                "    <option value='sun'>Sunday</option>" +
                "    </select>" +
                "</div>" +
                "<button type='button' class='btn btn-danger remove'><i class='fa fa-times'></i></button></div>";

            var $row = $(html);
            $row.find(".remove").click(function (){
                $row.remove();
            });

            $row.attr("data-quota-id", (id !== undefined) ? id : 0);

            if (quota !== undefined)
                $row.find(".quota").val(quota);
            if (from !== undefined)
                $row.find(".from").val(from);
            if (to !== undefined)
                $row.find(".to").val(to);
            if (day_of_week !== undefined)
                $row.find(".day-of-week").val(day_of_week);

            $row.find(".from, .to").datetimepicker({
                useCurrent: false,
                format: 'YYYY-MM-DD',
            });

            $row.appendTo("#quota-container");
        };

        $("#btn-add").click(function (){
            add_quota();
        });

        @if (isset($tourprice))
            @foreach ($tourprice->Quota as $value)
                add_quota("{{ $value->id }}", "{{ $value->quota }}", "{{ $value->date }}", "{{ $value->to }}", "{{ $value->day_of_week }}");
            @endforeach
        @endif

        $("#price-form").submit(function (){
            var quotas = [];
            $(".quota-row").each(function (){
                quotas.push({
                    id: $(this).attr("data-quota-id"),
                    quota: $(this).find(".quota").val(),
                    from: $(this).find(".from").val(),
                    to: $(this).find(".to").val(),
                    day: $(this).find(".day-of-week").val()
                });
            });
            $("input[name=special-quota]").val(JSON.stringify(quotas));
        });
    });
</script>