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
                <li> <a href="{{ url('admin/transaction/customer/'.$detail.'/edit') }}"> Transaction </a></li>
                <li> edit </li>
            </ol>
        </section>

        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box" style="border-top: 3px solid rgba(0, 191, 241, 1);">
                        <div class="box-header with-border">
                            <h3 class="box-title">View Customer Purchase record </h3>
                        </div>
                        <div class="box-body">
                            <table class="table table-bordered" style="width: 50%;">
                                <tbody>

                                <tr>
                                    <th>Id</th>
                                    <td>{{$order->id}}</td>
                                </tr>
                                <tr>
                                    <th>Customer Name</th>
                                    <td>{{$order->name}}</td>
                                </tr>
                                <tr>
                                    <th>Phone no.</th>
                                    <td>{{$order->phone}}</td>
                                </tr>
                                <tr>
                                    <th>Email address</th>
                                    <td>{{$order->email}}</td>
                                </tr>

                                </tbody></table>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box" style="border-top: 3px solid rgba(0, 191, 241, 1);">
                        <div class="box-header with-border">
                            <h3 class="box-title">Order Information</h3>
                        </div>
                        @foreach ($product as $list)
                            {!! Form::model($order,['url' => url('admin/transaction/'.$detail.'/order/'.$list->id), 'method' => 'patch', 'class' => 'form-horizontal','files'=>true]) !!}
                            {{--{!! Form::open(['url' => url('admin/transaction/'.$detail.'/order/'.$list->id), 'method' => 'patch', 'class' => 'form-horizontal','files'=>'true']) !!}--}}
                        <div class="box-body" style="box-sizing: border-box;margin-top: 15px;border: solid rgba(0, 0, 0, 0.2) 1px;width: 50%;margin-left: 15px;border-left: 5px solid #00bff1;">
                            <div class="col-sm-10">
                            <a href="{{ url('admin/transaction/'.$detail."/".$order->id.'/product/'.$list->id.'/edit')}}">
                                <table>
                                    <h4>{{$list->product_name}}
                                        @if($list['status'] == 'Confirmed')
                                                <span class="label label-success">Confirmed</span>
                                        @endif
                                        @if($list['status'] == 'Pending')
                                            <span class="label label-warning">Pending</span>
                                        @endif
                                        @if($list['status'] == 'Cancelled')
                                                <span class="label label-danger">Cancelled</span>
                                        @endif
                                    </h4>
                                    <tr>
                                        <td>HKD {{$list->total}}</td>
                                    </tr>
                                    <tr>
                                        <th>Reference No:</th>
                                        <td>{{$list->reference_no}}</td>
                                    </tr>
                                    <tr>
                                        <th>Tour Date:</th>
                                        <td>{{$list->date}}</td>
                                    </tr>
                                    @foreach($price as $ps)
                                        <tr>
                                            <th>{{$ps->title}}:</th>
                                            <td>{{$ps->qty}} x HKD {{$ps->price}}</td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <th>Promo Code:</th>
                                        <td>{{$list->promocode}}</td>
                                    </tr>
                                    <tr>
                                        <th>Public/Private:</th>
                                        <td>{{$list->post}}</td>
                                    </tr>
                                </table>
                            </a>
</div>
                            <div style="float: right;margin-right: -150px;margin-top: 15px;">
                                <button name="status" type="submit" style="width: 64px;background-color: transparent;border-color: #5bc0de;" value="Pending">Settle</button>
                            </div>

                            <div style="float: right;margin-right: -150px;margin-top: 65px;">

                                <button style="background-color: transparent;border-color: #5cb85c;" type="button" data-toggle="modal" data-target="#myModal{{$list['id']}}">Confirm</button>

                                {{--<button name="status" type="submit" style="background-color: transparent;border-color: #5cb85c;"  value="Confirmed">Confirm</button>--}}
                            </div>

                            <div style="float: right;margin-right: -150px;margin-top: 115px;">
                                <button name="status" type="submit" style="width: 63px;background-color: transparent;border-color: #d9534f;" value="Cancelled">Cancel</button>
                            </div>

                        </div>
                            <div id="myModal{{$list['id']}}" class="modal" role="dialog">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">×</span></button>
                                        </div>
                                        <div class="modal-body">
                                        <h2>Confirmation email</h2>
                                       @include('admin.transaction.order.email')
                                         </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancel</button>
                                            <button type="submit" value="Confirmed" name="status" class="btn btn-primary">Send</button>
                                        </div>
                                    </div>

                                </div>

                            </div>
                            {!! Form::close() !!}

                        @endforeach
                        <br>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

