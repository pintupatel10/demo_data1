

<div class="form-group{{ $errors->has('subject') ? ' has-error' : '' }}">
    <label class="col-sm-2 control-label" for="subject">Email<span class="text-red">*</span></label>

    <div class="col-sm-8">
        {!! Form::text('email', null, ['class' => 'form-control', 'placeholder' => 'Email']) !!}
        @if ($errors->has('email'))
            <span class="help-block">
                <strong>{{ $errors->first('email') }}</strong>
            </span>
        @endif
    </div>
</div>

<div class="form-group{{ $errors->has('content') ? ' has-error' : '' }}">
    <label class="col-sm-2 control-label" for="title">Content Of Mail<span class="text-red">*</span></label>
    <div class="col-sm-8">
        <textarea  name="content" id="editor1{{$list['id']}}" class="form-control" rows="10" cols="80"></textarea>
        @if ($errors->has('content'))
            <span class="help-block">
                <strong>{{ $errors->first('content') }}</strong>
            </span>
        @endif
    </div>
</div>
<div class="form-group{{ $errors->has('attachment') ? ' has-error' : '' }}">
    <label class="col-sm-2 control-label" for="subject">Attachment<span class="text-red">*</span></label>

    <div class="col-sm-8">
       {{form::file('attachment',['class'=>'form-control'])}}
        @if ($errors->has('attachment'))
            <span class="help-block">
                <strong>{{ $errors->first('attachment') }}</strong>
            </span>
        @endif
    </div>
</div>


<br>
<script src="{{ URL::asset('assets/plugins/ckeditor/ckeditor.js')}}"></script>
<script src="{{ URL::asset('assets/plugins/ckeditor/ckfinder/ckfinder.js')}}"></script>

<script type="text/javascript">
    //<![CDATA[
    var editor = CKEDITOR.replace("editor1{{$list['id']}}",{
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
    CKFinder.setupCKEditor( editor, "ckeditor/ckfinder/");
</script>
