
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
                <li><a href="{{ url('admin/images') }}"> <i class="fa fa-dashboard"></i>  Image Center </a></li>
                <li class="active">Add</li>
            </ol>
        </section>

        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title">ADD Images Center</h3>
                        </div>
                        <script type="text/javascript" src="http://www.expertphp.in/js/jquery.form.js"></script>
                        <script>
                            function preview_images()
                            {
                                var total_file=document.getElementById("images").files.length;
                                document.getElementById("cnt").value=total_file;
                                for(var i=0;i<total_file;i++)
                                {
                                    $('#image_preview').append("<div class='col-md-2' style='margin-left: 40px;'><img  height='70px' width = '100px' src='"+URL.createObjectURL(event.target.files[i])+"'></div>");
                                }
                            }
                        </script>
                        <div class="row">
                            {!! Form::open(['url' => url('admin/images'),'method'=>'post','class' => 'form-horizontal','files'=>true]) !!}

                            <div class="col-sm-3" style="margin-left: 50px;">
                                {{--<input type="file" class="form-control"  name="images[]" multiple/>--}}
                                <div class="form-group{{ $errors->has('images') ? ' has-error' : '' }}">
                                <input type="file" class="form-control" id="images" name="images[]" onchange='preview_images();' multiple/>
                                @if ($errors->has('images'))
                                    <span class="help-block">
                    <strong>{{ $errors->first('images') }}</strong>
                </span>
                                @endif
                                    </div>
                               <input type="hidden" name="cnt" id="cnt">
                            </div>

                            <div class="col-sm-6">
                                <input type="submit" class="btn btn-primary" name='submit_image' value="Upload Multiple Image"/>
                            </div>
                            <br> <br>
                            {!! Form::close() !!}
                        </div>
                        <div class="row" id="image_preview"></div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection