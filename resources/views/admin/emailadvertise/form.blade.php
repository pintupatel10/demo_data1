{!! Form::hidden('redirects_to', URL::previous()) !!}

<div class="form-group{{ $errors->has('subject') ? ' has-error' : '' }}">
    <label class="col-sm-2 control-label" for="subject">Subject<span class="text-red">*</span></label>

    <div class="col-sm-5">
        {!! Form::text('subject', null, ['class' => 'form-control', 'placeholder' => 'Subject']) !!}
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
        {!! Form::textarea('content', null, ['class' => 'form-control', 'placeholder' => 'Content','rows'=>'10','cols'=>'80', 'id'=>'editor']) !!}
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