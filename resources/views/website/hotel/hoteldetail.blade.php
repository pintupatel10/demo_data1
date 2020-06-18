<!doctype html>
<html>
<head>
    @include('website.header')
    <title>Gray Line Tours | {{$hotel->title}}</title>
    <link rel="icon" href="{!! asset('favicon.ico') !!}"/>

</head>
<body>
@include('website.menu')
<div class="col-md-10 col-lg-10 col-sm-10 contact_us_bg" style="padding:20px;background: url({{url($hotel->image)}});">
    <div class="col-md-12 contact_us_form_bg animated zoomInUp" style="padding:19px 19px 0px 2px;background-color: rgba(255, 255, 255, 0.96)!important;">
        <div class="row mg0">
            <div class="col-md-7 col-sm-7 text-left">
                <div class="col-md-12 padding-0">
                    <img src="{{url($hotel->image)}}" class="img-responsive">
                </div>
                <span class="blue-color simple_title" style="margin-bottom:10px;margin-top:20px;float: left;width: 100%">{{$hotel->title}}</span>
               {!! $hotel->description !!}
                <div class="clearfix">&nbsp;</div>
            </div>
            <div class="col-md-5 col-sm-5 text-left padding-0 xs-class">
                @if($cookie == "English")
                    @if(Illuminate\Support\Facades\Session::has('success'))<div style="color:green;" class="alert alert-info"> Thank you for your enquiry.  We will contact you within 24 hours. </div> @endif

                    <span class="blue-color simple_title" style="padding-left:5px;"> Contact Us</span>
                <hr class="contact_us_hr" style="margin-top: 0">
                    {!! Form::open(['url' => url('hotelcontact/store/'.$hotel->id),'class' => 'form-horizontal','files'=>true]) !!}
                    <div class="col-md-12 padding-0">
                    <div class="col-md-12 padding-0">
                        <div class="col-md-4 label padding-left-right-0">Title </div>
                        <div class="col-md-8 padding-0">
                            {{--<select name="title" class="form-control" style="height:28px;">--}}
                                {{--<option value="">Please Select</option>--}}
                                {{--<option value="Mr">Mr</option>--}}
                                {{--<option value="Mrs">Mrs</option>--}}
                                {{--<option value="Miss">Miss</option>--}}
                            {{--</select>--}}
                            {!! Form::select('title', \App\TicketPrice::$title_eng, null, ['class' => 'select2 select2-hidden-accessible form-control', 'style' => 'height:28px;']) !!}
                            @if ($errors->has('title'))<span class="help-block"><strong>{{ $errors->first('title') }}</strong></span> @endif
                        </div>
                    </div>
                    <div class="col-md-12 padding-0">
                        <div class="clearfix">&nbsp;</div>
                        <div class="col-md-4 label padding-left-right-0">First Name </div>
                        <div class="col-md-8 padding-0">
                            {{--<input name="firstname" type="text" class="form-control">--}}
                            {!! Form::text('firstname', null, ['class' => 'form-control']) !!}
                            @if ($errors->has('firstname'))<span class="help-block"><strong>{{ $errors->first('firstname') }}</strong></span> @endif
                        </div>
                    </div>
                    <div class="col-md-12 padding-0">
                        <div class="clearfix">&nbsp;</div>
                        <div class="col-md-4 label padding-left-right-0">Last Name </div>
                        <div class="col-md-8 padding-0">
                            {{--<input name="lastname" type="text" class="form-control">--}}
                            {!! Form::text('lastname', null, ['class' => 'form-control']) !!}
                            @if ($errors->has('lastname'))<span class="help-block"><strong>{{ $errors->first('lastname') }}</strong></span> @endif
                        </div>
                    </div>
                    <div class="col-md-12 padding-0">
                        <div class="clearfix">&nbsp;</div>
                        <div class="col-md-4 label padding-left-right-0">Email Address </div>
                        <div class="col-md-8 padding-0">
                            {{--<input name="email"type="email" class="form-control">--}}
                            {!! Form::email('email', null, ['class' => 'form-control']) !!}
                            @if ($errors->has('email'))<span class="help-block"><strong>{{ $errors->first('email') }}</strong></span> @endif
                        </div>
                    </div>
                    <div class="col-md-12 padding-0">
                        <div class="clearfix">&nbsp;</div>
                        <div class="col-md-4 label padding-left-right-0">Telephone no: </div>
                        <div class="col-md-8 padding-0">
                            {{--<input name="telephone" type="text" class="form-control">--}}
                            {!! Form::text('telephone', null, ['class' => 'form-control']) !!}
                        </div>
                    </div>
                    <div class="col-md-12 padding-0">
                        <div class="clearfix">&nbsp;</div>
                        <div class="col-md-4 label padding-left-right-0">Contact Address : </div>
                        <div class="col-md-8 padding-0">
                            {{--<input name="address" type="email" class="form-control">--}}
                            {!! Form::text('address', null, ['class' => 'form-control']) !!}
                        </div>
                    </div>
                    <div class="col-md-12 padding-0">
                        <div class="clearfix">&nbsp;</div>
                        <div class="col-md-4 label padding-left-right-0">Fax no: </div>
                        <div class="col-md-8 padding-0">
                            {{--<input name="fax_no" type="text" class="form-control">--}}
                            {!! Form::text('fax_no', null, ['class' => 'form-control']) !!}
                        </div>
                    </div>
                    <div class="col-md-12 padding-0">
                        <div class="clearfix">&nbsp;</div>
                        <div class="col-md-4 label padding-left-right-0">Home Country : </div>
                        <div class="col-md-8 padding-0">
                            {{--<input name="country" type="text" class="form-control">--}}
                            {!! Form::text('country', null, ['class' => 'form-control']) !!}
                        </div>
                    </div>
                    <div class="col-md-12 padding-0">
                        <div class="clearfix">&nbsp;</div>
                        <div class="col-md-4 label padding-left-right-0">Your Message : </div>
                        <div class="col-md-8 padding-0">
                            {{--<textarea  name="message"class="form-control"></textarea>--}}
                            {!! Form::textarea('message', null, ['class' => 'form-control','style' => 'height:100px;']) !!}
                            @if ($errors->has('message'))<span class="help-block"><strong>{{ $errors->first('message') }}</strong></span> @endif
                        </div>
                    </div>
                    <div class="clearfix">&nbsp;</div>
                    <div class="col-md-12 padding-0 text-right">
                        <input type="submit"  class="btn btn-submit-jasbir" value="Send">
                    </div>
                    <div class="clearfix" >&nbsp;</div>
                </div>
                    {!! Form::close() !!}
                 @endif
                    @if($cookie == "繁中")
                        @if(Illuminate\Support\Facades\Session::has('success'))<div style="color:green;" class="alert alert-info"> 感謝你的查詢，我們將於24 小時內與你聯絡。 </div> @endif

                        <span class="blue-color simple_title" style="padding-left:5px;"> 聯絡我們</span>
                        <hr class="contact_us_hr" style="margin-top: 0">
                        {!! Form::open(['url' => url('hotelcontact/store/'.$hotel->id),'class' => 'form-horizontal','files'=>true]) !!}
                        <div class="col-md-12 padding-0">
                            <div class="col-md-12 padding-0">
                                <div class="col-md-4 label padding-left-right-0">稱謂 </div>
                                <div class="col-md-8 padding-0">
                                    {{--<select name="title" class="form-control" style="height:28px;">--}}
                                        {{--<option value="">請選擇</option>--}}
                                        {{--<option value="Mr">先生</option>--}}
                                        {{--<option value="Mrs">太太</option>--}}
                                        {{--<option value="Miss">小姐</option>--}}
                                    {{--</select>--}}
                                    {!! Form::select('title', \App\TicketPrice::$title_td, null, ['class' => 'select2 select2-hidden-accessible form-control', 'style' => 'height:28px;']) !!}
                                    @if ($errors->has('title'))<span class="help-block"><strong>{{ $errors->first('title') }}</strong></span> @endif
                                </div>
                            </div>
                            <div class="col-md-12 padding-0">
                                <div class="clearfix">&nbsp;</div>
                                <div class="col-md-4 label padding-left-right-0">名稱 </div>
                                <div class="col-md-8 padding-0">
                                    {{--<input name="firstname" type="text" class="form-control">--}}
                                    {!! Form::text('firstname', null, ['class' => 'form-control']) !!}
                                    @if ($errors->has('firstname'))<span class="help-block"><strong>{{ $errors->first('firstname') }}</strong></span> @endif
                                </div>
                            </div>
                            <div class="col-md-12 padding-0">
                                <div class="clearfix">&nbsp;</div>
                                <div class="col-md-4 label padding-left-right-0">姓氏 </div>
                                <div class="col-md-8 padding-0">
                                    {{--<input name="lastname" type="text" class="form-control">--}}
                                    {!! Form::text('lastname', null, ['class' => 'form-control']) !!}
                                    @if ($errors->has('lastname'))<span class="help-block"><strong>{{ $errors->first('lastname') }}</strong></span> @endif
                                </div>
                            </div>
                            <div class="col-md-12 padding-0">
                                <div class="clearfix">&nbsp;</div>
                                <div class="col-md-4 label padding-left-right-0">電郵 </div>
                                <div class="col-md-8 padding-0">
                                    {{--<input name="email"type="email" class="form-control">--}}
                                    {!! Form::email('email', null, ['class' => 'form-control']) !!}
                                    @if ($errors->has('email'))<span class="help-block"><strong>{{ $errors->first('email') }}</strong></span> @endif
                                </div>
                            </div>
                            <div class="col-md-12 padding-0">
                                <div class="clearfix">&nbsp;</div>
                                <div class="col-md-4 label padding-left-right-0">電話號碼: </div>
                                <div class="col-md-8 padding-0">
                                    {{--<input name="telephone" type="text" class="form-control">--}}
                                    {!! Form::text('telephone', null, ['class' => 'form-control']) !!}
                                </div>
                            </div>
                            <div class="col-md-12 padding-0">
                                <div class="clearfix">&nbsp;</div>
                                <div class="col-md-4 label padding-left-right-0">聯繫地址 : </div>
                                <div class="col-md-8 padding-0">
                                    {{--<input name="address" type="email" class="form-control">--}}
                                    {!! Form::text('address', null, ['class' => 'form-control']) !!}
                                </div>
                            </div>
                            <div class="col-md-12 padding-0">
                                <div class="clearfix">&nbsp;</div>
                                <div class="col-md-4 label padding-left-right-0">Fax 號碼: </div>
                                <div class="col-md-8 padding-0">
                                    {{--<input name="fax_no" type="text" class="form-control">--}}
                                    {!! Form::text('fax_no', null, ['class' => 'form-control']) !!}
                                </div>
                            </div>
                            <div class="col-md-12 padding-0">
                                <div class="clearfix">&nbsp;</div>
                                <div class="col-md-4 label padding-left-right-0">國家/地區: </div>
                                <div class="col-md-8 padding-0">
                                    {{--<input name="country" type="text" class="form-control">--}}
                                    {!! Form::text('country', null, ['class' => 'form-control']) !!}
                                </div>
                            </div>
                            <div class="col-md-12 padding-0">
                                <div class="clearfix">&nbsp;</div>
                                <div class="col-md-4 label padding-left-right-0">訊息 : </div>
                                <div class="col-md-8 padding-0">
                                    {{--<textarea  name="message"class="form-control"></textarea>--}}
                                    {!! Form::textarea('message', null, ['class' => 'form-control','style' => 'height:100px;']) !!}
                                    @if ($errors->has('message'))<span class="help-block"><strong>{{ $errors->first('message') }}</strong></span> @endif
                                </div>
                            </div>
                            <div class="clearfix">&nbsp;</div>
                            <div class="col-md-12 padding-0 text-right">
                                <input type="submit"  class="btn btn-submit-jasbir" value="發送">
                            </div>
                            <div class="clearfix" >&nbsp;</div>
                        </div>
                        {!! Form::close() !!}

                    @endif

                    @if($cookie == "簡")
                        @if(Illuminate\Support\Facades\Session::has('success'))<div style="color:green;" class="alert alert-info"> 感谢你的查询，我们将于24 小时内与你联络。 </div> @endif

                        <span class="blue-color simple_title" style="padding-left:5px;"> 联络我们</span>
                        <hr class="contact_us_hr" style="margin-top: 0">
                        {!! Form::open(['url' => url('hotelcontact/store/'.$hotel->id),'class' => 'form-horizontal','files'=>true]) !!}
                        <div class="col-md-12 padding-0">
                            <div class="col-md-12 padding-0">
                                <div class="col-md-4 label padding-left-right-0">称谓 </div>
                                <div class="col-md-8 padding-0">
                                    {{--<select name="title" class="form-control" style="height:28px;">--}}
                                        {{--<option value="">请选择</option>--}}
                                        {{--<option value="Mr">先生</option>--}}
                                        {{--<option value="Mrs">太太</option>--}}
                                        {{--<option value="Miss">小姐</option>--}}
                                    {{--</select>--}}
                                    {!! Form::select('title', \App\TicketPrice::$title_sp, null, ['class' => 'select2 select2-hidden-accessible form-control', 'style' => 'height:28px;']) !!}
                                    @if ($errors->has('title'))<span class="help-block"><strong>{{ $errors->first('title') }}</strong></span> @endif
                                </div>
                            </div>
                            <div class="col-md-12 padding-0">
                                <div class="clearfix">&nbsp;</div>
                                <div class="col-md-4 label padding-left-right-0">名称 </div>
                                <div class="col-md-8 padding-0">
                                    {{--<input name="firstname" type="text" class="form-control">--}}
                                    {!! Form::text('firstname', null, ['class' => 'form-control']) !!}
                                    @if ($errors->has('firstname'))<span class="help-block"><strong>{{ $errors->first('firstname') }}</strong></span> @endif
                                </div>
                            </div>
                            <div class="col-md-12 padding-0">
                                <div class="clearfix">&nbsp;</div>
                                <div class="col-md-4 label padding-left-right-0">姓氏 </div>
                                <div class="col-md-8 padding-0">
                                    {{--<input name="lastname" type="text" class="form-control">--}}
                                    {!! Form::text('lastname', null, ['class' => 'form-control']) !!}
                                    @if ($errors->has('lastname'))<span class="help-block"><strong>{{ $errors->first('lastname') }}</strong></span> @endif
                                </div>
                            </div>
                            <div class="col-md-12 padding-0">
                                <div class="clearfix">&nbsp;</div>
                                <div class="col-md-4 label padding-left-right-0">电邮 </div>
                                <div class="col-md-8 padding-0">
                                    {{--<input name="email"type="email" class="form-control">--}}
                                    {!! Form::email('email', null, ['class' => 'form-control']) !!}
                                    @if ($errors->has('email'))<span class="help-block"><strong>{{ $errors->first('email') }}</strong></span> @endif
                                </div>
                            </div>
                            <div class="col-md-12 padding-0">
                                <div class="clearfix">&nbsp;</div>
                                <div class="col-md-4 label padding-left-right-0">电话号码: </div>
                                <div class="col-md-8 padding-0">
                                    {{--<input name="telephone" type="text" class="form-control">--}}
                                    {!! Form::text('telephone', null, ['class' => 'form-control']) !!}
                                </div>
                            </div>
                            <div class="col-md-12 padding-0">
                                <div class="clearfix">&nbsp;</div>
                                <div class="col-md-4 label padding-left-right-0">联系地址 : </div>
                                <div class="col-md-8 padding-0">
                                    {{--<input name="address" type="email" class="form-control">--}}
                                    {!! Form::text('address', null, ['class' => 'form-control']) !!}
                                </div>
                            </div>
                            <div class="col-md-12 padding-0">
                                <div class="clearfix">&nbsp;</div>
                                <div class="col-md-4 label padding-left-right-0">Fax 号码: </div>
                                <div class="col-md-8 padding-0">
                                    {{--<input name="fax_no" type="text" class="form-control">--}}
                                    {!! Form::text('fax_no', null, ['class' => 'form-control']) !!}
                                </div>
                            </div>
                            <div class="col-md-12 padding-0">
                                <div class="clearfix">&nbsp;</div>
                                <div class="col-md-4 label padding-left-right-0">国家/地区 : </div>
                                <div class="col-md-8 padding-0">
                                    {{--<input name="country" type="text" class="form-control">--}}
                                    {!! Form::text('country', null, ['class' => 'form-control']) !!}
                                </div>
                            </div>
                            <div class="col-md-12 padding-0">
                                <div class="clearfix">&nbsp;</div>
                                <div class="col-md-4 label padding-left-right-0">讯息 : </div>
                                <div class="col-md-8 padding-0">
                                    {{--<textarea  name="message"class="form-control"></textarea>--}}
                                    {!! Form::textarea('message', null, ['class' => 'form-control','style' => 'height:100px;']) !!}
                                    @if ($errors->has('message'))<span class="help-block"><strong>{{ $errors->first('message') }}</strong></span> @endif
                                </div>
                            </div>
                            <div class="clearfix">&nbsp;</div>
                            <div class="col-md-12 padding-0 text-right">
                                <input type="submit"  class="btn btn-submit-jasbir" value="发送">
                            </div>
                            <div class="clearfix" >&nbsp;</div>
                        </div>
                        {!! Form::close() !!}
                    @endif
            </div>
        </div>
    </div>
</div>
   @include('website.footer')
        <script>
        $('.nav.navbar-nav.side-nav > li').click(function () {

            $("li.active").removeClass("active");

            $(this).addClass('active');
        });
    </script>
</body>
</html>