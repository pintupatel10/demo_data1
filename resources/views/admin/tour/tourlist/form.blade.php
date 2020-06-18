{!! Form::hidden('redirects_to', URL::previous()) !!}

<div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
    <label class="col-sm-2 control-label" for="title">Title <span class="text-red">*</span></label>
    <div class="col-sm-4">
        {!! Form::text('title', null, ['class' => 'form-control', 'placeholder' => 'Title']) !!}
        @if ($errors->has('title'))
            <span class="help-block">
                <strong>{{ $errors->first('title') }}</strong>
            </span>
        @endif
    </div>
    <div class="col-sm-1">
        {!! Form::text('title_color', null, ['class' => 'jscolor','style'=>'width:100%;height:35px']) !!}
        {{--<input class="jscolor">--}}
    </div>
</div>

<script src="{{ URL::asset('assets/pk/jscolor.js')}}"></script>

<div class="form-group{{ $errors->has('image') ? ' has-error' : '' }}">
    <label class="col-sm-2 control-label" for="image">Image <span class="text-red">*</span></label>
    <div class="col-sm-2">
        <div class="">
            {!! Form::file('image', ['class' => '', 'id'=> 'image', 'onChange'=>'AjaxUploadImage(this)']) !!}
        </div>

        <?php
        if (!empty($detail->image) && $detail->image != "") {
        ?>
        <br><img id="DisplayImage" src="{{ url($detail->image) }}" name="img" id="img" width="150" style="padding-bottom:5px" >
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
    <div class="col-sm-3">
        <button class="btn btn-sucess" type="button" data-toggle="modal" data-target="#myModal">Choose image center</button>
        <p id="demo"></p>
        <img id="DisplayImage" src="" width="150" style="display: none;"/>
    </div>
</div>

<div id="myModal" class="modal fade" role="dialog">

    <div class="modal-dialog" style="width: 85%; !important;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" id="close" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Choose image center</h4>
            </div>
            <div class="modal-body">
                @foreach ($image_center as $list)
                    <div  style="display: inline-block;">
                        @if($list['image']!="" && file_exists($list['image']))
                            <img src="{{ url($list->image) }}"  class="img-responsive" class="close" style="opacity: 1" data-dismiss="modal" width="200" onclick="imageIsLoaded(this.value,'{{url($list->image)}}','{{$list->image}}')">
                        @endif
                    </div>
                @endforeach
            </div>
            <div class="clearfix" style="padding:1px;">&nbsp;</div>
        </div>
    </div>

</div>

<input type="hidden" name="image_name" value="" id="image_name" />

<div class="form-group{{ $errors->has('tour_type') ? ' has-error' : '' }}">
    <label class="col-sm-2 control-label" for="tour_type">Tour type <span class="text-red">*</span></label>
    <div class="col-sm-5">
        {!! Form::select('tour_type[]',\App\TourList::$type, !empty($modes)?$modes:null, ['class' => 'select2 select2-hidden-accessible form-control', 'style' => 'width: 100%','multiple']) !!}
        @if ($errors->has('tour_type'))
            <span class="help-block">
                <strong>{{ $errors->first('tour_type') }}</strong>
            </span>
        @endif
    </div>
</div>

<div class="form-group{{ $errors->has('tour_code') ? ' has-error' : '' }}">
    <label class="col-sm-2 control-label" for="tour_code">Tour code <span class="text-red">*</span></label>
    <div class="col-sm-5">
        {!! Form::text('tour_code', null, ['class' => 'form-control', 'placeholder' => 'Tour code']) !!}
        @if ($errors->has('tour_code'))
            <span class="help-block">
                <strong>{{ $errors->first('tour_code') }}</strong>
            </span>
        @endif
    </div>
</div>

<div class="form-group{{ $errors->has('display') ? ' has-error' : '' }}">
    <label class="col-sm-2 control-label" for="post">Display method <span class="text-red">*</span></label>

    <div class="col-sm-5">

        @foreach (\App\TourList::$display as $key => $value)
            <label>
                {!! Form::radio('display', $key, null, ['class' => 'flat-red']) !!} <span style="margin-right: 10px">{{ $value }}</span>
            </label>
        @endforeach

        @if ($errors->has('display'))
            <span class="help-block">
             <strong>{{ $errors->first('display') }}</strong>
            </span>
        @endif
    </div>
</div>


<div class="form-group{{ $errors->has('language') ? ' has-error' : '' }}">
    <label class="col-sm-2 control-label" for="language">Language <span class="text-red">*</span></label>
    <div class="col-sm-5">
        {!! Form::select('language[]',['please Select']+\App\TourList::$language, !empty($modes_selected)?$modes_selected:null, ['class' => 'select2 select2-hidden-accessible form-control', 'style' => 'width: 100%']) !!}
        @if ($errors->has('language'))
            <span class="help-block">
                <strong>{{ $errors->first('language') }}</strong>
            </span>
        @endif
    </div>
</div>


<div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
    <label class="col-sm-2 control-label" for="description">Description <span class="text-red">*</span></label>
    <div class="col-sm-10">
        {!! Form::textarea('description', null, ['class' => 'form-control', 'placeholder' => 'Content','rows'=>'10','cols'=>'80', 'id'=>'editor']) !!}
        @if ($errors->has('description'))
            <span class="help-block">
                <strong>{{ $errors->first('description') }}</strong>
            </span>
        @endif
    </div>
</div>

<div class="form-group{{ $errors->has('status') ? ' has-error' : '' }}">
    <label class="col-sm-2 control-label" for="role">Status <span class="text-red">*</span></label>

    <div class="col-sm-5">

        @foreach (\App\TourList::$status as $key => $value)
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

<script src="{{ URL::asset('assets/plugins/ckeditor/ckeditor.js')}}"></script>
<script src="{{ URL::asset('assets/plugins/ckeditor/ckfinder/ckfinder.js')}}"></script>


<script type="text/javascript">
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
            CKFinder.setupCKEditor( editor, "assets/plugins/ckeditor/ckfinder/" ) ;
</script>





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

    function imageIsLoaded(e,url,url1) {
        if(url!=undefined){
            var x=document.getElementById('image_name').value = url1;
            $('#DisplayImage').css("display", "block");
            $('#DisplayImage').attr('src', url);
            $('#DisplayImage').attr('width', '150');
        }
        else{
            $('#DisplayImage').css("display", "block");
            $('#DisplayImage').attr('src', e.target.result);
            $('#DisplayImage').attr('width', '150');
        }
    };

    function calltype(val) {
        if (val == 'Private') {
            document.getElementById('stock_type').style.visibility = "visible";
        }
        else {
            document.getElementById('stock_type').style.visibility = "hidden";
        }
    }
</script>





{{--<div class="box-footer">--}}
    {{--<a href="{{ url('admin/tour/tourlist') }}" ><button class="btn btn-default" type="button">Back</button></a>--}}
    {{--<button class="btn btn-info pull-right" type="submit">Edit</button>--}}
{{--</div>--}}