@extends('admin.layouts.app')


@section('content')
    <div class="content-wrapper" style="min-height: 946px;">
        <section class="content-header">
            <h1>
                <h1>
                    Disneyland Tickets
                    <small>Add</small>
                </h1>

            </h1>
            <ol class="breadcrumb">
                <li class="active">Add</li>
            </ol>
        </section>

        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title">Add Disneyland Ticket </h3>
                        </div>
                        {!! Form::open(['url' => url("admin/disneyland-ticket/$price_type/$price_id"), 'class' => 'form-horizontal','files'=>true]) !!}
                        <div class="box-body">

                            @include ('admin.disneyland-ticket.form')

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



