<!doctype html>
<html>
<head>
  @include('website.header')
    <title>Gray Line Tours | {{$transportation->title}}</title>
    <link rel="icon" href="{!! asset('favicon.ico') !!}"/>

</head>
<body>
@include('website.menu')
<div class="col-md-10 col-lg-10 col-sm-10 padding-0">
    <div class="col-md-2 tour_details_single_banner_bg affix" style="background: url({{url($transportation->image)}});background-repeat: no-repeat;background-size: cover;background-position: center;"> </div>
    <div class="col-md-10 col-lg-10 col-sm-10 padding-left-100-desktop margin-left-15per-desktop" style="margin-left: 19%;">
        <div class="col-md-12" style="padding-top:20px; padding-left:0"> <a href="{{url('transportation/'.$menuid.'/fulllist/'.$transportation->id)}}"><img src="{{URL::asset('website/assets/img/chevron-circle-outline.png')}}" style="height:40px; width:40px; padding-right:0px;">
                @if($cookie == 'English')
                    Back
                @elseif($cookie == '繁中')
                    返回
                @elseif($cookie == '簡')
                    返回
                @endif
            </a></div>
        <div class="clearfix" style="padding:10px;">&nbsp;</div>
        <div class="tour_details_single_text">
            <h2 class="font-sky-color font-eligible-regular">{{$transportation->title}}</h2>
            <br>
            {!! $transportation->description !!}
        </div>
        <div class="clearfix" style="padding:10px;">&nbsp;</div>
        <div class="tour_details_gallery">
                <div class="row">
                    @foreach($checkpoints as $checkpoint)
                        <div class="col-md-3 padding-top-bottom-10 padding-left-0">
                            <div class="gallery-img">
                                <ul class="img-list">
                                    <li> <img src="{{url($checkpoint->image)}}" class="img-responsive gallery_img" alt="media temple"> <span class="text-content"><span><a href="" data-toggle="modal" data-target="#myModal{{$checkpoint->id}}">{{$checkpoint->title}}</a></span></span> </li>
                                    <span class="image_gallery_title">{{$checkpoint->title}}</span>
                                </ul>
                            </div>
                        </div>
                    @endforeach
                </div>
         </div>
        <hr class="blue-hr">

        <div class="col-md-12">
            @foreach($transportation['Transportationhighlight'] as $highlight)
                <span class="font-sky-color font-eligible-regular" style="font-size: 20px;color:#6FA4C3;">{{$highlight->title}}</span>
                <br>
                <br>
                <div>
                    <label for="option"><span><span></span></span>{!! $highlight->content  !!}</label>
                </div>
            @endforeach
        </div>



        @if($transportation['transportation_type'] != 'Contact form')

        @foreach($TransportationPricegroup as $pricegroup)
            <div class="clearfix" style="padding:5px;">&nbsp;</div>
            <hr class="grey-bg-hr">

            <div class="clearfix">&nbsp;</div>

            <div class="col-md-12 text-center">

                <table class="table table-responsive table-bordered">

                    <tr>
                        <td colspan="2"><span class="font-sky-color font-eligible-regular">{{$pricegroup->title}}</span></td>
                    </tr>

                    <?php $TransportationPrice = \App\TransportationPrice::where('pricegroupid',$pricegroup->id)->where('status', 'active')->get(); ?>

                    @foreach($TransportationPrice as $price)

                        <tr>
                            <td width="50%">{{$price->title}}</td>
                            @if($price['price'] != 0)
                                <td width="50%">HKD {{$price->price}}</td>
                            @else
                                <td width="50%">Free</td>
                            @endif

                        </tr>

                    @endforeach
                </table>
                <div class="col-md-12 text-left padding-left-0">
                    {!! $pricegroup->description !!}
                </div>
                <div class="clearfix" style="padding:10px;">&nbsp;</div>

                <div class="col-md-12 text-left padding-left-0">

                    <a href="{{url('reserve/transportation/'.$menuid.'/'.$pricegroup->id)}}"><button type="submit" class="btn btn-submit-jasbir"><i class="fa fa-calendar"></i> &nbsp;
                            @if($cookie == 'English')
                                Reserve Transportation
                            @elseif($cookie == '繁中')
                                預訂
                            @elseif($cookie == '簡')
                                预订
                            @endif
                        </button></a>

                </div>

            </div>
        @endforeach

        @else

        <div class="clearfix" style="padding:5px;">&nbsp;</div>
        <hr class="grey-bg-hr">
        <span class="font-darksky-color" style="padding-left:5px;">Contact Us</span>
        <hr class="contact_us_hr" style="padding-top:0; margin:0; margin-bottom:10px;">
        <div class="col-md-12">
            <div class="form-group row">
                <label for="example-text-input" class="col-sm-3 col-xs-4 col-form-label">Title</label>
                <div class="col-xs-7">
                    <select name="title" class="form-control">
                        <option value=""></option>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label for="example-text-input" class="col-sm-3 col-xs-4 col-form-label">First Name</label>
                <div class="col-xs-7">
                    <input type="text" class="form-control">
                </div>
            </div>
            <div class="form-group row">
                <label for="example-text-input" class="col-sm-3 col-xs-4 col-form-label">Last Name</label>
                <div class="col-xs-7">
                    <input type="text" class="form-control">
                </div>
            </div>
            <div class="form-group row">
                <label for="example-text-input" class="col-sm-3 col-xs-4 col-form-label">Contact no:</label>
                <div class="col-xs-7">
                    <input type="text" class="form-control">
                </div>
            </div>
            <div class="form-group row">
                <label for="example-text-input" class="col-sm-3 col-xs-4 col-form-label">Email Address:</label>
                <div class="col-xs-7">
                    <input type="text" class="form-control">
                </div>
            </div>
            <div class="form-group row">
                <label for="example-text-input" class="col-sm-3 col-xs-4 col-form-label">Your Message :</label>
                <div class="col-xs-7">
                    <textarea class="form-control" style="height:50px;"></textarea>
                </div>
            </div>
            <div class="col-md-12 text-right">
                <input type="submit" class="btn btn-submit-jasbir" value="Send">
            </div>
            <div class="clearfix">&nbsp;</div>
        </div>
        @endif

    </div>
</div>
@include('website.footer')
</body>
</html>
@foreach($transportation['TransportationCheckpoint'] as $checkpoint)
    <div id="myModal{{$checkpoint->id}}" class="modal fade" role="dialog">
        <div class="modal-dialog" style="width: 93%; !important;">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="col-md-6"><img src="{{url($checkpoint->image)}}" class="img-responsive" alt="Jumbo"></div>
                    <div class="col-md-6 text-left"><span class="modal_title_popup">{{$checkpoint->title}}</span><br>
                        <br>
                        {!! $checkpoint->description !!}
                    </div>
                    <div class="clearfix" style="padding:1px;">&nbsp;</div>
                </div>
                <div class="clearfix" style="padding:1px;">&nbsp;</div>
            </div>
        </div>
    </div>
@endforeach