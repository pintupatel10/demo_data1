@extends('admin.layouts.app')


@section('content')
    <div class="content-wrapper" style="min-height: 946px;">
        <section class="content-header">
            <h1>
                <h1>
                    {{ $menu }}
                    <small>Add</small>
                </h1>

            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ url('admin/ticket/ticketlist') }}"> <i class="fa fa-dashboard"></i> Ticket List</a></li>
                <li><a href="{{ url('admin/ticket/ticketlist/'.$detail.'/edit') }}"> Ticket pricegroup </a></li>
                <li><a href="{{ url('admin/ticket/'.$detail.'/pricegroup/'.$detail1.'/edit') }}"> {{ $menu }}</a></li>
                <li class="active">Add</li>
            </ol>
        </section>

        <section class="content">
            <div class="row">
                <div class="col-md-12">.
                    @if(session()->has('message'))
                        <div class="alert alert-danger">
                            {{ session()->get('message') }}
                        </div>
                    @endif
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title">ADD Ticket Volumes </h3>
                        </div>
                        {!! Form::open(['url' => url('admin/ticket/'.$detail."/".$detail1.'/volume'), 'class' => 'form-horizontal','files'=>true]) !!}
                        <div class="box-body">

                            <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}"  style="display: none;" id="stock_type3">
                                <label class="col-sm-2 control-label" for="title">Title <span class="text-red">*</span></label>
                                <div class="col-sm-5">
                                    {!! Form::select('title[]',$name, !empty($modes)?$modes:null, ['class' => 'select2 select2-hidden-accessible form-control', 'style' => 'width: 100%','multiple']) !!}
                                    @if ($errors->has('title'))
                                        <span class="help-block">
                <strong>{{ $errors->first('title') }}</strong>
            </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('title1') ? ' has-error' : '' }}" id="stock_type2">
                                <label class="col-sm-2 control-label" for="title1">Title <span class="text-red">*</span></label>
                                <div class="col-sm-5">
                                    {!! Form::select('title1',['please Select']+$name1, !empty($modes)?$modes:null, ['class' => 'select2 select2-hidden-accessible form-control', 'style' => 'width: 100%']) !!}
                                    @if ($errors->has('title1'))
                                        <span class="help-block">
                <strong>{{ $errors->first('title1') }}</strong>
            </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('type') ? ' has-error' : '' }}">
                                <label class="col-sm-2 control-label" for="type">Volume discount type </label>

                                <div class="col-sm-5">

                                    @foreach (\App\TicketVolume::$volume as $key => $value)
                                        <label>
                                            {!! Form::radio('type', $key, 1, ['onclick'=>'calltype(this.value);']) !!} <span style="margin-right: 10px">{{ $value }}</span>
                                        </label>
                                    @endforeach

                                    @if ($errors->has('type'))
                                        <span class="help-block">
             <strong>{{ $errors->first('type') }}</strong>
            </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('volume') ? ' has-error' : '' }}">
                                <label class="col-sm-2 control-label" for="volume">Volume </label>
                                <div class="col-sm-5">
                                    {!! Form::number('volume', null, ['class' => 'form-control', 'placeholder' => 'Volume']) !!}
                                    @if ($errors->has('volume'))
                                        <span class="help-block">
                <strong>{{ $errors->first('volume') }}</strong>
            </span>
                                    @endif
                                </div>
                            </div>


                            <div class="form-group{{ $errors->has('discount') ? ' has-error' : '' }}"
                                 id="stock_type">
                                <label class="col-sm-2 control-label" for="discount">Discount(Price) </label>
                                <div class="col-sm-5">
                                    {!! Form::text('discount', null, ['class' => 'form-control', 'placeholder' => 'Discount']) !!}
                                    @if ($errors->has('discount'))
                                        <span class="help-block">
                <strong>{{ $errors->first('discount') }}</strong>
            </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('discount1') ? ' has-error' : '' }}"
                                 style="display: none;" id="stock_type1">
                                <label class="col-sm-2 control-label" for="discount1">Discount(%)</label>
                                <div class="col-sm-5">
                                    {!! Form::text('discount1', null, ['class' => 'form-control', 'placeholder' => 'Discount(%)']) !!}
                                    @if ($errors->has('discount1'))
                                        <span class="help-block">
                <strong>{{ $errors->first('discount1') }}</strong>
            </span>
                                    @endif
                                </div>
                            </div>


                            <script>
                                function calltype(val) {
                                    if (val == 'Single') {
                                        document.getElementById('stock_type').style.display = "block";
                                    }
                                    else {
                                        document.getElementById('stock_type').style.display = "none";
                                    }

                                    if (val == 'Multiple') {
                                        document.getElementById('stock_type1').style.display = "block";
                                    }
                                    else {
                                        document.getElementById('stock_type1').style.display = "none";
                                    }

                                    if (val == 'Single') {
                                        document.getElementById('stock_type2').style.display = "block";
                                    }
                                    else {
                                        document.getElementById('stock_type2').style.display = "none";
                                    }

                                    if (val == 'Multiple') {
                                        document.getElementById('stock_type3').style.display = "block";
                                    }
                                    else {
                                        document.getElementById('stock_type3').style.display = "none";
                                    }
                                }
                            </script>


                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="date">Date Range </label>
                                <div class="col-sm-2">
                                    {!! Form::text('date', null, ['class' => 'form-control', 'placeholder' => 'Date Range','id'=>'datepicker']) !!}
                                </div>

                                <label class="col-sm-1 control-label" for="to">To </label>
                                <div class="col-sm-2">
                                    {!! Form::text('to', null, ['class' => 'form-control', 'placeholder' => 'to','id'=>'birthdate']) !!}
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }}">
                                <label class="col-sm-2 control-label" for="role">Status <span class="text-red">*</span></label>

                                <div class="col-sm-5">

                                    @foreach (\App\TicketVolume::$status as $key => $value)
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


                        </div>
                        <div class="box-footer">
                            <button class="btn btn-info pull-right" type="submit">Add</button>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection