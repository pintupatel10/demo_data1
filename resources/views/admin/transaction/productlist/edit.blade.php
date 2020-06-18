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
                <li><a href="{{ url('admin/transaction/orderlist') }}"> <i class="fa fa-dashboard"></i>  Transaction OrderList </a></li>
                <li><a href="{{ url('admin/transaction/orderlist/'.$orderlist.'/edit') }}">  {{ $menu }} </a></li>
                <li> edit </li>
            </ol>
        </section>

        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title">ProductList Detail </h3>
                        </div>

                        {!! Form::model($productlist, ['url' => url('admin/transaction/'.$orderlist.'/productlist/'.$productlist->id), 'method' => 'patch', 'class' => 'form-horizontal','files'=>true]) !!}
                        <div class="box-body">

                            <div class="form-group{{ $errors->has('product_name') ? ' has-error' : '' }}">
                                <label class="col-sm-1 control-label" for="product_name">Product Name </label>
                                <div class="col-sm-5">
                                    {!! Form::text('product_name', null, ['class' => 'form-control', 'placeholder' => 'Product Name','disabled']) !!}
                                    @if ($errors->has('product_name'))
                                        <span class="help-block">
                <strong>{{ $errors->first('product_name') }}</strong>
            </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('subproduct_no') ? ' has-error' : '' }}">
                                <label class="col-sm-1 control-label" for="subproduct_no">Sub Product no. </label>
                                <div class="col-sm-5">
                                    {!! Form::text('subproduct_no', null, ['class' => 'form-control','disabled']) !!}
                                    @if ($errors->has('subproduct_no'))
                                        <span class="help-block">
                <strong>{{ $errors->first('subproduct_no') }}</strong>
            </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('voucher_no') ? ' has-error' : '' }}">
                                <label class="col-sm-1 control-label" for="voucher_no">Voucher No. </label>
                                    <div class="col-sm-5">
                                    {!! Form::text('voucher_no', null, ['class' => 'form-control']) !!}
                                    @if ($errors->has('voucher_no'))
                                        <span class="help-block">
                <strong>{{ $errors->first('voucher_no') }}</strong>
            </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('e_ticket_no') ? ' has-error' : '' }}">
                                <label class="col-sm-1 control-label" for="e_ticket_no">E-ticket No. </label>
                                <div class="col-sm-5">
                                    {!! Form::text('e_ticket_no', null, ['class' => 'form-control']) !!}
                                    @if ($errors->has('e_ticket_no'))
                                        <span class="help-block">
                <strong>{{ $errors->first('e_ticket_no') }}</strong>
            </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('live_ticket_no') ? ' has-error' : '' }}">
                                <label class="col-sm-1 control-label" for="live_ticket_no">Live ticket No </label>
                                <div class="col-sm-5">
                                    {!! Form::text('live_ticket_no', null, ['class' => 'form-control']) !!}
                                    @if ($errors->has('live_ticket_no'))
                                        <span class="help-block">
                <strong>{{ $errors->first('live_ticket_no') }}</strong>
            </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('post') ? ' has-error' : '' }}">
                                <label class="col-sm-1 control-label" for="post">Public/Private </label>

                                <div class="col-sm-5">

                                    @foreach (\App\TransactionProduct::$post as $key => $value)
                                        <label>
                                            {!! Form::radio('post', $key, null, ['class' => 'flat-red','disabled']) !!} <span style="margin-right: 10px">{{ $value }}</span>
                                        </label>
                                    @endforeach

                                    @if ($errors->has('post'))
                                        <span class="help-block">
             <strong>{{ $errors->first('post') }}</strong>
            </span>
                                    @endif
                                </div>
                            </div>

                            <hr>
                            <h3>Customer Information</h3>



                            <div class="form-group{{ $errors->has('date') ? ' has-error' : '' }}">
                                <label class="col-sm-1 control-label" for="date">Travel Date </label>
                                <div class="col-sm-5">
                                    {!! Form::text('date', null, ['class' => 'form-control', 'placeholder' => 'Travel Date','id'=>'datepicker']) !!}
                                    @if ($errors->has('date'))
                                        <span class="help-block">
                <strong>{{ $errors->first('date') }}</strong>
            </span>
                                    @endif
                                </div>
                            </div>



                            <div class="form-group{{ $errors->has('time') ? ' has-error' : '' }}">
                                <label class="col-sm-1 control-label" for="time">Travel Time</label>
                                <div class="col-sm-5">
                                    <div class="bootstrap-timepicker">
                                        {!! Form::text('time', null, ['class' => 'form-control timepicker','placeholder' => 'Travel Time']) !!}
                                    </div>
                                    @if ($errors->has('time'))
                                        <span class="help-block">
                <strong>{{ $errors->first('time') }}</strong>
            </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('hotel_stay') ? ' has-error' : '' }}">
                                <label class="col-sm-1 control-label" for="hotel_stay">Hotel of Stay </label>
                                <div class="col-sm-5">
                                    {!! Form::text('hotel_stay', null, ['class' => 'form-control', 'placeholder' => 'Hotel of Stay','disabled']) !!}
                                    @if ($errors->has('hotel_stay'))
                                        <span class="help-block">
                <strong>{{ $errors->first('hotel_stay') }}</strong>
            </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                                <label class="col-sm-1 control-label" for="language">Title </label>
                                <div class="col-sm-5">
                                    {!! Form::select('title[]',['please Select']+\App\TransactionProduct::$title, !empty($modes_selected)?$modes_selected:null, ['class' => 'select2 select2-hidden-accessible form-control', 'style' => 'width: 100%','disabled']) !!}
                                    @if ($errors->has('title'))
                                        <span class="help-block">
                <strong>{{ $errors->first('title') }}</strong>
            </span>
                                    @endif
                                </div>
                            </div>


                            <div class="form-group{{ $errors->has('fname') ? ' has-error' : '' }}">
                                <label class="col-sm-1 control-label" for="fname">First Name </label>
                                <div class="col-sm-5">
                                    {!! Form::text('fname', null, ['class' => 'form-control', 'placeholder' => 'First Name','disabled']) !!}
                                    @if ($errors->has('fname'))
                                        <span class="help-block">
                <strong>{{ $errors->first('fname') }}</strong>
            </span>
                                    @endif
                                </div>
                            </div>


                            <div class="form-group{{ $errors->has('lname') ? ' has-error' : '' }}">
                                <label class="col-sm-1 control-label" for="lname">Last name </label>
                                <div class="col-sm-5">
                                    {!! Form::text('lname', null, ['class' => 'form-control', 'placeholder' => 'Last name','disabled']) !!}
                                    @if ($errors->has('lname'))
                                        <span class="help-block">
                <strong>{{ $errors->first('lname') }}</strong>
            </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('passport') ? ' has-error' : '' }}">
                                <label class="col-sm-1 control-label" for="passport">Passport Nationality </label>
                                <div class="col-sm-5">
                                    {!! Form::text('passport', null, ['class' => 'form-control', 'placeholder' => 'Passport Nationality','disabled']) !!}
                                    @if ($errors->has('passport'))
                                        <span class="help-block">
                <strong>{{ $errors->first('passport') }}</strong>
            </span>
                                    @endif
                                </div>
                            </div>


                            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                <label class="col-sm-1 control-label" for="email">Email </label>

                                <div class="col-sm-5">
                                    {!! Form::text('email', null, ['class' => 'form-control', 'placeholder' => 'email','disabled']) !!}
                                    @if ($errors->has('email'))
                                        <span class="help-block">
                <strong>{{ $errors->first('email') }}</strong>
            </span>
                                    @endif
                                </div>
                            </div>


                            <div class="form-group{{ $errors->has('pnumber') ? ' has-error' : '' }}">
                                <label class="col-sm-1 control-label" for="pnumber">Primary Telephone </label>
                                <div class="col-sm-5">
                                    {!! Form::text('pnumber', null, ['class' => 'form-control', 'placeholder' => 'Primary Telephone','disabled']) !!}
                                    @if ($errors->has('pnumber'))
                                        <span class="help-block">
                <strong>{{ $errors->first('pnumber') }}</strong>
            </span>
                                    @endif
                                </div>
                            </div>


                            <div class="form-group{{ $errors->has('promocode') ? ' has-error' : '' }}">
                                <label class="col-sm-1 control-label" for="promocode">Promo Code </label>
                                <div class="col-sm-5">
                                    {!! Form::text('promocode', null, ['class' => 'form-control', 'placeholder' => 'Promo Code','disabled']) !!}
                                    @if ($errors->has('promocode'))
                                        <span class="help-block">
                <strong>{{ $errors->first('promocode') }}</strong>
            </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('remark') ? ' has-error' : '' }}">
                                <label class="col-sm-1 control-label" for="promocode">Remarks </label>
                                <div class="col-sm-5">
                                    {!! Form::textarea('remark', null, ['class' => 'form-control', 'placeholder' => '','disabled']) !!}
                                    @if ($errors->has('remark'))
                                        <span class="help-block">
                <strong>{{ $errors->first('remark') }}</strong>
            </span>
                                    @endif
                                </div>
                            </div>

                            <hr>
                            <h3>Price Table</h3>

                            <?php $total= 0;?>
                            @foreach($price as $ps)

                                <div class="form-group{{ $errors->has('remark') ? ' has-error' : '' }}">
                                    <label class="col-sm-1 control-label">{{$ps->title}} </label>
                                        <label class="col-sm-1 control-label">  {{$ps->qty}} </label>
                                        <label class="col-sm-1 control-label">  X HKD {{$ps->price}} </label>
                                    <?php $total= $total + ($ps->qty * $ps->price); ?>
                                </div>

                            @endforeach

                            <div class="form-group{{ $errors->has('remark') ? ' has-error' : '' }}">
                                <label class="col-sm-1 control-label">  Total : </label>
                                <label class="col-sm-2 control-label">   HKD {{$total}} </label>
                            </div>


                            <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }}">
                                <label class="col-sm-1 control-label" for="role">Status </label>

                                <div class="col-sm-5">

                                    @foreach (\App\TransactionProduct::$status as $key => $value)
                                        <label>
                                            {!! Form::radio('status', $key, null, ['class' => 'flat-red']) !!} <span style="margin-right: 10px;">{{ $value }}</span>
                                        </label>
                                    @endforeach

                                    @if ($errors->has('status'))
                                        <span class="help-block">
             <strong>{{ $errors->first('status') }}</strong>
            </span>
                                    @endif
                                </div>
                            </div>

                        </div>


                        @if($productlist['status'] != 'Complete')
                        <div class="box-footer">
                            <button class="btn btn-info pull-right" type="submit">Edit</button>
                        </div>
                        @endif
                        {!! Form::close() !!}
                        @include('admin.transaction.productlist.changehistory')
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

