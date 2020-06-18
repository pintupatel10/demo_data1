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
                <li><a href="{{ url('admin/tour/tourlist') }}"> <i class="fa fa-dashboard"></i>  {{ $menu }} </a></li>
                <li class="active">edit</li>
            </ol>
        </section>

        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title">Edit Tour List </h3>
                        </div>

                        {!! Form::model($detail, ['url' => url('admin/tour/tourlist/' . $detail->id), 'method' => 'patch', 'class' => 'form-horizontal','files'=>true]) !!}

                        <div class="box-body">

                            @include ('admin.tour.tourlist.form')

                            <div class="box-footer">
                                <a href="{{ url('admin/tour/tourlist') }}" ><button class="btn btn-default" type="button">Back</button></a>
                                <button class="btn btn-info pull-right" type="submit">Edit</button>
                            </div>

                            @include('admin.tour.tourlist.highlight')
                            @include('admin.tour.tourlist.pricegroup')

                        </div>

                        {!! Form::close() !!}

                        @include('admin.tour.tourlist.checkpoint')

                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection


