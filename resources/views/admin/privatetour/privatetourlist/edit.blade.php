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
                <li><a href="{{ url('admin/privatetour/privatetourlist') }}"> <i class="fa fa-dashboard"></i>  {{ $menu }} </a></li>
                <li class="active">edit</li>
            </ol>
        </section>

        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title">Edit Private Tour List </h3>
                        </div>

                        {!! Form::model($detail, ['url' => url('admin/privatetour/privatetourlist/' . $detail->id), 'method' => 'patch', 'class' => 'form-horizontal','files'=>true]) !!}

                        <div class="box-body">

                            <div class="form-group{{ $errors->has('link') ? ' has-error' : '' }}">
                                <label class="col-sm-2 control-label" for="link">Link </label>
                                <div class="col-sm-4">
                                    <?php $a=url('privatetour/privatetourlist/'.$detail->id); ?>
                                    {!! Form::text('link', $a, ['class' => 'form-control pull-left','placeholder' => 'Link','id'=>'copyTarget']) !!}

                                    @if ($errors->has('link'))
                                        <span class="help-block">
                                    <strong>{{ $errors->first('link') }}</strong>
                                      </span>
                                    @endif
                                </div>
                                <div class="col-sm-2">
                                    <input type="button" class="btn btn-info" value="Copy" id="copyButton">
                                </div>
                            </div>
                        </div>
                        @include ('admin.privatetour.privatetourlist.form')
                        <div class="box-footer">
                            <a href="{{ url('admin/privatetour/privatetourlist') }}" ><button class="btn btn-default" type="button">Back</button></a>
                            <button class="btn btn-info pull-right" type="submit">Edit</button>
                        </div>
                        {!! Form::close() !!}
                    </div>

                    @include ('admin.privatetour.privatetourlist.pricegroup')
                    @include ('admin.privatetour.privatetourlist.checkpoint')

                </div>
            </div>
        </section>
    </div>
@endsection


