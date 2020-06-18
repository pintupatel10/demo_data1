@extends('admin.layouts.app')


@section('content')
    <div class="content-wrapper" style="min-height: 946px;">
        <section class="content-header">
            <h1>
                <h1>
                    {{ $menu }}
                    <small>Edit</small>
                </h1>

            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ url('admin/transportation/transportationlist') }}"> <i class="fa fa-dashboard"></i>  Transportation </a></li>
                <li><a href="{{ url('admin/transportation/transportationlist/'.$detail.'/edit') }}"> {{ $menu }} </a></li>
                <li class="active">edit</li>
            </ol>
        </section>

        <section class="content">

            {!! Form::model($pricegroup, ['url' => url('admin/transportation/'.$detail.'/pricegroup/'.$pricegroup->id), 'method' => 'patch', 'class' => 'form-horizontal','files'=>true]) !!}
            <div class="text-right" style="margin: 20px 0;">
                <button class="btn btn-info" type="submit">Edit</button>
            </div>

            <div class="box box-warning">
                <div class="box-header with-border">
                    <h3 class="box-title">Edit Transportation Pricegroup </h3>
                </div>

                <div class="box-body">
                    @include ('admin.transportation.pricegroup.form')
                </div>
            </div>

            @if (!$pricegroup->TransportationList->isTurbojetType())
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">Transportation Price Table</h3>
                    <div class="box-tools pull-right">
                        <a href="{{ url('admin/transportation/'.$detail."/".$pricegroup->id.'/price/create/')}}" ><button class="btn btn-info" type="button"><span class="fa fa-plus"></span></button></a>
                        <a href="" ><button class="btn btn-default" type="button"><span class="fa fa-refresh"></span></button></a>
                    </div>
                </div>

                <div class="box-body">
                    @include('admin.transportation.pricegroup.price')
                </div>
            </div>

                @if($pricegroup['time_table'] == "On")
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Transportation Time Table</h3>
                        <div class="box-tools pull-right">
                            <a href="{{ url('admin/transportation/'.$detail."/".$pricegroup->id.'/timetable/create/')}}" ><button class="btn btn-info" type="button"><span class="fa fa-plus"></span></button></a>
                            <a href="" ><button class="btn btn-default" type="button"><span class="fa fa-refresh"></span></button></a>
                        </div>
                    </div>

                    <div class="box-body">
                        @include('admin.transportation.pricegroup.inventory')
                    </div>
                </div>
                @endif

            @else
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Turbojet Ticket</h3>
                    </div>

                    <div class="box-body">
                        @include('admin.transportation.pricegroup.turbojet-ticket')
                    </div>
                </div>
            @endif


            {!! Form::close() !!}

        </section>
    </div>
@endsection


