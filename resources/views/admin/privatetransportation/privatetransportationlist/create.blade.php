@extends('admin.layouts.app')


@section('content')
    <div class="content-wrapper" style="min-height: 946px;">
        <section class="content-header">
            <h1>
                <h1>
                    {{ $menu }}
                    <small>Add</small>
                </h1>

            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ url('admin/privatetransportation/privatetransportationlist') }}"> <i class="fa fa-dashboard"></i>  {{ $menu }} </a></li>
                <li class="active">Add</li>
            </ol>
        </section>

        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title">ADD Private Transportation List </h3>
                        </div>
                        {!! Form::open(['url' => url('admin/privatetransportation/privatetransportationlist'), 'class' => 'form-horizontal','files'=>true]) !!}
                        <div class="box-body">

                            <div class="form-group{{ $errors->has('link') ? ' has-error' : '' }}">
                                <label class="col-sm-2 control-label" for="link">Link </label>
                                <div class="col-sm-4">

                                    <?php
                                        $new=1;
                                    $totalprivate=\App\TransportationList::orderby('id','DESC')->first();
                                        if($totalprivate){
                                            $new=$totalprivate->id+1;
                                         }
                                    $a=url('privatetransportation/privatetransportationlist/'.$new);?>
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
                            @include('admin.privatetransportation.privatetransportationlist.form')
                        <div class="box-footer">
                            <a href="{{ url('admin/privatetransportation/privatetransportationlist') }}" ><button class="btn btn-default" type="button">Back</button></a>
                            <button class="btn btn-info pull-right" type="submit">Add</button>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
                </div>
            </div>
        </section>
    </div>
@endsection



