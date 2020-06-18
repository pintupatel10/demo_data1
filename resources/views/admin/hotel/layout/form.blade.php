{!! Form::hidden('redirects_to', URL::previous()) !!}

<div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
    <label class="col-sm-1 control-label" for="description">Description<span class="text-red">*</span></label>
    <div class="col-sm-5">
        {!! Form::textarea('description', null, ['class' => 'form-control', 'placeholder' => 'Content','rows'=>'10','cols'=>'80', 'id'=>'editor']) !!}
        @if ($errors->has('description'))
            <span class="help-block">
                <strong>{{ $errors->first('description') }}</strong>
            </span>
        @endif
    </div>
</div>

<div class="form-group{{ $errors->has('language') ? ' has-error' : '' }}">
    <label class="col-sm-1 control-label" for="language">Language<span class="text-red">*</span></label>
    <div class="col-sm-5">
        {!! Form::select('language',[''=>'Please select']+\App\HotelLayout::$language,null, ['class' => 'select2 select2-hidden-accessible form-control', 'style' => 'width: 100%']) !!}
        @if ($errors->has('language'))
            <span class="help-block">
                <strong>{{ $errors->first('language') }}</strong>
            </span>
        @endif
    </div>
</div>


<div class="form-group{{ $errors->has('status') ? ' has-error' : '' }}">
    <label class="col-sm-1 control-label" for="role">Status<span class="text-red">*</span></label>

    <div class="col-sm-5">

        @foreach (\App\HotelLayout::$status as $key => $value)
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
        filebrowserBrowseUrl : 'assets/plugins/ckeditor/kcfinder/browse.php?opener=ckeditor&type=files',
        filebrowserImageBrowseUrl : 'assets/plugins/ckeditor/kcfinder/browse.php?opener=ckeditor&type=images',
        filebrowserFlashBrowseUrl : 'assets/plugins/ckeditor/kcfinder/browse.php?opener=ckeditor&type=flash',
        filebrowserUploadUrl : 'assets/plugins/ckeditor/kcfinder/upload.php?opener=ckeditor&type=files',
        filebrowserImageUploadUrl : 'assets/plugins/ckeditor/kcfinder/upload.php?opener=ckeditor&type=images',
        filebrowserFlashUploadUrl : 'assets/plugins/ckeditor/kcfinder/upload.php?opener=ckeditor&type=flash',

        filebrowserWindowWidth : "1000",
        filebrowserWindowHeight : "700"
        //	uiColor= "blue";
    });
    CKFinder.setupCKEditor( editor, "ckeditor/ckfinder/" );
</script>
