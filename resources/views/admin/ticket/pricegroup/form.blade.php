{!! Form::hidden('redirects_to', URL::previous()) !!}

<div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
    <label class="col-sm-2 control-label" for="title">Title <span class="text-red">*</span></label>
    <div class="col-sm-5">
        {!! Form::text('title', null, ['class' => 'form-control', 'placeholder' => 'Title']) !!}
        @if ($errors->has('title'))
            <span class="help-block">
                <strong>{{ $errors->first('title') }}</strong>
            </span>
        @endif
    </div>
</div>

<div class="form-group{{ $errors->has('report_code') ? ' has-error' : '' }}">
    <label class="col-sm-2 control-label" for="report_code">Report code <span class="text-red">*</span></label>
    <div class="col-sm-5">
        {!! Form::text('report_code', null, ['class' => 'form-control', 'placeholder' => 'Report code']) !!}
        @if ($errors->has('report_code'))
            <span class="help-block">
                <strong>{{ $errors->first('report_code') }}</strong>
            </span>
        @endif
    </div>
</div>


<div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
    <label class="col-sm-2 control-label" for="description">Description <span class="text-red">*</span></label>
    <div class="col-sm-10">
        {!! Form::textarea('description', null, ['class' => 'form-control', 'placeholder' => 'description','rows'=>'10','cols'=>'80', 'id'=>'editor']) !!}
        @if ($errors->has('description'))
            <span class="help-block">
                <strong>{{ $errors->first('description') }}</strong>
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

<div class="form-group{{ $errors->has('servicecharge') ? ' has-error' : '' }}">
    <label class="col-sm-2 control-label" for="servicecharge">Service Charge <span class="text-red"></span></label>
    <div class="col-sm-5">
        {!! Form::text('servicecharge', null, ['class' => 'form-control', 'placeholder' => 'Service Charge']) !!}
        @if ($errors->has('servicecharge'))
            <span class="help-block">
                <strong>{{ $errors->first('servicecharge') }}</strong>
            </span>
        @endif
    </div>
</div>

<div class="form-group{{ $errors->has('status') ? ' has-error' : '' }}">
    <label class="col-sm-2 control-label" for="role">Status <span class="text-red">*</span></label>

    <div class="col-sm-5">

        @foreach (\App\TicketPricegroup::$status as $key => $value)
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

@include('admin.ticket.pricegroup.price')
@include('admin.ticket.pricegroup.volume')

<div class="box-footer">
    <button class="btn btn-info pull-right" type="submit">Edit</button>
</div>