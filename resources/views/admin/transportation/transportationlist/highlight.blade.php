<div style="float: left;">
    <input class="btn btn-info pull-right" type="button" value="Add Highlight" onClick="addEvent1213();">
</div>

<div id="myDiv1">
    <?php  $attribute_id = ""; ?>
    @if(isset($detail->Transportationhighlight[0]) && !empty($detail->Transportationhighlight[0]))
        <?php $count1 = 1;
        ?>
        @foreach ($detail->Transportationhighlight as $key => $value)
            <?php $attribute_id.=$value->id.",";
            ?>
            <br><br>
            <div class="col-sm-12" id="Account1<?php echo $count1; ?>">
                <br><br>
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Highlight</h3>
                        <div class="pull-right box-tools">
                            <a href="javascript:;" onclick="removeEvent1213('Account1<?php echo $count1; ?>','<?php echo $value->id;?>')">
                                <button type="button" class="btn btn-info btn-sm" data-widget="remove" data-toggle="tooltip" title="Remove">
                                    <i class="fa fa-times"></i></button>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="box-body" style="background-color: rgba(63, 143, 199, 0.09);margin-top: -20px;">
                    <div class="form-group"><br><br>
                        <label class="col-sm-2 control-label">Title<span class="text-red"></span></label>
                        <div class="col-sm-5">
                            <input type="text" name="title<?php echo $count1; ?>" id="title<?php echo $count1; ?>" value="{{$value->title}}" class="form-control" >
                        </div>
                    </div>

                    <div class="form-group"><br>
                        <label class="col-sm-2 control-label">Content<span class="text-red"></span></label>
                        <div class="col-sm-10">
                            <textarea  name="content<?php echo $count1; ?>" id="editor1<?php echo $count1; ?>" class="form-control" value="{{$value->content}}">{{$value->content}}</textarea><br><br>
                        </div>
                    </div>
                </div>
                <script>
                    var editor = CKEDITOR.replace('editor1<?php echo $count1; ?>',{
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
            </div>

            <?php $count1++; ?>
        @endforeach
    @endif
</div>



<script>
    function displayunicode(e){

        $(this).keydown(function(e)
        {
            var key = e.charCode || e.keyCode || 0;
            // allow backspace, tab, delete, enter, arrows, numbers and keypad numbers ONLY
            // home, end, period, and numpad decimal
            return (
            key == 8 ||
            key == 9 ||
            key == 13 ||
            key == 46 ||
            key == 110 ||
            key == 190 ||
            (key >= 35 && key <= 40) ||
            (key >= 48 && key <= 57) ||
            (key >= 96 && key <= 105));
        });

    }


    function addEvent1213() {

        var ni = document.getElementById('myDiv1');
        var numi1 = document.getElementById('theValue1213');
        var num1 = (document.getElementById("theValue1213").value - 1) + 2;
        numi1.value = num1;
        var divIdName1 = "Account1" + num1;
        var newdiv1 = document.createElement('div');
        newdiv1.setAttribute("id", divIdName1);
        newdiv1.setAttribute("class", "col-sm-12");
        newdiv1.innerHTML = "<br><br>"+ "<div class='box box-info'>"+
                "<div class='box-header with-border'><h3 class='box-title'>Highlight</h3><div class='pull-right box-tools'><a  href=\"javascript:;\" onclick=\"removeEvent1213(\'" + divIdName1 + "\')\"><button type='button' class='btn btn-info btn-sm' data-widget='remove' data-toggle='tooltip' title='Remove'><i class='fa fa-times'></i></button></a></div></div>"+
                "<div class='box-body' style='background-color: rgba(63, 143, 199, 0.09);'>"+
                "<div class='form-group'>" +
                "<br><br><label class='col-sm-2 control-label'>Title</label>" +
                "<div class='col-sm-5'><input type='text' id=\"title" + num1 + "\"  name=\"title" + num1 + "\"  class='form-control'><br></div>"+
                "</div>"+
                "<div class='form-group'>" +
                "<label class='col-sm-2 control-label' for='content'>Content</label>" +
                "<div class='col-sm-10'><textarea  id=\"editor1" + num1 + "\"  name=\"content" + num1 + "\"  class='form-control'></textarea></div><br><br><br><br>" +
                "</div>"+
                "</div>";
        ni.appendChild(newdiv1);
        document.getElementById("i").value = document.getElementById("i").value + 1;
        var editor = CKEDITOR.replace('editor1' + num1,{
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
    }

    function removeEvent1213(divNum1, id) {
        var remove1 = document.getElementById('remove_id1').value;
        if (remove1 == "") {
            remove1 = id;
        }
        else {
            remove1 = remove1 + "," + id;
        }

        document.getElementById('remove_id1').value = remove1;
        var d1 = document.getElementById('myDiv1');
        var olddiv1 = document.getElementById(divNum1);
        d1.removeChild(olddiv1);
        document.getElementById("theValue1213").value = document.getElementById("theValue1213").value - 1;
    }
</script>
<script src="{{ URL::asset('assets/plugins/ckeditor/ckeditor.js')}}"></script>
<script src="{{ URL::asset('assets/plugins/ckeditor/ckfinder/ckfinder.js')}}"></script>

<input type="hidden" value="{{ trim($attribute_id,",") }}" name="attribute_id" id="attribute_id">
<input type="hidden" value="" name="remove_id1" id="remove_id1">
<input type="hidden" value="{{ !empty($count1)?$count1-1:''  }}" name="theValue1213" id="theValue1213"/>
<input type="hidden" value="{{ !empty($count1)?$count1-1:''  }}" name="i" id="i"/>