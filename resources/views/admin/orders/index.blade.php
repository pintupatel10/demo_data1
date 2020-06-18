@extends('admin.layouts.app')
@section('content')

    <style>
        .order-header {
            background-color: #f9f9f9;
            display: flex;
            border: 1px solid #f4f4f4;
            font-size: 15px;
        }

        .order-header > div {
            padding: 8px;
            line-height: 1.42857143;
        }

        .order-header > div.share {
            flex: 1;
        }

        .order-body {
            padding-left: 35px;
        }

        .order-body > table {
            margin-bottom: 0;
        }

        .order-body + .order-header {
            margin-top: 20px;
        }
    </style>

    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                Order
            </h1>
            <ol class="breadcrumb">
                <li class="active"> <i class="fa fa-dashboard"></i> Order </li>
            </ol>
        </section>


        <section class="content">
            @include ('admin.error')

            {!! Form::model($filter, ['class' => 'form-horizontal', 'method' => 'get', 'url' => url('admin/order')]) !!}
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
                    <div class="form-group">
                        <div class="col-sm-2 control-label">Search Text</div>
                        <div class="col-sm-3">{!! Form::text('search', null, ['class' => 'form-control', 'placeholder' => 'Order ID / Product Title']) !!}</div>

                        <div class="col-sm-1 control-label">Type</div>
                        <div class="col-sm-4">{!! Form::select('type[]', \App\OrderProduct::$search_types, null, ['class' => 'select2 form-control', 'multiple']) !!}</div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-2 control-label">Status</div>
                        <div class="col-sm-3">{!! Form::select('status[]', \App\OrderProduct::$search_status, null, ['class' => 'select2 form-control', 'multiple']) !!}</div>

                        <div class="col-sm-1 control-label">Payment</div>
                        <div class="col-sm-4">{!! Form::select('payment[]', \App\Order::$payment_methods, null, ['class' => 'select2 form-control', 'multiple']) !!}</div>
                    </div>
                </div>

                <div class="box-footer">
                    <button class="btn btn-primary pull-right" type="submit"><i class="fa fa-search"></i> Search</button>
                </div>
            </div>
            {!! Form::close() !!}

            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Orders</h3>
                </div>
                <!-- /.box-header -->

                <div class="box-body">
                    <div id="orders-container">

                        @foreach ($orders as $order)
                            <div class="order-header">
                                <div style="width: 35px"><a href="{{ url("admin/order/$order->id") }}"><i class="fa fa-pencil-square-o"></i></a></div>
                                <div class="share">Order No: {{ $order->id }}</div>
                                <div class="share">Payment: {{ App\Order::$payment_methods[$order->{App\Order::COLUMN_PAYMENT_METHOD}] }}</div>
                                <div class="share text-right"><i class="fa fa-calendar" style="margin-right:8px"></i>{{ $order->created_at }}</div>
                            </div>
                            <div class="order-body">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th width="150">Type</th>
                                            <th width="120">Sub-Total</th>
                                            <th width="120">Tour Date</th>
                                            <th width="120">Status</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($order->products as $order_product)
                                            <tr>
                                                <td>
                                                    <a href="{{ url("admin/order/$order->id/$order_product->id") }}">
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
                        @endforeach

                        <div class="row">

                            <div class="col-md-6" style="padding-top: 25px">Showing {{ ($orders->currentPage() - 1) * $orders->perPage() + 1 }} to {{ min($orders->currentPage() * $orders->perPage(), $orders->total()) }} of {{ $orders->total() }} orders</div>

                            <div class="col-md-6 text-right">
                                <?=$orders->appends((array) $filter)->render()?>
                            </div>
                        </div>
                    </div>
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
        });
    </script>
@endsection