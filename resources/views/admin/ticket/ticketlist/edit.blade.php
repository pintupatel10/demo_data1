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
                <li><a href="{{ url('admin/ticket/ticketlist') }}"> <i class="fa fa-dashboard"></i>  {{ $menu }} </a></li>
                <li class="active">edit</li>
            </ol>
        </section>

        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title">Edit Ticket List </h3>
                        </div>

                        {!! Form::model($detail, ['url' => url('admin/ticket/ticketlist/' . $detail->id), 'method' => 'patch', 'class' => 'form-horizontal','files'=>true]) !!}

                        <div class="box-body">

                            @include ('admin.ticket.ticketlist.form')

                            <div class="box-footer">
                                <a href="{{ url('admin/ticket/ticketlist') }}" ><button class="btn btn-default" type="button">Back</button></a>
                                <button class="btn btn-info pull-right" type="submit">Edit</button>
                            </div>

                            @include('admin.ticket.ticketlist.highlight')
                            @include('admin.ticket.ticketlist.pricegroup')

                        </div>

                        {!! Form::close() !!}

                        @include('admin.ticket.ticketlist.checkpoint')

                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection


