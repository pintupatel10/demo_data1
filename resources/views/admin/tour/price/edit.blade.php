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
                <li><a href="{{ url('admin/tour/tourlist/'.$detail.'/edit') }}">  Tour Pricegroup </a></li>
                <li><a href="{{ url('admin/tour/'.$detail.'/pricegroup/'.$detail1.'/edit') }}">  {{ $menu }} </a></li>
                <li class="active">edit</li>
            </ol>
        </section>

        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title">Edit Tour Price </h3>
                        </div>

                        {!! Form::model($tourprice, ['id' => 'price-form', 'url' => url('admin/tour/'.$detail."/".$detail1.'/price/'.$tourprice->id), 'method' => 'patch', 'class' => 'form-horizontal','files'=>true]) !!}

                        <div class="box-body">

                            @include ('admin.tour.price.form')

                        </div>
                        <div class="box-footer">
                            <button class="btn btn-info pull-right" type="submit">Edit</button>
                        </div>
                        {!! Form::close() !!}
                    </div>

                    @if ($tourprice->TourList->isDisneylandType())
                        @include ('admin.disneyland-ticket.index', ['price' => $tourprice, 'price_type' => 'tour', 'price_id' => $tourprice->id])
                    @endif

                    @if ($tourprice->TourList->isOceanParkType())
                        @include ('admin.oceanpark-ticket.index', ['price' => $tourprice, 'price_type' => 'tour', 'price_id' => $tourprice->id])
                    @endif
                </div>
            </div>
        </section>
    </div>
@endsection


