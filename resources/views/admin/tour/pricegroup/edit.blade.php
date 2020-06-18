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
                <li><a href="{{ url('admin/tour/tourlist') }}"> <i class="fa fa-dashboard"></i>  Tour</a></li>
                <li><a href="{{ url('admin/tour/tourlist/'.$detail.'/edit') }}">  {{ $menu }} </a></li>
                <li class="active">edit</li>
            </ol>
        </section>

        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    @include ('admin.error')


                    {!! Form::model($pricegroup, ['url' => url('admin/tour/'.$detail.'/pricegroup/'.$pricegroup->id), 'method' => 'patch', 'class' => 'form-horizontal','files'=>true]) !!}
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title">Edit Tour Pricegroup </h3>
                        </div>

                        <div class="box-body">
                            @include ('admin.tour.pricegroup.form')
                        </div>
                    </div>

                    @if ($pricegroup->TourList->isTurbojetType())
                        <div class="box box-info">
                            <div class="box-header with-border">
                                <h3 class="box-title">Turbojet Ticket</h3>
                            </div>

                            <div class="box-body">
                                @include('admin.transportation.pricegroup.turbojet-ticket')
                            </div>

                            <div class="box-footer text-right">
                                <button class="btn btn-info pull-right" type="submit">Edit</button>
                            </div>
                        </div>


                    @endif
                    {!! Form::close() !!}


                    <div class="box box-success">
                        <div class="box-header with-border">
                            <h3 class="box-title">Turbojet Timetable</h3>
                        </div>

                        <div class="box-body">
                            @if (!$pricegroup->turbojet_ticket)
                                <div class="alert alert-warning">
                                    Please fill in the detail of Turbojet Ticket first.
                                </div>
                            @else
                                @include('admin.tour.pricegroup.turbojet-timetable')
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection


