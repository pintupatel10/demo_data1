{!! Form::hidden('redirects_to', URL::previous()) !!}

<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
    <label class="col-sm-1 control-label" for="name">Name<span class="text-red">*</span></label>

    <div class="col-sm-5">
        {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Name']) !!}
        @if ($errors->has('name'))
            <span class="help-block">
                <strong>{{ $errors->first('name') }}</strong>
            </span>
        @endif
    </div>
</div>

<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
    <label class="col-sm-1 control-label" for="email">Email<span class="text-red">*</span></label>

    <div class="col-sm-5">
        {!! Form::text('email', null, ['class' => 'form-control', 'placeholder' => 'email']) !!}
        @if ($errors->has('email'))
            <span class="help-block">
                <strong>{{ $errors->first('email') }}</strong>
            </span>
        @endif
    </div>
</div>


<div class="form-group{{ $errors->has('image') ? ' has-error' : '' }}">
    <label class="col-sm-1 control-label" for="image">Profile Pic </label>
    <div class="col-sm-5">
        <div class="">
            {!! Form::file('image', ['class' => '', 'id'=> 'image', 'onChange'=>'AjaxUploadImage(this)']) !!}
        </div>

        <?php
        if (!empty($staff->image) && $staff->image != "") {
        ?>
        <br><img id="DisplayImage" src="{{ url($staff->image) }}" name="img" id="img" width="150" style="padding-bottom:5px" >
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


<div class="form-group{{ $errors->has('number') ? ' has-error' : '' }}">
    <label class="col-sm-1 control-label" for="number">Staff Number<span class="text-red"> *</span></label>
    <div class="col-sm-5">
        {!! Form::text('number', null, ['class' => 'form-control', 'placeholder' => 'Staff Number']) !!}
        @if ($errors->has('number'))
            <span class="help-block">
                <strong>{{ $errors->first('number') }}</strong>
            </span>
        @endif
    </div>
</div>

<div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
    <label class="col-sm-1 control-label" for="inputPassword3">Password @if (!isset($user)) <span class="text-red">*</span> @endif</label>
    <div class="col-sm-5">
        <input type="password" placeholder="Password" id="password" name="password" class="form-control" >
        @if ($errors->has('password'))
            <span class="help-block">
                <strong>{{ $errors->first('password') }}</strong>
            </span>
        @endif
    </div>
</div>

<div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
    <label class="col-sm-1 control-label" for="inputPassword3">Confirm Password @if (!isset($user)) <span class="text-red">*</span> @endif</label>

    <div class="col-sm-5">
        <input type="password" placeholder="Confirm password" id="password-confirm" name="password_confirmation" class="form-control" >
        @if ($errors->has('password_confirmation'))
            <span class="help-block">
             <strong>{{ $errors->first('password_confirmation') }}</strong>
            </span>
        @endif
    </div>
</div>


<div class="form-group{{ $errors->has('actionlog_show') ? ' has-error' : '' }}">
    <label class="col-sm-1 control-label" for="actionlog_show">All Action-Log Show <span class="text-red">*</span></label>

    <div class="col-sm-5">

        @foreach (\App\Staff::$actionlog as $key => $value)
            <label>
                {!! Form::radio('actionlog_show', $key, null, ['class' => 'flat-red']) !!} <span style="margin-right: 10px">{{ $value }}</span>
            </label>
        @endforeach

        @if ($errors->has('actionlog_show'))
            <span class="help-block">
             <strong>{{ $errors->first('actionlog_show') }}</strong>
            </span>
        @endif
    </div>
</div>


<div class="form-group{{ $errors->has('group_id') ? ' has-error' : '' }}">
    <label class="col-sm-1 control-label" for="group_id">Staff Group<span class="text-red">*</span></label>
    <div class="col-sm-5">
        {!! Form::select('group_id',[''=>'Please Select']+$groupname, !empty($modes_selected)?$modes_selected:null, ['class' => 'select2 select2-hidden-accessible form-control', 'style' => 'width: 100%']) !!}
        @if ($errors->has('group_id'))
            <span class="help-block">
                <strong>{{ $errors->first('group_id') }}</strong>
            </span>
        @endif
    </div>
</div>
<div class="form-group{{ $errors->has('status') ? ' has-error' : '' }}">
    <label class="col-sm-1 control-label" for="role">Status <span class="text-red">*</span></label>

    <div class="col-sm-5">

        @foreach (\App\Staff::$status as $key => $value)
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

    function imageIsLoaded(e) {

        $('#DisplayImage').css("display", "block");
        $('#DisplayImage').attr('src', e.target.result);
        $('#DisplayImage').attr('width', '150');

    };

</script>