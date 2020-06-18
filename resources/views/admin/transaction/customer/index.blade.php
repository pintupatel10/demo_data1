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

                        {!! Form::open(['url' => url('admin/transaction/customer'), 'method' => 'get', 'class' => 'form-horizontal']) !!}

                        <div class="box-body">

                            <div class="form-group">
                                <label class="col-sm-1 control-label" for="date">From </label>
                                <div class="col-sm-2">
                                    {!! Form::text('date', null, ['class' => 'form-control', 'placeholder' => 'From','id'=>'datepicker']) !!}
                                </div>

                                <label class="col-sm-1 control-label" for="to">To </label>
                                <div class="col-sm-2">
                                    {!! Form::text('to', null, ['class' => 'form-control', 'placeholder' => 'to','id'=>'birthdate']) !!}
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
                Order List
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ url('admin/transaction/customer') }}"> <i class="fa fa-dashboard"></i>  {{ $menu }} </a></li>
            </ol>

            <br>
            @include ('admin.error')
            <div class="box">

                <div class="box-body table-responsive">
                    {{ Form::open(array('url' => array('admin/transaction/order/update_display_order'), 'method' => 'post','style'=>'display:inline')) }}
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>Order Id</th>
                            <th>Order No.</th>
                            <th>Bank Reference No.</th>
                            <th>Purchase Date Time</th>
                            <th>Total Amount</th>
                            <th>Purchase record</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $count = 0;?>
                        @foreach ($order as $list)
                            <?php $count++; ?>
                            <input type="hidden" name="pid<?php echo $count;?>" id="pid<?php echo $count;?>" value="{{ $list['id'] }}" />
                            <tr>
                                <td>{{ $list['id'] }}</td>
                                <td>{{ $list['order_no'] }}</td>
                                <td>{{ $list['bank_reference'] }}</td>
                                <td>{{ $list['purchase_date'] }}</td>
                                <td>HKD {{ $list['total_amount'] }}</td>
                                <td align="center">
                                    <a href="{{ route('admin.transaction.customer.edit', $list->id) }}"> <button class="btn btn-info" type="button">Detail</button></a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <input type="hidden" name="count" id="count" value="<?php echo $count;?>" />
                    {{ Form::close() }}
                </div>

            </div>

        </section>

        {{--<section class="content-header">--}}
            {{--<h1>--}}
              {{--Customer Order record--}}
            {{--</h1>--}}
            {{--<ol class="breadcrumb">--}}
                {{--<li><a href="#"><i class="fa fa-dashboard"></i>Transaction</a></li>--}}
                {{--<li class="active">Customer Order record</li>--}}
            {{--</ol>--}}

            {{--<br>--}}
            {{--@include ('admin.error')--}}
            {{--<div class="box">--}}

                {{--<div class="box-body table-responsive">--}}
                    {{--{{ Form::open(array('url' => array('admin/transaction/customer/update_display_order'), 'method' => 'post','style'=>'display:inline')) }}--}}
                    {{--<table id="example1" class="table table-bordered table-striped">--}}
                        {{--<thead>--}}
                        {{--<tr>--}}
                            {{--<th>Id</th>--}}
                            {{--<th>Customer Name</th>--}}
                            {{--<th>Phone Number</th>--}}
                            {{--<th>Email address</th>--}}
                            {{--<th>Purchase record</th>--}}
                        {{--</tr>--}}
                        {{--</thead>--}}
                        {{--<tbody>--}}

                        {{--@foreach ($customer as $list)--}}
                            {{--<tr>--}}

                                {{--<td>{{ $list['id'] }}</td>--}}
                                {{--<td>{{ $list['name'] }}</td>--}}
                                {{--<td>{{ $list['phone'] }}</td>--}}
                                {{--<td>{{ $list['email'] }}</td>--}}

                                {{--<td align="center">--}}
                                    {{--<a href="{{ route('admin.transaction.customer.edit', $list->id) }}"> <button class="btn btn-info" type="button">Detail</button></a>--}}
                                {{--</td>--}}

                            {{--</tr>--}}

                        {{--@endforeach--}}
                        {{--</tbody>--}}
                    {{--</table>--}}
                    {{--{{ Form::close() }}--}}


                {{--</div>--}}

            {{--</div>--}}

        {{--</section>--}}

    </div>
@endsection