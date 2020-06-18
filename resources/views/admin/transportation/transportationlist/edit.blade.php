@extends('admin.layouts.app')


@section('content')
    <div class="content-wrapper" style="min-height: 946px;">
        <section class="content-header">
            <h1>
                <h1>
                    {{ $menu }}
                    <small>Edit</small>
                </h1>

            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ url('admin/transportation/transportationlist') }}"> <i class="fa fa-dashboard"></i>  {{ $menu }} </a></li>
                <li class="active">edit</li>
            </ol>
        </section>

        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title">Edit Transportation List </h3>
                        </div>

                        {!! Form::model($detail, ['url' => url('admin/transportation/transportationlist/' . $detail->id), 'method' => 'patch', 'class' => 'form-horizontal','files'=>true]) !!}

                        <div class="box-body">
                            @include ('admin.transportation.transportationlist.form')

                            <div class="box-footer">
                                <a href="{{ url('admin/transportation/transportationlist') }}" ><button class="btn btn-default" type="button">Back</button></a>
                                <button class="btn btn-info pull-right" type="submit">Edit</button>
                            </div>

                            @include('admin.transportation.transportationlist.highlight')

                            <div style="@if($detail->transportation_type == "Contact form") display:none; @else display: block;@endif" id="stock_type1">
                                @include('admin.transportation.transportationlist.pricegroup')
                            </div>

                            <script>
                                function calltype1(val) {
                                    if (val == 'Contact form') {
                                        document.getElementById('stock_type1').style.display = "none";
                                    }
                                    else {
                                        document.getElementById('stock_type1').style.display = "block";
                                    }
                                }
                            </script>

                        </div>

                        {!! Form::close() !!}
                        @include('admin.transportation.transportationlist.checkpoint')

                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection


