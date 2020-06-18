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


<div class="form-group{{ $errors->has('portrait_image') ? ' has-error' : '' }}">
    <label class="col-sm-2 control-label" for="portrait_image">Portrait Image <span class="text-red">*</span></label>
    <div class="col-sm-3">
        <div class="">
            <input type="file" name="portrait_image" id="image1" onChange="AjaxUploadImage(this,'1')">
        </div>

        <?php
        if (!empty($groupdetail->portrait_image) && $groupdetail->portrait_image != "") {
        ?>
        <br><img id="DisplayImage1" src="{{ url($groupdetail->portrait_image) }}" name="img1" width="150" style="padding-bottom:5px" >
        <?php
        }else{
            echo '<br><img id="DisplayImage1" src="" width="150" style="display: none;"/>';
        } ?>

        @if ($errors->has('portrait_image'))
            <span class="help-block">
                    <strong>{{ $errors->first('portrait_image') }}</strong>
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
<input type="hidden" name="portrait_image_name" value="" id="image_name" />

<div class="form-group{{ $errors->has('landscape_image') ? ' has-error' : '' }}">
    <label class="col-sm-2 control-label" for="landscape_image">Landscape Image <span class="text-red">*</span></label>
    <div class="col-sm-3">
        <div class="">
            <input type="file" name="landscape_image" id="image2" onChange="AjaxUploadImage1(this,'2')">
        </div>

        <?php
        if (!empty($groupdetail->landscape_image) && $groupdetail->landscape_image != "") {
        ?>
        <br><img id="DisplayImage2" src="{{ url($groupdetail->landscape_image) }}" name="img2" id="img2" width="150" style="padding-bottom:5px" >
        <?php
        }else{
            echo '<br><img id="DisplayImage2" src="" width="150" style="display: none;"/>';
        } ?>

        @if ($errors->has('landscape_image'))
            <span class="help-block">
                    <strong>{{ $errors->first('landscape_image') }}</strong>
                </span>
        @endif
    </div>
    <div class="col-sm-3">
        <button class="btn btn-sucess" type="button" data-toggle="modal" data-target="#myModal1">Choose image center</button>
        <p id="demo"></p>
        <img id="DisplayImage2" src="" width="150" style="display: none;"/>
    </div>

</div>
<div id="myModal1" class="modal fade" role="dialog">

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
                            <img src="{{ url($list->image) }}"  class="img-responsive" class="close" style="opacity: 1" data-dismiss="modal" width="200" onclick="imageIsLoaded1(this.value,'{{url($list->image)}}','{{$list->image}}')">
                        @endif
                    </div>
                @endforeach
            </div>
            <div class="clearfix" style="padding:1px;">&nbsp;</div>
        </div>
    </div>

</div>
<input type="hidden" name="landscape_image_name" value="" id="image_name2" />

<div class="form-group{{ $errors->has('language') ? ' has-error' : '' }}">
    <label class="col-sm-2 control-label" for="language">Language <span class="text-red">*</span></label>
    <div class="col-sm-5">
        {!! Form::select('language[]',['please Select']+\App\TourGroup::$language, !empty($modes_selected)?$modes_selected:null, ['class' => 'select2 select2-hidden-accessible form-control', 'style' => 'width: 100%']) !!}
        @if ($errors->has('language'))
            <span class="help-block">
                <strong>{{ $errors->first('language') }}</strong>
            </span>
        @endif
    </div>
</div>

<div class="form-group{{ $errors->has('select_sentence') ? ' has-error' : '' }}">
    <label class="col-sm-2 control-label" for="select_sentence">Sentence <span class="text-red">*</span></label>
    <div class="col-sm-5">
        {!! Form::text('select_sentence', null, ['class' => 'form-control', 'placeholder' => 'Title']) !!}
        @if ($errors->has('select_sentence'))
            <span class="help-block">
                <strong>{{ $errors->first('select_sentence') }}</strong>
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
        filebrowserBrowseUrl : "public/assets/plugins/ckeditor/ckfinder/ckfinder.html",
        filebrowserImageBrowseUrl : "public/assets/plugins/ckeditor/ckfinder/ckfinder.html?type=Images",
        filebrowserFlashBrowseUrl : "public/assets/plugins/ckeditor/ckfinder/ckfinder.html?type=Flash",
        filebrowserUploadUrl : "public/assets/plugins/ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files",
        filebrowserImageUploadUrl : "public/assets/plugins/ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images",
        filebrowserFlashUploadUrl : "public/assets/plugins/ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash",


        filebrowserWindowWidth : "1000",
        filebrowserWindowHeight : "700"
        //	uiColor= "blue";
    });
    CKFinder.setupCKEditor( editor, "ckeditor/ckfinder/" ) ;
</script>




<div class="form-group{{ $errors->has('status') ? ' has-error' : '' }}">
    <label class="col-sm-2 control-label" for="role">Status <span class="text-red">*</span></label>

    <div class="col-sm-5">

        @foreach (\App\TourGroup::$status as $key => $value)
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

//    $("#image").fileinput({
//        showUpload: false,
//        showCaption: false,
//        showPreview: false,
//        showRemove: false,
//        browseClass: "btn btn-primary btn-lg btn_new",
//    });

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

    function AjaxUploadImage1(obj,id){

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
            reader.onload = imageIsLoaded1;
            reader.readAsDataURL(obj.files[0]);
        }
    }

    function imageIsLoaded(e,url,url1) {
        if(url!=undefined){
            var x=document.getElementById('image_name').value = url1;
            $('#DisplayImage1').css("display", "block");
            $('#DisplayImage1').attr('src', url);
            $('#DisplayImage1').attr('width', '150');

        }
        else{
            $('#DisplayImage1').css("display", "block");
            $('#DisplayImage1').attr('src', e.target.result);
            $('#DisplayImage1').attr('width', '150');
        }
    };
    function imageIsLoaded1(e,url,url1) {
        if(url!=undefined){
            var x=document.getElementById('image_name2').value = url1;
            $('#DisplayImage2').css("display", "block");
            $('#DisplayImage2').attr('src', url);
            $('#DisplayImage2').attr('width', '150');
        }
        else{
            $('#DisplayImage2').css("display", "block");
            $('#DisplayImage2').attr('src', e.target.result);
            $('#DisplayImage2').attr('width', '150');
        }
    };

</script>
{{--<script>--}}

    {{--$("#image").fileinput({--}}
        {{--showUpload: false,--}}
        {{--showCaption: false,--}}
        {{--showPreview: false,--}}
        {{--showRemove: false,--}}
        {{--browseClass: "btn btn-primary btn-lg btn_new",--}}
    {{--});--}}

    {{--function AjaxUploadImage(obj, id) {--}}
        {{--var file = obj.files[0];--}}
        {{--var imagefile = file.type;--}}
        {{--var match = ["image/jpeg", "image/png", "image/jpg"];--}}
        {{--if (!((imagefile == match[0]) || (imagefile == match[1]) || (imagefile == match[2]))) {--}}
            {{--$('#previewing' + URL).attr('src', 'noimage.png');--}}
            {{--alert("<p id='error'>Please Select A valid Image File</p>" + "<h4>Note</h4>" + "<span id='error_message'>Only jpeg, jpg and png Images type allowed</span>");--}}
            {{--//$("#message").html("<p id='error'>Please Select A valid Image File</p>" + "<h4>Note</h4>" + "<span id='error_message'>Only jpeg, jpg and png Images type allowed</span>");--}}
            {{--return false;--}}
        {{--} else {--}}
            {{--var reader = new FileReader();--}}
            {{--reader.onload = imageIsLoaded;--}}
            {{--reader.readAsDataURL(obj.files[0]);--}}
        {{--}--}}

        {{--function imageIsLoaded(e) {--}}

            {{--$('#DisplayImage' + id).css("display", "block");--}}
            {{--$('#DisplayImage' + id).attr('src', e.target.result);--}}
            {{--$('#DisplayImage' + id).attr('width', '75');--}}

        {{--};--}}

    {{--}--}}
{{--</script>--}}


<link rel="stylesheet" type="text/css" href="{{URL::asset('assets/bootstrap/css/bootstrap.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{URL::asset('assets/test/prettify.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{URL::asset('assets/test/src/bootstrap-duallistbox.css')}}">
<script src="{{URL::asset('assets/jquery.min.js')}}"></script>
<script src="{{URL::asset('assets/bootstrap/js/bootstrap.min.js')}}"></script>
<script src="{{URL::asset('assets/test/run_prettify.min.js')}}"></script>
<script src="{{URL::asset('assets/test/src/jquery.bootstrap-duallistbox.js')}}"></script>






<hr>
<h1> Tour List </h1>
<br>
<div class="box">
    <div class="box-body">
        <head>
            <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
            <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
            <link rel="stylesheet" href="http://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

            <style>

                @media (min-width: 1200px) {
                    .container {
                        width:1080px;
                    }
                }

                .item-container {
                    height: 450px;
                    overflow: auto;
                    border: solid 1px #ccc;
                    margin-top: 5px;
                    background-color: #ecf0f5;
                }

                .item-container > div {
                    padding: 20px 20px 10px 10px;
                    overflow: auto;
                    border-bottom: solid 1px #ddd;
                    cursor: pointer;
                    position: relative;
                    height: 60px;
                }

                .item-container > div label {
                    display: inline-block;
                    max-width: 100%;
                    margin-bottom: 5px;
                    font-weight: 700;
                }

                .item-container > div small {
                    color: #aaa;
                    display: block;
                    overflow: hidden;
                    font-weight: normal;
                    line-height: 1.4em;
                }

                .item-container > div:hover {
                    background: #f3f3f3;
                }

                #item-available > div::after {
                    font-family: 'Glyphicons Halflings';
                    content: '\e258';
                    display: block;
                    position: absolute;
                    right: 10px;
                    color: #a9a9a9;
                    top: 18px;
                }

                #item-selected > div::after {
                    font-family: 'Glyphicons Halflings';
                    content: '\e020';
                    display: block;
                    position: absolute;
                    right: 10px;
                    color: #d40000;
                    top: 18px;
                }

                #item-available > div.selected {
                    display: none !important;
                }

                .item-container > .sortable-placeholder {
                    height: 70px; /* Same as the drag item height */
                    border: 1px solid #fcefa1;
                    background-color: #fbf9ee;
                }

            </style>
        </head>


        <div class="container" style="margin-top: 20px">

            <div class="row">

                <div class="col-md-6">
                    <div>Items Available: (click to select)</div>

                    <div class="input-group">
                        <span class="input-group-addon"><span class="glyphicon glyphicon-search"></span></span>
                        <input type="text" class="form-control search" placeholder="Search"
                               data-container-id="item-available">
                    </div>

                    <div id="item-available" class="item-container">
                        <!-- You can generate item content by PHP Here -->
                    </div>

                </div>
                <div data-value="~"></div>

                <div class="col-md-6">
                    <div>Items Selected: (click to un-selected)</div>

                    <div class="input-group">
                        <span class="input-group-addon"><span class="glyphicon glyphicon-search"></span></span>
                        <input type="text" class="form-control search" placeholder="Search"
                               data-container-id="item-selected">
                    </div>

                    <div id="item-selected" class="item-container">
                        <!-- You can generate item content by PHP Here (make sure to add on the left with "selected" class as well) -->
                    </div>
                </div>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
        <script src="http://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
        <script src="http://gesquive.github.io/bootstrap-add-clear/js/bootstrap-add-clear.min.js"></script>

        <input type="hidden" name="itm_sel" id="itm_sel"
               value="@if(isset($filter['tour_list'])){{$filter['tour_list']}}@endif">
        {{--<input type="hidden" name="grp_sel" id="grp_sel"--}}
        {{--value="@if(isset($filter['group_list'])){{$filter['group_list']}}@endif">--}}
        <script type="text/javascript">
            function toggle_item1(item) {
                var selected_item = document.getElementById('itm_sel').value;

                if ($(item).closest("#item-available").length == 1) {
                    var current_id = $(item).attr('data-value');
                    if (selected_item == '') {
                        document.getElementById('itm_sel').value = current_id;
                    } else {
                        document.getElementById('itm_sel').value = selected_item + ',' + current_id;
                    }
                    $(item).addClass('selected').clone().appendTo("#item-selected");
                }
                else {
                    $("#item-available > div").each(function () {
                        if ($(this).attr('data-value') == $(item).attr('data-value'))
                            $(this).removeClass('selected');
                        var current_id1 = $(item).attr('data-value');
                        var separator = ",";
                        var values = selected_item.split(",");
                        for (var i = 0; i < values.length; i++) {
                            if (values[i] === current_id1) {
                                values.splice(i, 1);
                                selected_item = document.getElementById('itm_sel').value = values.join(separator);
                            }
                        }
                    });
                    $(item).remove();
                }

            }

            function toggle_item(item) {
                var selected_item = document.getElementById('itm_sel').value;
                if ($(item).closest("#item-available").length == 1) {
                    var current_id = $(item).attr('data-value');
                    if (selected_item == '') {
                        document.getElementById('itm_sel').value = current_id;
                    } else {
                        document.getElementById('itm_sel').value = selected_item + ',' + current_id;
                    }
                    $(item).addClass('selected').clone().appendTo("#item-selected");
                }
                else {
                    $("#item-available > div").each(function () {
                        if ($(this).attr('data-value') == $(item).attr('data-value'))
                            $(this).removeClass('selected');
                        var current_id1 = $(item).attr('data-value');
                        var separator = ",";
                        var values = selected_item.split(",");
                        for (var i = 0; i < values.length; i++) {
                            if (values[i] === current_id1) {
                                values.splice(i, 1);
                                selected_item = document.getElementById('itm_sel').value = values.join(separator);
                            }
                        }
                    });
                    $(item).remove();
                }
            }

            $(function () {

                /* ============ LEFT SIDE =============== */

                /* ============ ADD MODE ==========*/

                        <?php
                        if (isset($name)){
                        foreach($name as $key1 => $value1){
                        ?>
                var d = '<?php echo $value1['id']; ?>';
                var text1 = "<?php echo $value1['title']; ?>";
                var html1 = "<div data-id='"  + d +"' data-value='" + d + "' onclick='toggle_item(this)' style='background-color: white;'>" +
                        "    <label>" + text1 + "</label>" +
                        "</div>";
                $("#item-available").append($(html1));
                <?php
                }}
                ?>
                /*============== EDIT MODE ================*/
                        <?php
                        if (isset($mode3)){
                        foreach($mode3 as $key1 => $value1){
                        ?>
                var d = '<?php echo $value1['id']; ?>';
                var text1 = "<?php echo $value1['title']; ?>";
                var html1 = "<div data-id='"  + d +"' data-value='" + d + "'  onclick='toggle_item(this)' style='background-color: white;'>" +
                        "    <label>" + text1 + "</label>" +
                        "</div>";
                $("#item-available").append($(html1));
                <?php
                }}
                ?>
                /* =====================  RIGHT SIDE ===================== */

                        <?php
                        if (isset($mode50)){
                        //  for($i=0;$i<count($mode50);$i++) {

                        //$mm1 = App\HotelDetail::where('id', $mode50[$i+1])->first();
                        foreach($mode50 as $key => $value){
                        ?>
                var d = '<?php echo $value['id']; ?>';
                var text1 = "<?php echo $value['title']; ?>";
                var html1 = "<div data-id='" + d +"' data-value='" + d + "'  onclick='toggle_item(this)' style='background-color: white;'>" +
                        "    <label>" + text1 + "</label>" +
                        "</div>";
                $("#item-available").append($(html1).addClass("selected"));
                $("#item-selected").append($(html1));

                <?php
                }

                }

                ?>
                // set up search
                $("input.search").each(function () {
                    var container_id = $(this).attr('data-container-id');
                    $self = $(this);
                    $self.keyup(function () {
                        var value = $(this).val();
                        $("#" + container_id + " > div").each(function () {
                            if (value == '' || $(this).find("label, small").text().toLowerCase().search(value.toLowerCase()) != -1)
                                $(this).show();
                            else
                                $(this).hide();
                        });
                    });
//                    $self.addClear({
//                        onClear: function () {
//                            $("#" + container_id + " > div").show();
//                        }
//                    });
                });

                // Use jQuery UI sortable to make it drag
                $("#item-selected").sortable({
                    placeholder: 'sortable-placeholder',
                    helper: function (event, ui) {
                        var $clone = $(ui).clone();
                        $clone.css('position', 'absolute');
                        return $clone.get(0);
                    }
                });

            });

            //replace itm_sel with sorted order list
            $("form").submit(function() {
                var data = [];
                $('[data-value]').append(function(){
                    var a  = $(this).attr('id');
                    data.push($(this).attr('data-value'));
                });
                $("input[name=itm_sel]").val(data);
            });

        </script>
    </div>
</div>