@extends('admin.layouts.app')


@section('content')
    <div class="content-wrapper" style="min-height: 946px;">
        <section class="content-header">
            <h1>
                <h1>
                    Turbojet Coupons
                    <small>Edit</small>
                </h1>

            </h1>
            <ol class="breadcrumb">
                <li class="active">edit</li>
            </ol>
        </section>

        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title">Edit Turbojet Coupon</h3>
                        </div>

                        {!! Form::model($coupon, ['url' => url("admin/turbojet-coupon/$coupon->id"), 'method' => 'put', 'class' => 'form-horizontal','files'=>true]) !!}

                        <div class="box-body">

                            @include ('admin.turbojet-coupon.form')

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


