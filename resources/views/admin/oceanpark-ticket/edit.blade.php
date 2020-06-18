@extends('admin.layouts.app')


@section('content')
    <div class="content-wrapper" style="min-height: 946px;">
        <section class="content-header">
            <h1>
                <h1>
                    OceanPark Tickets
                    <small>Edit</small>
                </h1>

            </h1>
            <ol class="breadcrumb">
                <li class="active">edit</li>
            </ol>
        </section>

        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title">Edit OceanPark Ticket </h3>
                        </div>

                        {!! Form::model($ticket, ['url' => url("admin/oceanpark-ticket/$price_type/$price_id/$ticket->id"), 'method' => 'put', 'class' => 'form-horizontal','files'=>true]) !!}

                        <div class="box-body">

                            @include ('admin.oceanpark-ticket.form')

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


