{!! Form::hidden('redirects_to', URL::previous()) !!}


<div class="form-group{{ $errors->has('language') ? ' has-error' : '' }}">
    <label class="col-sm-2 control-label" for="language">Language <span class="text-red">*</span></label>
    <div class="col-sm-5">
        {!! Form::select('language[]',['please Select']+\App\Sitelogo::$language, !empty($modes_selected)?$modes_selected:null, ['class' => 'select2 select2-hidden-accessible form-control', 'style' => 'width: 100%']) !!}
        @if ($errors->has('language'))
            <span class="help-block">
                <strong>{{ $errors->first('language') }}</strong>
            </span>
        @endif
    </div>
</div>

<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
    <label class="col-sm-2 control-label" for="name">Logo name <span class="text-red">*</span></label>
    <div class="col-sm-5">
        {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Logo name']) !!}
        @if ($errors->has('name'))
            <span class="help-block">
                <strong>{{ $errors->first('name') }}</strong>
            </span>
        @endif
    </div>
</div>


<div class="form-group{{ $errors->has('path') ? ' has-error' : '' }}">
    <label class="col-sm-2 control-label" for="path">Logo <span class="text-red">*</span></label>
    <div class="col-sm-2">
        <div class="">

            {!! Form::file('path', ['class' => '', 'id'=> 'image', 'onChange'=>'AjaxUploadImage(this)']) !!}
        </div>

        <?php
        if (!empty($sitelogo->path) && $sitelogo->path != "") {
        ?>
        <br><img id="DisplayImage" src="{{ url($sitelogo->path) }}" name="img" id="img" width="150" style="padding-bottom:5px" >
        <?php
        }else{
            echo '<br><img id="DisplayImage" src="" width="150" style="display: none;"/>';
        } ?>

        @if ($errors->has('path'))
            <span class="help-block">
                    <strong>{{ $errors->first('path') }}</strong>
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


<div class="form-group{{ $errors->has('status') ? ' has-error' : '' }}">
    <label class="col-sm-2 control-label" for="role">Status <span class="text-red">*</span></label>

    <div class="col-sm-5">

        @foreach (\App\Sitelogo::$status as $key => $value)
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

</script>