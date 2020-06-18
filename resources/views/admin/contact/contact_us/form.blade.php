{!! Form::hidden('redirects_to', URL::previous()) !!}

<div class="form-group{{ $errors->has('menu_name') ? ' has-error' : '' }}">
    <label class="col-sm-2 control-label" for="menu_name">Menu Name <span class="text-red">*</span></label>
    <div class="col-sm-5">
        {!! Form::text('menu_name', null, ['class' => 'form-control', 'placeholder' => 'Menu Name']) !!}
        @if ($errors->has('menu_name'))
            <span class="help-block">
                <strong>{{ $errors->first('menu_name') }}</strong>
            </span>
        @endif
    </div>
</div>

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


<div class="form-group{{ $errors->has('image') ? ' has-error' : '' }}">
    <label class="col-sm-2 control-label" for="image">image <span class="text-red">*</span></label>
    <div class="col-sm-3">
        <div class="">

            {!! Form::file('image', ['class' => '', 'id'=> 'image', 'onChange'=>'AjaxUploadImage(this)']) !!}
        </div>

        <?php
        if (!empty($contact->image) && $contact->image != "") {
        ?>
        <br><img id="DisplayImage" src="{{ url($contact->image) }}" name="img" id="img" width="150" style="padding-bottom:5px" >
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

<div class="form-group{{ $errors->has('address_map') ? ' has-error' : '' }}">
    <label class="col-sm-2 control-label" for="address_map">Address Map </label>
    <div class="col-sm-5">
        <input type='text' class="form-control" id="Address" name='address_map' placeholder="Enter address" value="<?php
        if (!empty($contact) && $contact['address_map'] != "") {
            echo $contact["address_map"];
        }
        ?>" />
    </div>
    <div class="col-sm-2">
    <a class="btn" onClick="call_latlong(document.getElementById('Address').value)" style="border:1px solid rgba(241,52,52,0.92);">Update Map Location <i class="icon-square-down position-right"></i></a>
    </div>

    <div class="col-sm-10" id="map" style="min-height:300px;margin-top: 50px;margin-left: 260px;" >
    </div>
</div>

<div class="form-group{{ $errors->has('language') ? ' has-error' : '' }}">
    <label class="col-sm-2 control-label" for="language">Language <span class="text-red">*</span></label>
    <div class="col-sm-5">
        {!! Form::select('language[]',\App\Contactus::$language, !empty($modes_selected)?$modes_selected:null, ['class' => 'select2 select2-hidden-accessible form-control', 'style' => 'width: 100%','multiple']) !!}
        @if ($errors->has('language'))
            <span class="help-block">
                <strong>{{ $errors->first('language') }}</strong>
            </span>
        @endif
    </div>
</div>



{{--@include('admin.contact.contact_us.email')--}}

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

<div class="form-group{{ $errors->has('status') ? ' has-error' : '' }}">
    <label class="col-sm-2 control-label" for="role">Status <span class="text-red">*</span></label>

    <div class="col-sm-5">

        @foreach (\App\Contactus::$status as $key => $value)
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

<input type="hidden" name="Latitude" id="Latitude" value="<?php
if (!empty($contact) && $contact['Latitude'] != "") {
    echo $contact["Latitude"];
}
?>">
<input type="hidden" name="Longitude" id="Longitude" value="<?php
if (!empty($contact) && $contact['Longitude'] != "") {
    echo $contact["Longitude"];
}
?>">

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC9VE1DP_XUwvuN4e5sT5hFy8HZu-gZUAU&callback=initMap" async defer></script>

<script>
    var map;
    var markersArray = [];
            <?php
            if (!empty($contact) && !empty($contact["Latitude"]) && !empty($contact["Longitude"])) {
                echo 'var myLatLng = {lat: ' . $contact["Latitude"] . ', lng: ' . $contact["Longitude"] . '};';
            } else {
                echo 'var myLatLng = {lat: 22.28552, lng: 114.15769};';
            }
            ?>
    var latitude;
    var longitude;
    var myLatLng;
    function call_latlong(addr)
    {
        //var myLatLng="";
        var geocoder = new google.maps.Geocoder();


        geocoder.geocode({'address': addr}, function(results, status) {

            if (status == google.maps.GeocoderStatus.OK) {
                latitude = results[0].geometry.location.lat();
                longitude = results[0].geometry.location.lng();
                $("#Latitude").val(latitude);
                $("#Longitude").val(longitude);
                myLatLng = {lat: latitude, lng: longitude};

                // initMap();
                map = new google.maps.Map(document.getElementById('map'), {
                    center: myLatLng,
                    zoom: 12
                });
                var marker = new google.maps.Marker({
                    position: myLatLng,
                    map: map,
                    //title: 'Hello World!'
                });
                placeMarker(myLatLng);
                marker.setMap(null);

                google.maps.event.addListener(map, 'click', function(event) {
                    placeMarker(event.latLng);
                    // Remove Current Marker
                    marker.setMap(null);
                    $("#Latitude").val(event.latLng.lat());
                    $("#Longitude").val(event.latLng.lng());

                    var latlng = new google.maps.LatLng(event.latLng.lat(), event.latLng.lng());
                    var geocoder = geocoder = new google.maps.Geocoder();
                    geocoder.geocode({'latLng': latlng}, function(results, status) {
                        if (status == google.maps.GeocoderStatus.OK) {
                            if (results[0]) {
                                $('#Address').val(results[0].formatted_address);
                                //alert("Location: " + results[0].formatted_address);
                            }
                        }
                    });
                    //document.getElementById('latlng').innerHTML = event.latLng.lat() + ', ' + event.latLng.lng()
                });

            }
        });
    }

    function initMap() {

        map = new google.maps.Map(document.getElementById('map'), {
            center: myLatLng,
            zoom: 12
        });

        var marker = new google.maps.Marker({
            position: myLatLng,
            map: map,
            //title: 'Hello World!'
        });



        google.maps.event.addListener(map, 'click', function(event) {
            placeMarker(event.latLng);
            // Remove Current Marker
            marker.setMap(null);
            $("#Latitude").val(event.latLng.lat());
            $("#Longitude").val(event.latLng.lng());

            var latlng = new google.maps.LatLng(event.latLng.lat(), event.latLng.lng());
            var geocoder = geocoder = new google.maps.Geocoder();
            geocoder.geocode({'latLng': latlng}, function(results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    if (results[0]) {
                        $('#Address').val(results[0].formatted_address);
                        //alert("Location: " + results[0].formatted_address);
                    }
                }
            });
            //document.getElementById('latlng').innerHTML = event.latLng.lat() + ', ' + event.latLng.lng()
        });


    }

    function clearMarkers() {
        setMapOnAll(null);
    }


    function placeMarker(location) {
        // first remove all markers if there are any
        deleteOverlays();

        var marker = new google.maps.Marker({
            position: location,
            map: map
        });

        // add marker in markers array
        markersArray.push(marker);

        //map.setCenter(location);
    }

    // Deletes all markers in the array by removing references to them
    function deleteOverlays() {
        if (markersArray) {
            for (i in markersArray) {
                markersArray[i].setMap(null);
            }
            markersArray.length = 0;
        }
    }


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

</script>