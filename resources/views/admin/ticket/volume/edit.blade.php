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
                <li><a href="{{ url('admin/ticket/ticketlist') }}"> <i class="fa fa-dashboard"></i> Ticket List</a></li>
                <li><a href="{{ url('admin/ticket/ticketlist/'.$detail.'/edit') }}"> Ticket pricegroup </a></li>
                <li><a href="{{ url('admin/ticket/'.$detail.'/pricegroup/'.$detail1.'/edit') }}"> {{ $menu }}</a></li>
                <li class="active">edit</li>
            </ol>
        </section>

        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    @if(session()->has('message'))
                        <div class="alert alert-danger">
                            {{ session()->get('message') }}
                        </div>
                    @endif
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title">Edit Ticket Volumes </h3>
                        </div>

                        {!! Form::model($volume, ['url' => url('admin/ticket/'.$detail."/".$detail1.'/volume/'.$volume->id), 'method' => 'patch', 'class' => 'form-horizontal','files'=>true]) !!}

                        <div class="box-body">

                            @include ('admin.ticket.volume.form')

                        </div>
                        <div class="box-footer">
                            <button class="btn btn-info pull-right" type="submit">Edit</button>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection


