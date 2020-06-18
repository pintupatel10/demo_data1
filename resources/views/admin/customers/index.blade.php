@extends('admin.layouts.app')
@section('content')

    <style>
    </style>

    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                Customer
            </h1>
            <ol class="breadcrumb">
                <li class="active"> <i class="fa fa-dashboard"></i> Customers </li>
            </ol>
        </section>


        <section class="content">
            @include ('admin.error')

            {!! Form::model($filter, ['class' => 'form-horizontal', 'method' => 'get', 'url' => url('admin/customer')]) !!}
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Search</h3>
                </div>

                <div class="box-body">
                    <div class="form-group">
                        <div class="col-sm-2 control-label">Order From:</div>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                {!! Form::text('start-date', null, ['class' => 'form-control date-input']) !!}
                            </div>
                        </div>

                        <div class="col-sm-1 control-label">Order To:</div>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                {!! Form::text('end-date', null, ['class' => 'form-control date-input']) !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="box-footer">
                    <button class="btn btn-primary pull-right" type="submit"><i class="fa fa-search"></i> Search</button>
                </div>
            </div>
            {!! Form::close() !!}

            <div class="box box-info">
                <div class="box-header with-border" style="padding: 12px 10px;">
                    <h3 class="box-title">Customers</h3>
                    <div class="box-tools pull-right">
                        {!! Form::open(['url' => url('admin/customer/export?' . http_build_query(Request::all()))]) !!}
                        <button type="submit" class="btn btn-info"><i class="fa fa-download"></i> Excel</button>
                        {!! Form::close() !!}
                    </div>
                </div>
                <!-- /.box-header -->

                <div class="box-body">

                    <table id="table" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>Title</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Tel</th>
                            <th>Email</th>
                            <th>Details</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($customers as $customer)
                            <tr>
                                <td>{{ $customer->{\App\OrderProduct::COLUMN_TITLE} }}</td>
                                <td>{{ $customer->{\App\OrderProduct::COLUMN_FIRST_NAME} }}</td>
                                <td>{{ $customer->{\App\OrderProduct::COLUMN_LAST_NAME} }}</td>
                                <td>{{ $customer->phone }}</td>
                                <td>{{ $customer->{\App\OrderProduct::COLUMN_EMAIL} }}</td>
                                <td><a href="{{ url('admin/customer/' . base64_encode($customer->{App\OrderProduct::COLUMN_EMAIL})) }}" class="btn btn-default">Detail</a></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
@endsection

@section("jquery")
    <script type="text/javascript" src="{{ URL::asset('website/assets/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js')}}"></script>
    <script type="text/javascript">

        $(function (){
            $(".date-input").datetimepicker({
                useCurrent: false,
                format: 'YYYY-MM-DD',
            });

            $('#table').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": false,
                "info": true,
                "autoWidth": true
            });
        });
    </script>
@endsection