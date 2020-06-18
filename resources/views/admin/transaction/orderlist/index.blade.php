@extends('admin.layouts.app')
@section('content')

    <div class="content-wrapper">

        <section class="content" style="min-height: 197px; !important;">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-info">

                        <div class="box-header with-border">
                            <h3 class="box-title">Filter </h3>
                        </div>

                        {!! Form::open(['url' => url('admin/transaction/orderlist'), 'method' => 'get', 'class' => 'form-horizontal']) !!}
                        <div class="box-body">
                            <div class="form-group{{ $errors->has('post') ? ' has-error' : '' }}">
                                <label class="col-sm-1 control-label" for="post">Public/Private </label>
                                <div class="col-sm-5">
                                    {!! Form::select('post',\App\Transactionorder::$post,isset($filter)?$filter:null, ['class' => 'form-control', 'style' => 'width: 100%']) !!}
                                </div>
                            </div>
                            <button class="btn btn-info pull" type="submit">Submit</button>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </section>

        <section class="content-header">
            <h1>
                Order record
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ url('admin/transaction/orderlist') }}"> <i class="fa fa-dashboard"></i>  {{ $menu }} </a></li>
            </ol>

            <br>
            @include ('admin.error')
            <div class="box">

                <div class="box-body table-responsive">
                    {{ Form::open(array('url' => array('admin/transaction/orderlist/update_display_order'), 'method' => 'post','style'=>'display:inline')) }}
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>Edit</th>
                            <th>Product Name</th>
                            <th>Order No.</th>
                            <th>Bank Reference No.</th>
                            <th>Total Amount</th>
                            <th>Reference No.</th>
                            <th>Promo Code</th>
                            <th>Public/Private</th>
                            <th>Status</th>
                            <th>Delete</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $count = 0;?>


                        @foreach ($orderlist as $list)

                            <?php $count++; ?>
                            <?php  $product = App\Transactionorder::where('id',$list['orderid'])->get();?>
                            @foreach($product as $list1)
                                <input type="hidden" name="pid<?php echo $count;?>" id="pid<?php echo $count;?>" value="{{ $list['id'] }}" />
                                <tr @if($list['orderid'] % 2 == 0 ) style="background-color: white;" @else style="background-color: rgba(0, 153, 255, 0.137254901960784);" @endif>
                                    <td align="center">
                                        <a href="{{ route('admin.transaction.orderlist.edit', $list->orderid) }}"> <button class="btn btn-info" type="button"><i class="fa fa-edit"></i></button></a>
                                    </td>
                                    <td>{{ $list['product_name'] }}</td>
                                    <td>{{ $list1['order_no'] }}</td>
                                    <td>{{ $list1['bank_reference'] }}</td>
                                    <td>HKD {{ $list1['total_amount'] }}</td>
                                    <td>{{ $list1['reference_no'] }}</td>
                                    <td>{{ $list['promocode'] }}</td>
                                    <td>{{ $list['post'] }}</td>

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
                                            @if($list['status'] == 'Complete')
                                                <span class="label label-info">Complete</span>
                                            @endif
                                    </td>

                                    <td align="center">
                                        <button class="btn btn-danger" type="button" data-toggle="modal" data-target="#myModal{{$list->orderid}}"><i class="fa fa-trash"></i></button>
                                    </td>
                                </tr>
                                <div id="myModal{{$list->orderid}}" class="fade modal modal-danger" role="dialog" >
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
                                                <a href="{{ url('admin/transaction/orderlist/'.$list1['id'].'/destroy') }}" data-method="delete" name="delete_item">
                                                    <button type="button" class="btn btn-outline">Delete</button>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
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