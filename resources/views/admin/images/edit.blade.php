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
                <li><a href="{{ url('admin/images') }}"> <i class="fa fa-dashboard"></i>  Image Center</a></li>
                <li class="active">edit</li>
            </ol>
        </section>

        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title">Edit Images Center</h3>
                        </div>

                        {!! Form::model($image, ['url' => url('admin/images/'.$image->id), 'method' => 'patch', 'class' => 'form-horizontal','files'=>true]) !!}

                        <div class="box-body">
                            <div class="form-group{{ $errors->has('image') ? ' has-error' : '' }}">
                                <label class="col-sm-1 control-label" for="image">image <span class="text-red">*</span></label>
                                <div class="col-sm-5">
                                    <div class="">

                                        {!! Form::file('image', ['class' => '', 'id'=> 'image', 'onChange'=>'AjaxUploadImage(this)']) !!}
                                    </div>

                                    <?php
                                    if (!empty($layout->image) && $layout->image != "") {
                                    ?>
                                    <br><img id="DisplayImage" src="{{ url($layout->image) }}" name="img" id="img" width="150" style="padding-bottom:5px" >
                                    <?php
                                    }else{
                                        echo '<br><img id="DisplayImage" src="" width="150" style="display: none;"/>';
                                    } ?>

                                    @if ($errors->has('image'))
                                        <span class="help-block">
                    <strong>{{ $errors->first('image') }}</strong>
                </span>
                                    @endif
                                </div>
                            </div>

                        </div>
                        <div class="box-footer">
                            <a href="{{ url('admin/images') }}" ><button class="btn btn-default" type="button">Back</button></a>
                            <button class="btn btn-info pull-right" type="submit">Edit</button>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection


<script>

    $("#image").fileinput({
        showUpload: false,
        showCaption: false,
        showPreview: false,
        showRemove: false,
        browseClass: "btn btn-primary btn-lg btn_new",
    });

    function AjaxUploadImage(obj,id){

        var file = obj.files[0];
        var imagefile = file.type;
        var match = ["image/jpeg", "image/png", "image/jpg"];
        if (!((imagefile == match[0]) || (imagefile == match[1]) || (imagefile == match[2])))
        {
            $('#previewing'+URL).attr('src', 'noimage.png');
            alert("<p id='error'>Please Select A valid Image File</p>" + "<h4>Note</h4>" + "<span id='error_message'>Only jpeg, jpg and png Images type allowed</span>");
            //$("#message").html("<p id='error'>Please Select A valid Image File</p>" + "<h4>Note</h4>" + "<span id='error_message'>Only jpeg, jpg and png Images type allowed</span>");
            return false;
        } else{
            var reader = new FileReader();
            reader.onload = imageIsLoaded;
            reader.readAsDataURL(obj.files[0]);
        }
    }

    function imageIsLoaded(e) {

        $('#DisplayImage').css("display", "block");
        $('#DisplayImage').attr('src', e.target.result);
        $('#DisplayImage').attr('width', '150');

    };

</script>


