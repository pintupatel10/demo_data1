@extends('admin.layouts.app')


@section('content')
    <div class="content-wrapper" style="min-height: 946px;">
        <section class="content-header">
            <h1>
                <h1>
                    {{ $menu }}
                </h1>

            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ url('admin/transaction/customer') }}"> <i class="fa fa-dashboard"></i>  {{ $menu }} </a></li>
                <li> edit </li>
            </ol>
        </section>

        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box" style="border-top: 3px solid rgba(0, 191, 241, 1);">
                        <div class="box-header with-border">
                            <h3 class="box-title">View Transaction Order Detail</h3>
                        </div>
                        <div class="box-body">
                            <table class="table table-bordered" style="width: 50%;">
                                <tbody>

                                <tr>
                                    <th>Order Id</th>
                                    <td>{{$order->id}}</td>
                                </tr>
                                <tr>
                                    <th>Order No.</th>
                                    <td>{{$order->order_no}}</td>
                                </tr>
                                <tr>
                                    <th>Bank Reference No.</th>
                                    <td>{{$order->bank_reference}}</td>
                                </tr>
                                <tr>
                                    <th>Purchase Date</th>
                                    <td>{{$order->purchase_date}}</td>
                                </tr>
                                <tr>
                                    <th>Total amount</th>
                                    <td>HKD {{$order->total_amount}}</td>
                                </tr>
                                <tr>
                                    <th>Order Status</th>
                                    <td>{{$order->order_status}}</td>
                                </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>



        <section class="content-header">
                <h1>
                Customer Order record
                </h1>


                <br>
                @include ('admin.error')
                <div class="box">

                <div class="box-body table-responsive">
                {{ Form::open(array('url' => array('admin/transaction/customer/update_display_order'), 'method' => 'post','style'=>'display:inline')) }}
                <table  class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th>Edit</th>
                    <th>Id</th>
                    <th>Customer Name</th>
                    <th>Phone Number</th>
                    <th>Email address</th>
                    <th>Product</th>
                </tr>
                </thead>
                <tbody>

                @foreach ($customer as $list)
                <tr>
                    <td align="center">
                        <a href="{{ url('admin/transaction/'.$order->id.'/order/'.$list->id.'/edit') }}"> <button class="btn btn-info" type="button"><i class="fa fa-edit"></i></button></a>
                    </td>
                <td>{{ $list['id'] }}</td>
                <td>{{ $list['name'] }}</td>
                <td>{{ $list['phone'] }}</td>
                <td>{{ $list['email'] }}</td>

                <td align="center">
                    <button class="btn btn-info" type="button" onclick="toggle_visibility('{{$list['id']}}')">MORE</button>
                </td>

                </tr>

                <tr>
                    <td colspan="9">
                        {{--more--}}
                        <?php  $product = App\Transactionproduct::where('customerid',$list['id'])->get();?>
                        {{--<table  id="tbl" class="table table-bordered table-striped" style="display:none;">--}}
                        <table id="{{$list['id']}}"  class="table table-bordered table-striped" style="display:none;">
                            <thead style="background-color: rgba(51, 204, 255, 1);">
                            <tr>
                                <th>Product Name</th>
                                <th>Total Amount</th>
                                <th>Reference No.</th>
                                <th>Promo Code</th>
                                <th>Status</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($product as $list)
                                <tr>
                                    <td>{{ $list['product_name'] }}</td>
                                    <td>HKD {{ $list['total'] }}</td>
                                    <td>{{ $list['reference_no'] }}</td>
                                    <td>{{ $list['promocode'] }}</td>
                                    <td>
                                        @if($list['status'] == 'Confirmed')
                                            <span class="label label-success">Confirmed</span>
                                        @endif
                                        @if($list['status'] == 'Pending')
                                            <span class="label label-warning">Pending</span>
                                        @endif
                                        @if($list['status'] == 'Cancelled')
                                            <span class="label label-danger">Cancelled</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </td>
                </tr>

                @endforeach
                </tbody>
                </table>
                {{ Form::close() }}

                </div>
                </div>
        </section>

    </div>
@endsection

<script>
    function toggle_visibility(id) {
        var e = document.getElementById(id);
        if(e.style.display == 'table')
            e.style.display = 'none';
        else
            e.style.display = 'table';
    }
</script>


