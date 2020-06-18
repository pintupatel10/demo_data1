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
                <li><a href="{{ url('admin/transaction/orderlist') }}"> <i class="fa fa-dashboard"></i>  {{ $menu }} </a></li>
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

                        {!! Form::model($orderlist,['url' => url('admin/transaction/orderlist/'.$orderlist->id.'/edit'), 'method' => 'post', 'class' => 'form-horizontal','files'=>true]) !!}
                        <div class="box-body">
                            <table class="table table-bordered" style="width: 50%;">
                                <tbody>

                                <tr>
                                    <th>Order Id</th>
                                    <td>{{$orderlist->id}}</td>
                                </tr>
                                <tr>
                                    <th>Order No.</th>
                                    <td>{{$orderlist->order_no}}</td>
                                </tr>
                                <tr>
                                    <th>Reference No </th>
                                    <td>
                                        {!! Form::text('reference_no', null, ['class' => 'form-control']) !!}
                                    </td>
                                </tr>
                                <tr>
                                    <th>Purchase Date</th>
                                    <td>{{$orderlist->purchase_date}}</td>
                                </tr>
                                <tr>
                                    <th>Total amount</th>
                                    <td>HKD {{$orderlist->total_amount}}</td>
                                </tr>

                                <?php $c1=0; ?>
                                @foreach ($product as $list)
                                    <?php
                                    if($list['status'] == 'Confirmed'){
                                        $c1++;
                                    };
                                    ?>
                                @endforeach
                                <tr>
                                    <th>Order Status</th>
                                    <?php $c=count($product);?>
                                    <td>Confirmed {{ $c1 }}/{{ $c }}</td>
                                </tr>

                                </tbody></table>
                        </div>
                        <div class="box-footer">
                            <button class="btn btn-info pull-right" type="submit">Edit</button>
                        </div>
                        {!! Form::close() !!}
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
                            {!! Form::model($orderlist,['url' => url('admin/transaction/orderlist/'.$list->id), 'method' => 'patch', 'class' => 'form-horizontal','files'=>true]) !!}

                        <div class="box-body" style="box-sizing: border-box;margin-top: 15px;border: solid rgba(0, 0, 0, 0.2) 1px;width: 50%;margin-left: 15px;border-left: 5px solid #00bff1;">
                            <div class="col-sm-10">

                                {{--@if($list['status'] == 'Complete')--}}
                                    {{--<table>--}}
                                        {{--<h4>{{$list->product_name}}--}}
                                            {{--@if($list['status'] == 'Confirmed')--}}
                                                {{--<span class="label label-success" id='P'>Confirmed</span>--}}
                                            {{--@endif--}}
                                            {{--@if($list['status'] == 'Pending')--}}
                                                {{--<span class="label label-warning" id='P'>Pending</span>--}}
                                            {{--@endif--}}
                                            {{--@if($list['status'] == 'Cancelled')--}}
                                                {{--<span class="label label-danger" id='P'>Cancelled</span>--}}
                                            {{--@endif--}}
                                            {{--@if($list['status'] == 'Complete')--}}
                                                {{--<span class="label label-info" id='P'>Complete</span>--}}
                                            {{--@endif--}}
                                        {{--</h4>--}}
                                        {{--<tr>--}}
                                            {{--<th>Sub product no.:</th>--}}
                                            {{--<td>{{$list->subproduct_no}}</td>--}}
                                        {{--</tr>--}}
                                        {{--<tr>--}}
                                            {{--<th>Total Amount:</th>--}}
                                            {{--<td>HKD {{$list->total}}</td>--}}
                                        {{--</tr>--}}
                                        {{--<tr>--}}
                                            {{--<th>Voucher No.:</th>--}}
                                            {{--<td>{{$list->voucher_no}}</td>--}}
                                        {{--</tr>--}}
                                        {{--<tr>--}}
                                            {{--<th>e-ticket No.:</th>--}}
                                            {{--<td>{{$list->e_ticket_no}}</td>--}}
                                        {{--</tr>--}}
                                        {{--<tr>--}}
                                            {{--<th>Live ticket No.:</th>--}}
                                            {{--<td>{{$list->live_ticket_no}}</td>--}}
                                        {{--</tr>--}}
                                        {{--<tr>--}}
                                            {{--<th>Tour Date:</th>--}}
                                            {{--<td>{{$list->date}}</td>--}}
                                        {{--</tr>--}}
                                        {{--<tr>--}}
                                            {{--<th>Adults:</th>--}}
                                            {{--<td>{{$list->adult}} x HKD640</td>--}}
                                        {{--</tr>--}}
                                        {{--<tr>--}}
                                            {{--<th>Children:</th>--}}
                                            {{--<td>{{$list->children}} x HKD370</td>--}}
                                        {{--</tr>--}}
                                        {{--<tr>--}}
                                            {{--<th>Promo Code:</th>--}}
                                            {{--<td>{{$list->promocode}}</td>--}}
                                        {{--</tr>--}}
                                        {{--<tr>--}}
                                            {{--<th>Public/Private:</th>--}}
                                            {{--<td>{{$list->post}}</td>--}}
                                        {{--</tr>--}}
                                    {{--</table>--}}
                                {{--@else--}}
                            <a href="{{ url('admin/transaction/'.$orderlist->id.'/productlist/'.$list->id.'/edit')}}">
                                <table>
                                    <h4>{{$list->product_name}}
                                        @if($list['status'] == 'Confirmed')
                                                <span class="label label-success" id='P'>Confirmed</span>
                                        @endif
                                        @if($list['status'] == 'Pending')
                                            <span class="label label-warning" id='P'>Pending</span>
                                        @endif
                                        @if($list['status'] == 'Cancelled')
                                                <span class="label label-danger" id='P'>Cancelled</span>
                                        @endif
                                        @if($list['status'] == 'Complete')
                                            <span class="label label-info" id='P'>Complete</span>
                                        @endif
                                    </h4>
                                    <tr>
                                        <th>Sub product no.:</th>
                                        <td>{{$list->subproduct_no}}</td>
                                    </tr>
                                    <tr>
                                        <th>Total Amount:</th>
                                        <td>HKD {{$list->total}}</td>
                                    </tr>
                                    <tr>
                                        <th>Voucher No.:</th>
                                        <td>{{$list->voucher_no}}</td>
                                    </tr>
                                    <tr>
                                        <th>e-ticket No.:</th>
                                        <td>{{$list->e_ticket_no}}</td>
                                    </tr>
                                    <tr>
                                        <th>Live ticket No.:</th>
                                        <td>{{$list->live_ticket_no}}</td>
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
                                {{--@endif--}}
                </div>

                            <div style="float: right;margin-right: -150px;margin-top: 15px;">
                                <button name="status" type="submit" style="width: 70px;background-color: transparent;border-color: #5bc0de;" value="Complete">Complete</button>
                            </div>

                            <div style="float: right;margin-right: -150px;margin-top: 65px;">
                                <button style="width: 70px;background-color: transparent;border-color: #5cb85c;" type="button" data-toggle="modal" data-target="#myModal{{$list['id']}}">Confirm</button>
                            </div>

                            <div style="float: right;margin-right: -150px;margin-top: 115px;">
                                <button name="status" type="submit" style="width: 70px;background-color: transparent;border-color: #d9534f;" value="Cancelled">Cancel</button>
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
                                            @include('admin.transaction.orderlist.email')
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
{{--<script>--}}
    {{--function changeText()--}}
    {{--{--}}
        {{--document.getElementById('P').innerHTML = '<span class="label label-success">Confirmed</span>';--}}
    {{--}--}}
    {{--function changeText1()--}}
    {{--{--}}
        {{--document.getElementById('P').innerHTML = '<span class="label label-warning">Pending</span>';--}}
    {{--}--}}
    {{--function changeText2()--}}
    {{--{--}}
        {{--document.getElementById('P').innerHTML = '<span class="label label-danger">Cancelled</span>';--}}
    {{--}--}}
{{--</script>--}}

