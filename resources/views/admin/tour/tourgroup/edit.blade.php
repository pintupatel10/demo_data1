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
                <li><a href="{{ url('admin/tour/tourgroup') }}"> <i class="fa fa-dashboard"></i>  {{ $menu }} </a></li>
                <li class="active">edit</li>
            </ol>
        </section>

        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title">Edit Tour Group </h3>
                        </div>

                        {!! Form::model($groupdetail, ['url' => url('admin/tour/tourgroup/' . $groupdetail->id), 'method' => 'patch', 'class' => 'form-horizontal','files'=>true]) !!}

                        <div class="box-body">

                            @include ('admin.tour.tourgroup.form')

                        </div>
                        <div class="box-footer">
                            <a href="{{ url('admin/tour/tourgroup') }}" ><button class="btn btn-default" type="button">Back</button></a>
                            <button class="btn btn-info pull-right" type="submit">Edit</button>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection


