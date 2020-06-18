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
                <li> <i class="fa fa-dashboard"></i> <a href="{{ url('admin/emailadvertise') }}"> {{ $menu }} </a></li>
                <li class="active">edit</li>
            </ol>
        </section>

        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title">Edit Email Advertise </h3>
                        </div>
                        {!! Form::model($emailadvertise, ['url' => url('admin/emailadvertise/'. $emailadvertise->id), 'method' => 'patch', 'class' => 'form-horizontal','files'=>true]) !!}

                        <div class="box-body">

                            <div class="form-group{{ $errors->has('subject') ? ' has-error' : '' }}">
                                <label class="col-sm-2 control-label" for="subject">Subject<span class="text-red">*</span></label>

                                <div class="col-sm-5">
                                    {!! Form::text('subject', null, ['class' => 'form-control', 'placeholder' => 'Subject','disabled']) !!}
                                    @if ($errors->has('subject'))
                                        <span class="help-block">
                <strong>{{ $errors->first('subject') }}</strong>
            </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('content') ? ' has-error' : '' }}">
                                <label class="col-sm-2 control-label" for="title">Content Of Mail<span class="text-red">*</span></label>
                                <div class="col-sm-10">
                                    {!! Form::textarea('content', null, ['class' => 'form-control', 'placeholder' => 'Content','rows'=>'10','cols'=>'80', 'id'=>'editor','disabled']) !!}
                                    @if ($errors->has('content'))
                                        <span class="help-block">
                <strong>{{ $errors->first('content') }}</strong>
            </span>
                                    @endif
                                </div>
                            </div>


                            <script src="{{ URL::asset('assets/plugins/ckeditor/ckeditor.js')}}"></script>
                            <script src="{{ URL::asset('assets/plugins/ckeditor/ckfinder/ckfinder.js')}}"></script>

                            <script type="text/javascript">
                                //<![CDATA[
                                var editor = CKEDITOR.replace( "editor" ,{
                                    on: {
                                        instanceReady: function( ev ) {
                                            // Output paragraphs as <p>Text</p>.
                                            this.dataProcessor.writer.setRules( "p", {
                                                indent: false,
                                                breakBeforeOpen: true,
                                                breakAfterOpen: false,
                                                breakBeforeClose: false,
                                                breakAfterClose: false
                                            });
                                        }
                                    },
                                    allowedContent: true,
                                    filebrowserBrowseUrl : "{{URL::asset('assets/plugins/ckeditor/ckfinder/ckfinder.html')}}",
                                    filebrowserImageBrowseUrl : "{{URL::asset('assets/plugins/ckeditor/ckfinder/ckfinder.html')}}?type=Images",
                                    filebrowserFlashBrowseUrl : "{{URL::asset('assets/plugins/ckeditor/ckfinder/ckfinder.html')}}?type=Flash",
                                    filebrowserUploadUrl : "{{URL::asset('assets/plugins/ckeditor/ckfinder/core/connector/php/connector.php')}}?command=QuickUpload&type=Files",
                                    filebrowserImageUploadUrl : "{{URL::asset('assets/plugins/ckeditor/ckfinder/core/connector/php/connector.php')}}?command=QuickUpload&type=Images",
                                    filebrowserFlashUploadUrl : "{{URL::asset('assets/plugins/ckeditor/ckfinder/core/connector/php/connector.php')}}?command=QuickUpload&type=Flash",


                                    filebrowserWindowWidth : "1000",
                                    filebrowserWindowHeight : "700"
                                    //	uiColor= "blue";
                                });
                                CKFinder.setupCKEditor( editor, "ckeditor/ckfinder/" );
                            </script>


                            <div class="box box-info">
                                <br>
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Title</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $count = 0;?>
                                    @foreach ($email_selected as $list)
                                        <tr>
                                            <td>{{ $list['id'] }}</td>
                                            <td>{{ $list['title'] }}</td>
                                            <td>{{ $list['name'] }}</td>
                                            <td>{{ $list['email'] }}</td>
                                        </tr>
                                    @endforeach
                                </table>

                                <br>
                            </div>

                        </div>
                        <div class="box-footer">
                            <a href="{{ url('admin/emailadvertise') }}" ><button class="btn btn-default" type="button">Back</button></a>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection


