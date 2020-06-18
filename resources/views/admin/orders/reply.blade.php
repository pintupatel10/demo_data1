@extends('admin.layouts.app')


@section('content')
    <style>
        @import url("{{ URL::asset('assets/plugins/summernote/summernote.css')}}");

        .form-group .text {
            margin-top: 7px;
        }

        .attachment-preview {
            position: relative;
        }

        .attachment-preview h4 {
            margin-right: 30px;
        }

        .attachment-preview .delete {
            color: red;
            position: absolute;
            top: 11px;
            right: 25px;
            cursor: pointer;
            padding: 10px;
        }

    </style>


    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                Order #{{ $order->{App\Order::COLUMN_ID} }}
            </h1>
            <ol class="breadcrumb">
                <li class="active">reply</li>
            </ol>
        </section>


        {!! Form::open(['id' => 'reply-form', 'url' => url("admin/order/$order->id/send-reply"), 'method' => 'post', 'class' => 'form-horizontal', 'files' => true]) !!}

        <section class="content">

            <div class="row" style="margin-bottom: 10px">
                <div class="col-xs-6">
                    <a href="{{ url("admin/order/$order->id") }}" class="btn btn-default"><span class="glyphicon glyphicon-chevron-left"></span> Order Detail</a>
                </div>
                <div class="col-xs-6 text-right">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-send-o"></i> Send</button>
                </div>
            </div>

            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">Reply Order Message</h3>
                </div>

                <div class="box-body">

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Order No.</label>
                        <div class="col-sm-4 text">{{ $order->{App\Order::COLUMN_ID} }}</div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Message</label>
                        <div class="col-sm-10">{!! Form::textarea('message', null, ['class' => 'form-control']) !!}</div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Attachments</label>
                        <div class="col-sm-5">
                            <div id="btn-upload-container"></div>
                            <div id="attachment-preview-container"></div>
                        </div>
                    </div>


                </div>
            </div>
        </section>

        {!! Form::close() !!}
    </div>
@endsection

@section("jquery")
    <script src="{{ URL::asset('assets/plugins/summernote/summernote.min.js')}}"></script>

    <script type="text/javascript">

        $(function (){
            var init = function () {
                $("textarea").summernote({
                    height: 200,
                    toolbar: [
                        // [groupName, [list of button]]
                        ['style', ['bold', 'italic', 'underline', 'clear']],
                        ['font', ['strikethrough', 'superscript', 'subscript']],
                        ['fontsize', ['fontsize']],
                        ['color', ['color']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['insert', ['link']],
                        ['height', ['height']],
                    ],
                });
            };

            init();

            var create_upload_button = function (){
                var $button = $("<span class='btn btn-default btn-file'> <i class='fa fa-upload'></i> New <input name='upload[]' type='file'></span>");
                $button.find("input").change(function (){
                    var filename = $(this).val().replace(/^.*[\\\/]/, '');

                    // Create preview
                    var $preview = $("<div class='bs-callout bs-callout-warning attachment-preview'><h4>" + filename + "</h4><div class='delete'><i class='fa fa-trash-o'></i></div></div>");
                    $preview.find(".delete").click(function (){
                        $button.remove();
                        $preview.remove();
                    });
                    $preview.prependTo("#attachment-preview-container");

                    // Hide the input control and create new one
                    $(this).closest(".btn-file").hide();
                    create_upload_button();
                });
                $button.appendTo("#btn-upload-container");
            };

            create_upload_button();
        });
    </script>
@endsection