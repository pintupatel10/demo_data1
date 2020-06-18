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
                <li> <a href="{{ url('admin/transaction/customer/'.$customer.'/edit') }}"> Transaction </a></li>
                <li> <a href="{{ url('admin/transaction/'.$customer."/order/".$order.'/edit') }}"> Product </a></li>
                <li> edit </li>
            </ol>
        </section>

        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title">Product Detail </h3>
                        </div>

                        {!! Form::model($product, ['url' => url('admin/transaction/'.$customer."/".$order.'/product/'.$product->id), 'method' => 'patch', 'class' => 'form-horizontal','files'=>true]) !!}
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

                            <div class="form-group{{ $errors->has('reference_no') ? ' has-error' : '' }}">
                                <label class="col-sm-1 control-label" for="reference_no">Reference No </label>
                                    <div class="col-sm-5">
                                    {!! Form::text('reference_no', null, ['class' => 'form-control', 'placeholder' => 'Reference No','disable']) !!}
                                    @if ($errors->has('reference_no'))
                                        <span class="help-block">
                <strong>{{ $errors->first('reference_no') }}</strong>
            </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('post') ? ' has-error' : '' }}">
                                <label class="col-sm-1 control-label" for="post">Public/Private <span class="text-red">*</span></label>

                                <div class="col-sm-5">

                                    @foreach (\App\Transactionproduct::$post as $key => $value)
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
                                        {!! Form::text('time', null, ['class' => 'form-control timepicker','placeholder' => 'Travel Time','disabled']) !!}
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
                                <label class="col-sm-1 control-label" for="language">Title <span class="text-red">*</span></label>
                                <div class="col-sm-5">
                                    {!! Form::select('title[]',['please Select']+\App\Transactionproduct::$title, !empty($modes_selected)?$modes_selected:null, ['class' => 'select2 select2-hidden-accessible form-control', 'style' => 'width: 100%','disabled']) !!}
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
                                <label class="col-sm-1 control-label" for="email">Email <span class="text-red">*</span></label>

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


                            <div class="form-group{{ $errors->has('snumber') ? ' has-error' : '' }}">
                                <label class="col-sm-1 control-label" for="snumber">Secondary Telephone </label>
                                <div class="col-sm-5">
                                    {!! Form::text('snumber', null, ['class' => 'form-control', 'placeholder' => 'Secondary Telephone','disabled']) !!}
                                    @if ($errors->has('snumber'))
                                        <span class="help-block">
                <strong>{{ $errors->first('snumber') }}</strong>
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

                            {{--<div class="form-group{{ $errors->has('adult') ? ' has-error' : '' }}">--}}
                                {{--<label class="col-sm-1 control-label" for="adult">Adult </label>--}}
                                {{--<div class="col-sm-2">--}}
                                    {{--{!! Form::text('adult', null, ['class' => 'form-control','disabled']) !!}--}}
                                    {{--@if ($errors->has('adult'))--}}
                                        {{--<span class="help-block">--}}
                {{--<strong>{{ $errors->first('adult') }}</strong>--}}
            {{--</span>--}}
                                    {{--@endif--}}
                                {{--</div>--}}
                                {{--<div class="col-sm-1">--}}
                                   {{--<h4> <span>x  </span>       <span>  HKD400</span></h4>--}}
                                {{--</div>--}}
                            {{--</div>--}}

                            {{--<div class="form-group{{ $errors->has('children') ? ' has-error' : '' }}">--}}
                                {{--<label class="col-sm-1 control-label" for="children">Children </label>--}}
                                {{--<div class="col-sm-2">--}}
                                    {{--{!! Form::text('children', null, ['class' => 'form-control','disabled']) !!}--}}
                                    {{--@if ($errors->has('children'))--}}
                                        {{--<span class="help-block">--}}
                {{--<strong>{{ $errors->first('children') }}</strong>--}}
            {{--</span>--}}
                                    {{--@endif--}}
                                {{--</div>--}}
                                {{--<div class="col-sm-1">--}}
                                    {{--<h4> <span>x  </span>       <span>  HKD600</span></h4>--}}
                                {{--</div>--}}
                            {{--</div>--}}

                            {{--<div class="form-group{{ $errors->has('total') ? ' has-error' : '' }}">--}}
                                {{--<label class="col-sm-1 control-label" for="total">Total amount </label>--}}
                                {{--<div class="col-sm-2">--}}
                                    {{--{!! Form::text('total', null, ['class' => 'form-control','disabled']) !!}--}}
                                    {{--@if ($errors->has('total'))--}}
                                        {{--<span class="help-block">--}}
                {{--<strong>{{ $errors->first('total') }}</strong>--}}
            {{--</span>--}}
                                    {{--@endif--}}
                                {{--</div>--}}
                            {{--</div>--}}
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
                                <label class="col-sm-1 control-label" for="role">Status <span class="text-red">*</span></label>

                                <div class="col-sm-5">

                                    @foreach (\App\Transactionproduct::$status as $key => $value)
                                        <label>
                                            {!! Form::radio('status', $key, null, ['class' => 'flat-red']) !!} <span style="margin-right: 10px">{{ $value }}</span>
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







                        <div class="box-footer">
                            <button class="btn btn-info pull-right" type="submit">Edit</button>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection


