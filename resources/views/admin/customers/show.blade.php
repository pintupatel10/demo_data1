@extends('admin.layouts.app')
@section('content')

    <style>
        .form-group .text {
            margin-top: 7px;
        }
    </style>

    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                Customer Info
            </h1>
            <ol class="breadcrumb">
                <li class="active"> <i class="fa fa-dashboard"></i> Customers </li>
            </ol>
        </section>


        <section class="content">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">Customer Detail</h3>
                </div>
                <!-- /.box-header -->

                <div class="box-body">

                    {!! Form::open(['class' => 'form-horizontal']) !!}

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Title</label>
                            <div class="col-sm-4 text">{{ $order_products[0]->{App\OrderProduct::COLUMN_TITLE} }}</div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Last Name</label>
                            <div class="col-sm-4 text">{{ $order_products[0]->{App\OrderProduct::COLUMN_LAST_NAME} }}</div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">First Name</label>
                            <div class="col-sm-4 text">{{ $order_products[0]->{App\OrderProduct::COLUMN_FIRST_NAME} }}</div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Tel</label>
                            <div class="col-sm-4 text">{{ $order_products[0]->phone }}</div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Email</label>
                            <div class="col-sm-4 text">{{ $order_products[0]->{App\OrderProduct::COLUMN_EMAIL} }}</div>
                        </div>

                    {!! Form::close() !!}
                </div>
            </div>

            <div class="box box-green">
                <div class="box-header with-border">
                    <h3 class="box-title">Order Histories</h3>
                </div>
                <!-- /.box-header -->

                <div class="box-body">

                    <table id="table" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>Product</th>
                            <th>Type</th>
                            <th>Sub Total</th>
                            <th>Tour Date</th>
                            <th>Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($order_products as $order_product)
                            <tr>
                                <td>
                                    <a href="{{ url("admin/order/$order_product->order_id/$order_product->id") }}">
                                        {{ $order_product->{App\OrderProduct::COLUMN_PRODUCT_PRICE_GROUP_TITLE} }}
                                    </a>
                                </td>
                                <td>
                                    {{ App\OrderProduct::$types[$order_product->{App\OrderProduct::COLUMN_TYPE}] }}
                                    @if ($order_product->{App\OrderProduct::COLUMN_IS_PRIVATE})
                                        <span class="label label-primary">Private</span>
                                    @endif
                                </td>
                                <td>HKD {{ $order_product->{App\OrderProduct::COLUMN_TOTAL_AMOUNT} }}</td>
                                <td>{{ $order_product->{App\OrderProduct::COLUMN_DATE}->format('Y-m-d') }}</td>
                                <td>
                                    @if ($order_product->{App\OrderProduct::COLUMN_STATUS} == App\OrderProduct::STATUS_IN_QUEUE)
                                        <span class="label label-default">In-Queue</span>
                                    @elseif ($order_product->{App\OrderProduct::COLUMN_STATUS} == App\OrderProduct::STATUS_PENDING)
                                        <span class="label label-warning">Pending</span>
                                    @elseif ($order_product->{App\OrderProduct::COLUMN_STATUS} == App\OrderProduct::STATUS_CONFIRM_DRAFTED)
                                        <span class="label label-primary">Confirm Drafted</span>
                                    @elseif ($order_product->{App\OrderProduct::COLUMN_STATUS} == App\OrderProduct::STATUS_CONFIRMED)
                                        <span class="label label-info">Confirmed</span>
                                    @elseif ($order_product->{App\OrderProduct::COLUMN_STATUS} == App\OrderProduct::STATUS_COMPLETED)
                                        <span class="label label-success">Completed</span>
                                    @elseif ($order_product->{App\OrderProduct::COLUMN_STATUS} == App\OrderProduct::STATUS_CANCELED)
                                        <span class="label label-danger">Cancelled</span>
                                    @endif
                                </td>
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