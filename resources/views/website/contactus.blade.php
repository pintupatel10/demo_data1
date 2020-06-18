<!doctype html>
<html>
<head>
   @include('website.header')
    <title>Gray Line Tours | ContactUs</title>
    <link rel="icon" href="{!! asset('favicon.ico') !!}"/>

</head>
<body>
@include('website.menu')
<div class="col-md-10 col-lg-10 col-sm-10 contact_us_bg" style="padding:20px; height: 101%;background: url({{url($contact->image)}});background-repeat: no-repeat;background-size: cover;background-position: center;">

    <div class="col-md-12 contact_us_form_bg animated zoomInUp">
        <div class="clearfix">&nbsp;</div>
        <span class="blue-color simple_title" style="padding-left:5px;">{{$contact['title']}}</span>
        <hr class="contact_us_hr" style="margin-top: 0">
        <div class="col-md-8 text-left padding-0">
            {!! $contact['description'] !!}
            </div>
        <div class="col-md-4">
            <div id="map"></div>
            <form name="from1">
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
            </form>
            <script>
                function initMap() {
                    var latitude=document.getElementById('Latitude').value;
                    var longitude = document.getElementById('Longitude').value;

                    var uluru = {lat: +latitude , lng: +longitude};
                   // var uluru = {lat: 22.298040, lng: 114.172438};
                    var map = new google.maps.Map(document.getElementById('map'), {
                        zoom: 18,
                        center: uluru
                    });
                    var marker = new google.maps.Marker({
                        position: uluru,
                        map: map
                    });
                }
            </script>
            <script async defer
                    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBEzFt7nv03AKkUpOFMaPNpZgxcQ0fttYs&callback=initMap">
            </script>
        </div>
        <div class="clearfix" style="padding:20px">&nbsp;</div>

        @if($cookie=='English')
            @if(Illuminate\Support\Facades\Session::has('success'))<div style="color:green;" class="alert alert-info"> Thank you for your enquiry.  We will contact you within 24 hours. </div> @endif
        <span class="blue-color simple_title" style="padding-left:5px;"> Contact Us</span>
        <hr class="contact_us_hr" style="margin-top: 0">
        <div class="col-md-12 padding-0">
            {!! Form::open(['url' => url('contactus/store'),'class' => 'form-horizontal','files'=>true]) !!}

            <div class="col-md-2 label padding-left-right-0">Title </div>
            <div class="col-md-4">
                {{--<select name="title" class="form-control" style="height:28px;">--}}
                    {{--<option value="">Please Select</option>--}}
                    {{--<option value="Mr">Mr</option>--}}
                    {{--<option value="Mrs">Mrs</option>--}}
                    {{--<option value="Miss">Miss</option>--}}
                {{--</select>--}}
                {!! Form::select('title', \App\TicketPrice::$title_eng, null, ['class' => 'select2 select2-hidden-accessible form-control', 'style' => 'height:28px;']) !!}
                @if ($errors->has('title'))<span class="help-block"><strong>{{ $errors->first('title') }}</strong></span> @endif
            </div>
            <div class="col-md-2 label padding-left-right-0">Last Name</div>
            <div class="col-md-4">
                {{--<input name="lastname" type="text" class="form-control">--}}
                {!! Form::text('lastname', null, ['class' => 'form-control']) !!}
                @if ($errors->has('lastname'))<span class="help-block"><strong>{{ $errors->first('lastname') }}</strong></span> @endif
            </div>
            <div class="clearfix">&nbsp;</div>
            <div class="col-md-2 label padding-left-right-0">First Name</div>
            <div class="col-md-4">
                {{--<input name="firstname"type="text" class="form-control">--}}
                {!! Form::text('firstname', null, ['class' => 'form-control']) !!}
                @if ($errors->has('firstname'))<span class="help-block"><strong>{{ $errors->first('firstname') }}</strong></span> @endif

            </div>
            <div class="col-md-2 label padding-left-right-0">Email Address</div>
            <div class="col-md-4">
                {{--<input name="email"type="email" class="form-control">--}}
                {!! Form::email('email', null, ['class' => 'form-control']) !!}
                @if ($errors->has('email'))<span class="help-block"><strong>{{ $errors->first('email') }}</strong></span> @endif
            </div>
            <div class="clearfix">&nbsp;</div>
            <div class="col-md-2 label padding-left-right-0">Telephone no:</div>
            <div class="col-md-4">
                {{--<input name="telephone" type="text" class="form-control">--}}
                {!! Form::text('telephone', null, ['class' => 'form-control']) !!}
            </div>
            <div class="col-md-2 label padding-left-right-0">Contact Address : </div>
            <div class="col-md-4">
                {{--<input name="address" type="email" class="form-control">--}}
                {!! Form::text('address', null, ['class' => 'form-control']) !!}
            </div>
            <div class="clearfix">&nbsp;</div>
            <div class="col-md-2 label padding-left-right-0">Fax no: </div>
            <div class="col-md-4">
                {{--<input name="fax_no" type="text" class="form-control">--}}
                {!! Form::text('fax_no', null, ['class' => 'form-control']) !!}
            </div>
            <div class="col-md-2 label padding-left-right-0">Home Country / Territory : </div>
            <div class="col-md-4">
                {{--<input name="country" type="text" class="form-control">--}}
                {!! Form::text('country', null, ['class' => 'form-control']) !!}
            </div>
            <div class="clearfix">&nbsp;</div>
            <div class="col-md-2 label padding-left-right-0">Your Message : </div>
            <div class="col-md-10">
                {{--<textarea  name="message"class="form-control" style="height: 200px;"></textarea>--}}
                {!! Form::textarea('message', null, ['class' => 'form-control','style' => 'height:200px;']) !!}
                @if ($errors->has('message'))<span class="help-block"><strong>{{ $errors->first('message') }}</strong></span> @endif
            </div>
            <div class="clearfix">&nbsp;</div>
            <div class="col-md-12 text-right">
                <input type="submit"  class="btn btn-submit-jasbir" value="Send">
            </div>
            {!! Form::close() !!}
            <div class="clearfix" style="padding: 20px;">&nbsp;</div>
        </div>
        @endif
        @if($cookie=='繁中')
            @if(Illuminate\Support\Facades\Session::has('success'))<div style="color:green;" class="alert alert-info"> 感謝你的查詢，我們將於24 小時內與你聯絡。 </div> @endif

            <span class="blue-color simple_title" style="padding-left:5px;"> 聯絡我們</span>
                <hr class="contact_us_hr" style="margin-top: 0">
                <div class="col-md-12 padding-0">
                    {!! Form::open(['url' => url('contactus/store'),'class' => 'form-horizontal','files'=>true]) !!}

                    <div class="col-md-2 label padding-left-right-0">稱謂 </div>
                    <div class="col-md-4">
                        {{--<select name="title" class="form-control" style="height:28px;">--}}
                            {{--<option value="">請選擇</option>--}}
                            {{--<option value="Mr">先生</option>--}}
                            {{--<option value="Mrs">太太</option>--}}
                            {{--<option value="Miss">小姐</option>--}}
                        {{--</select>--}}
                        {!! Form::select('title', \App\TicketPrice::$title_td, null, ['class' => 'select2 select2-hidden-accessible form-control', 'style' => 'height:28px;']) !!}
                        @if ($errors->has('title'))
                            <span class="help-block">
                         <strong>{{ $errors->first('title') }}</strong>
                       </span>
                        @endif
                    </div>
                    <div class="col-md-2 label padding-left-right-0">姓氏</div>
                    <div class="col-md-4">
                        {{--<input name="lastname" type="text" class="form-control">--}}
                        {!! Form::text('lastname', null, ['class' => 'form-control']) !!}
                        @if ($errors->has('lastname'))<span class="help-block"><strong>{{ $errors->first('lastname') }}</strong></span> @endif

                    </div>
                    <div class="clearfix">&nbsp;</div>
                    <div class="col-md-2 label padding-left-right-0">名稱</div>
                    <div class="col-md-4">
                        {{--<input name="firstname"type="text" class="form-control">--}}
                        {!! Form::text('firstname', null, ['class' => 'form-control']) !!}
                        @if ($errors->has('firstname'))<span class="help-block"><strong>{{ $errors->first('firstname') }}</strong></span> @endif

                    </div>
                    <div class="col-md-2 label padding-left-right-0">電郵</div>
                    <div class="col-md-4">
                        {{--<input name="email"type="email" class="form-control">--}}
                        {!! Form::email('email', null, ['class' => 'form-control']) !!}
                        @if ($errors->has('email'))<span class="help-block"><strong>{{ $errors->first('email') }}</strong></span> @endif

                    </div>
                    <div class="clearfix">&nbsp;</div>
                    <div class="col-md-2 label padding-left-right-0">電話號碼:</div>
                    <div class="col-md-4">
                        {{--<input name="telephone" type="text" class="form-control">--}}
                        {!! Form::text('telephone', null, ['class' => 'form-control']) !!}
                    </div>
                    <div class="col-md-2 label padding-left-right-0"> 聯繫地址 : </div>
                    <div class="col-md-4">
                        {{--<input name="address" type="email" class="form-control">--}}
                        {!! Form::text('address', null, ['class' => 'form-control']) !!}
                    </div>
                    <div class="clearfix">&nbsp;</div>
                    <div class="col-md-2 label padding-left-right-0">Fax 號碼: </div>
                    <div class="col-md-4">
                        {{--<input name="fax_no" type="text" class="form-control">--}}
                        {!! Form::text('fax_no', null, ['class' => 'form-control']) !!}
                    </div>
                    <div class="col-md-2 label padding-left-right-0">國家/地區: </div>
                    <div class="col-md-4">
                        {{--<input name="country" type="text" class="form-control">--}}
                        {!! Form::text('country', null, ['class' => 'form-control']) !!}
                    </div>
                    <div class="clearfix">&nbsp;</div>
                    <div class="col-md-2 label padding-left-right-0">訊息 : </div>
                    <div class="col-md-10">
                        {{--<textarea  name="message" class="form-control" style="height: 200px;"></textarea>--}}
                        {!! Form::textarea('message', null, ['class' => 'form-control','style' => 'height:200px;']) !!}
                        @if ($errors->has('message'))<span class="help-block"><strong>{{ $errors->first('message') }}</strong></span> @endif
                    </div>
                    <div class="clearfix">&nbsp;</div>
                    <div class="col-md-12 text-right">
                        <input type="submit"  class="btn btn-submit-jasbir" value="發送">
                    </div>
                    {!! Form::close() !!}
                    <div class="clearfix" style="padding: 20px;">&nbsp;</div>
                </div>
            @endif

        @if($cookie=='簡')
            @if(Illuminate\Support\Facades\Session::has('success'))<div style="color:green;" class="alert alert-info"> 感谢你的查询，我们将于24 小时内与你联络。 </div> @endif

            <span class="blue-color simple_title" style="padding-left:5px;"> 联络我们</span>
            <hr class="contact_us_hr" style="margin-top: 0">
            <div class="col-md-12 padding-0">
                {!! Form::open(['url' => url('contactus/store'),'class' => 'form-horizontal','files'=>true]) !!}

                <div class="col-md-2 label padding-left-right-0">称谓 </div>
                <div class="col-md-4">
                    {{--<select name="title" class="form-control" style="height:28px;">--}}
                        {{--<option value="">请选择</option>--}}
                        {{--<option value="Mr">先生</option>--}}
                        {{--<option value="Mrs">太太</option>--}}
                        {{--<option value="Miss">小姐</option>--}}
                    {{--</select>--}}
                    {!! Form::select('title', \App\TicketPrice::$title_sp, null, ['class' => 'select2 select2-hidden-accessible form-control', 'style' => 'height:28px;']) !!}
                    @if ($errors->has('title'))
                        <span class="help-block">
                <strong>{{ $errors->first('title') }}</strong>
            </span>
                    @endif
                </div>
                <div class="col-md-2 label padding-left-right-0">姓氏</div>
                <div class="col-md-4">
                    {{--<input name="lastname" type="text" class="form-control">--}}
                    {!! Form::text('lastname', null, ['class' => 'form-control']) !!}
                    @if ($errors->has('lastname'))<span class="help-block"><strong>{{ $errors->first('lastname') }}</strong></span> @endif
                </div>
                <div class="clearfix">&nbsp;</div>
                <div class="col-md-2 label padding-left-right-0">名称</div>
                <div class="col-md-4">
                    {{--<input name="firstname"type="text" class="form-control">--}}
                    {!! Form::text('firstname', null, ['class' => 'form-control']) !!}
                    @if ($errors->has('firstname'))<span class="help-block"><strong>{{ $errors->first('firstname') }}</strong></span> @endif

                </div>
                <div class="col-md-2 label padding-left-right-0">电邮</div>
                <div class="col-md-4">
                    {{--<input name="email"type="email" class="form-control">--}}
                    {!! Form::email('email', null, ['class' => 'form-control']) !!}
                    @if ($errors->has('email'))<span class="help-block"><strong>{{ $errors->first('email') }}</strong></span> @endif
                </div>
                <div class="clearfix">&nbsp;</div>
                <div class="col-md-2 label padding-left-right-0">电话号码：</div>
                <div class="col-md-4">
                    {{--<input name="telephone" type="text" class="form-control">--}}
                    {!! Form::text('telephone', null, ['class' => 'form-control']) !!}
                </div>
                <div class="col-md-2 label padding-left-right-0">联系地址: </div>
                <div class="col-md-4">
                    {{--<input name="address" type="email" class="form-control">--}}
                    {!! Form::text('address', null, ['class' => 'form-control']) !!}
                </div>
                <div class="clearfix">&nbsp;</div>
                <div class="col-md-2 label padding-left-right-0">Fax 号码: </div>
                <div class="col-md-4">
                    {{--<input name="fax_no" type="text" class="form-control">--}}
                    {!! Form::text('fax_no', null, ['class' => 'form-control']) !!}
                </div>
                <div class="col-md-2 label padding-left-right-0">国家/地区: </div>
                <div class="col-md-4">
                    {{--<input name="country" type="text" class="form-control">--}}
                    {!! Form::text('country', null, ['class' => 'form-control']) !!}
                </div>
                <div class="clearfix">&nbsp;</div>
                <div class="col-md-2 label padding-left-right-0">讯息 : </div>
                <div class="col-md-10">
                    {{--<textarea  name="message"class="form-control" style="height: 200px;"></textarea>--}}
                    {!! Form::textarea('message', null, ['class' => 'form-control','style' => 'height:200px;']) !!}
                    @if ($errors->has('message'))<span class="help-block"><strong>{{ $errors->first('message') }}</strong></span> @endif
                </div>
                <div class="clearfix">&nbsp;</div>
                <div class="col-md-12 text-right">
                    <input type="submit"  class="btn btn-submit-jasbir" value="发送">
                </div>
                {!! Form::close() !!}
                <div class="clearfix" style="padding: 20px;">&nbsp;</div>
            </div>
        @endif

    </div>
    <div class="clearfix" style="padding:40px;">&nbsp;</div>
</div>
@include('website.footer')
</body>
</html>