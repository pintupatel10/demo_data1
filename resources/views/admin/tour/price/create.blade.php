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
                <li><a href="{{ url('admin/tour/tourlist') }}"> <i class="fa fa-dashboard"></i>  Tour</a></li>
                <li><a href="{{ url('admin/tour/tourlist/'.$detail.'/edit') }}">  Tour Pricegroup </a></li>
                <li><a href="{{ url('admin/tour/'.$detail.'/pricegroup/'.$detail1.'/edit') }}">  {{ $menu }} </a></li>
                <li class="active">Add</li>
            </ol>
        </section>

        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title">ADD Tour Price </h3>
                        </div>
                        {!! Form::open(['id' => 'price-form', 'url' => url('admin/tour/'.$detail."/".$detail1.'/price'), 'class' => 'form-horizontal','files'=>true]) !!}
                        <div class="box-body">

                            @include ('admin.tour.price.form')

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



