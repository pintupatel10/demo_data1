@extends('admin.layouts.app')

@section('content')
    <style>
        @import url("{{ URL::asset('website/assets/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css')}}");
    </style>

    <div class="content-wrapper" style="min-height: 946px;">
        <section class="content-header">
            <h1>
                <h1>
                    {{ $menu }}
                </h1>

            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ url('admin/report') }}"> <i class="fa fa-dashboard"></i> </a></li>
                <li class="active">Report</li>
            </ol>
        </section>

        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    @if(Session::has('message'))
                    <div class="alert alert-success alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            {{ Session::get('message') }}
                    </div>
                    @endif

                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title">Export</h3>
                        </div>
                        {!! Form::open(['url' => url('admin/report/export'), 'class' => 'form-horizontal']) !!}
                        <div class="box-body">

                            <div class="form-group{{ $errors->has('export_order_report') ? ' has-error' : '' }}">
                                <label class="col-sm-2 control-label" for="export_order_report">Report Type</label>
                                <div class="col-sm-4">
                                    {!! Form::select('type', $reports, null, ['class' => 'select2 select2-hidden-accessible form-control', 'style' => 'width: 100%']) !!}

                                    @if ($errors->has('type'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('type') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div id="panel-multiple">
                                <div class="form-group {{ $errors->has('from') ? ' has-error' : '' }}">
                                    <label class="col-sm-2 control-label" for="from">From <span class="text-red">*</span></label>
                                    <div class="col-sm-4">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                            {!! Form::text('from', null, ['class' => 'form-control', 'data-provider'=>'datepicker']) !!}
                                        </div>

                                        @if ($errors->has('from'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('from') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group {{ $errors->has('to') ? ' has-error' : '' }}">
                                    <label class="col-sm-2 control-label" for="to">To <span class="text-red">*</span></label>
                                    <div class="col-sm-4">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                            {!! Form::text('to', null, ['class' => 'form-control', 'data-provider'=>'datepicker']) !!}
                                        </div>
                                        @if ($errors->has('to'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('to') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div id="panel-single" class="form-group">
                                <div class="{{ $errors->has('date') ? ' has-error' : '' }}">
                                    <label class="col-sm-2 control-label" for="from">Date <span class="text-red">*</span></label>
                                    <div class="col-sm-4">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                            {!! Form::text('date', null, ['class' => 'form-control', 'data-provider'=>'datepicker']) !!}
                                        </div>
                                        @if ($errors->has('date'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('date') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>


                        </div>
                        <div class="box-footer">
                            <button class="btn btn-info pull-right" type="submit"><i class="fa fa-download"></i> Export</button>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@section("jquery")
    <script type="text/javascript" src="{{ URL::asset('website/assets/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js')}}"></script>
    <script type="text/javascript">
        $(function (){
            $("input[data-provider=datepicker]").datetimepicker({
                useCurrent: false,
                format: 'YYYY-MM-DD',
            });

            var reload_inputs = function (){
                var type = $("select[name=type]").val();
                var panel_type = "single";

                if (type == "{{ \App\Http\Controllers\admin\ReportController::REPORT_BANK }}")
                    panel_type = "multiple";
                else if (type == "{{ \App\Http\Controllers\admin\ReportController::REPORT_BOOKING_REMINDER }}")
                    panel_type = "none";
                else if (type == "{{ \App\Http\Controllers\admin\ReportController::REPORT_RESERVATION }}")
                    panel_type = "multiple";

                $("#panel-single").hide();
                $("#panel-multiple").hide();

                if (panel_type == "single")
                    $("#panel-single").show();
                if (panel_type == "multiple")
                    $("#panel-multiple").show();
            };

            reload_inputs();

            $("select[name=type]").change(function (){
                reload_inputs();
            });
        });
    </script>
@endsection