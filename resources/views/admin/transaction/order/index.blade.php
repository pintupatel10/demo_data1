@extends('admin.layouts.app')
@section('content')

    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                Order List
            </h1>
            <ol class="breadcrumb">
                <li> <i class="fa fa-dashboard"></i> Transaction </li>
                <li class="active">Order List</li>
            </ol>

            <br>
            @include ('admin.error')
            <div class="box">

                <div class="box-body table-responsive">
                    {{ Form::open(array('url' => array('admin/transaction/order/update_display_order'), 'method' => 'post','style'=>'display:inline')) }}
                    <table id="" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>Edit</th>
                            <th>Order Id</th>
                            <th>Order No.</th>
                            <th>Bank Reference No.</th>
                            <th>Purchase Date Time</th>
                            <th>Total Amount</th>
                            <th>Order Status</th>
                            <th>Products</th>
                            <th>Delete</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $count = 0;?>
                        @foreach ($order as $list)
                            <?php $count++; ?>
                            <input type="hidden" name="pid<?php echo $count;?>" id="pid<?php echo $count;?>" value="{{ $list['id'] }}" />
                            <tr>
                                <td align="center">
                                        <a href="{{ route('admin.transaction.order.edit', $list->id) }}"> <button class="btn btn-info" type="button"><i class="fa fa-edit"></i></button></a>
                                </td>
                                <td>{{ $list['id'] }}</td>
                                <td>{{ $list['order_no'] }}</td>
                                <td>{{ $list['bank_reference'] }}</td>
                                <td>{{ $list['purchase_date'] }}</td>
                                <td>{{ $list['total_amount'] }}</td>
                                <td>{{ $list['order_status'] }}</td>
                                <td>{{ $list['products'] }} <button class="btn btn-info" type="button" onclick="toggle_visibility('{{$list['id']}}')">MORE</button></td>
                                <td align="center">
                                    <button class="btn btn-danger" type="button" data-toggle="modal" data-target="#myModal{{$list['id']}}"><i class="fa fa-trash"></i></button>
                                </td>
                            </tr>
                            <div id="myModal{{$list['id']}}" class="fade modal modal-danger" role="dialog" >
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span></button>
                                            <h4 class="modal-title">Delete Order List</h4>
                                        </div>
                                        <div class="modal-body">
                                            <p>Are you sure you want to delete this Order List ?</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Close</button>
                                            <a href="{{ route('admin.transaction.order.destroy',$list['id']) }}" data-method="delete" name="delete_item">
                                                <button type="button" class="btn btn-outline">Delete</button>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <tr>
                                <td colspan="9">
                            {{--more--}}
                                    <?php  $product = App\Transactionproduct::where('orderid',$list['id'])->get();?>
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
                                                <td>{{ $list['total'] }}</td>
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


                            {{--end more--}}
                        @endforeach
                        </tbody>
                    </table>
                    <input type="hidden" name="count" id="count" value="<?php echo $count;?>" />
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