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
                <li><a href="{{ url('admin/privatetour/privatetourlist') }}"> <i class="fa fa-dashboard"></i>  Private Tour List</a></li>
                <li><a href="{{ url('admin/privatetour/privatetourlist/'.$detail.'/edit') }}"> {{ $menu }} </a></li>
                <li class="active">edit</li>
            </ol>
        </section>

        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title">Edit Private Tour Checkpoint </h3>
                        </div>

                        {!! Form::model($checkpoint, ['url' => url('admin/privatetour/'.$detail.'/checkpoint/'.$checkpoint->id), 'method' => 'patch', 'class' => 'form-horizontal','files'=>true]) !!}
                        <div class="box-body">

                            @include ('admin.privatetour.checkpoint.form')

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