<script src="{{ URL::asset('assets/jquery.js')}}"></script>
<link rel="stylesheet" href="{{ URL::asset('assets/pk/ladda-theme.min.css')}}">
<script src="{{ URL::asset('assets/pk/sp.min.js')}}"></script>
<script src="{{ URL::asset('assets/pk/ladd.min.js')}}"></script>
<script>Ladda.bind( 'input[type=submit]' );</script>
<script type="text/javascript">
    $(document).ready(function() {
        $('.assign').click(function () {

            var user_id = $(this).attr('uid');
            var l = Ladda.create(this);
            l.start();
            $.ajax({
                url: '{{url('api/cms/transaction/orderlist/assign')}}',
                type: "get",
                data: {'id': user_id, 'X-CSRF-Token': $('meta[name=_token]').attr('content')},
                success: function (data) {
                    l.stop();
                    $('#assign_remove_' + user_id).show();
                    $('#assign_add_' + user_id).hide();
                }
            });
        });

        $('.unassign').click(function () {
            //alert('in');
            var user_id = $(this).attr('ruid');
            var l = Ladda.create(this);
            l.start();
            $.ajax({
                url: '{{url('api/cms/transaction/orderlist/unassign')}}',
                type: "get",
                data: {'id': user_id, 'X-CSRF-Token': $('meta[name=_token]').attr('content')},
                success: function (data) {
                    l.stop();
                    $('#assign_remove_' + user_id).hide();
                    $('#assign_add_' + user_id).show();
                }
            });
        });
    });

</script>


