<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="HandheldFriendly" content="true" />
    <meta name="viewport" content="user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, width=device-width" />
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('website/bootstrap/css/bootstrap.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('website/font-awesome/css/font-awesome.css')}}">

    {{--<link rel="stylesheet" href="{{ URL::asset('website/assets/slideinpanel/css/reset.css')}}"> <!-- CSS reset -->--}}
    <link rel="stylesheet" href="{{ URL::asset('website/assets/slideinpanel/css/style.css')}}"> <!-- Resource style -->
    <script src="{{ URL::asset('website/assets/slideinpanel/js/modernizr.js')}}"></script> <!-- Modernizr -->

    <link rel="stylesheet" type="text/css" href="{{ URL::asset('website/assets/css/custom-css.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('website/css/jquerysctipttop.css')}}">

    <link href="{{ URL::asset('website/assets/backtotop/css/backTop.css')}}" rel="stylesheet" type="text/css" />

    <title>Gray Line Tours | {{$tour->title}}</title>
    <link rel="icon" href="{!! asset('favicon.ico') !!}"/>

</head>

<body style="style:none;">
@include('website.menu')
<div class="col-md-10 col-lg-10 col-sm-10 tour_details_full_bg scroll-dv" style="padding:20px; height: 101%;background: url({{url($tour->image)}}) no-repeat center center fixed; -webkit-background-size: cover; -moz-background-size: cover;-o-background-size: cover;background-size: cover;">

    <div class="col-md-12 tour_details_full_bg_page_in zoomInUp scroll-dv">

        <h1 class="font-eligible-regular text-center font-white-color" style="color:#{{$tour->title_color}}">{{$tour->title}}<br>
            {{$tour->tour_code}}</h1>

        <div class="tour_details_floating text-center" style=""><a href="#0" class="cd-btn">
                @if($cookie == 'English')
                    Details
                @elseif($cookie == '繁中')
                    詳細內容
                @elseif($cookie == '簡')
                    详细内容
                @endif

            </a></div>

    </div>
    <div class="clearfix" style="padding:40px;">&nbsp;</div>
</div>

<!-- jQuery library -->
<script src="{{ URL::asset('website/jquery/3.1.1/jquery.min.js')}}"></script>

<!-- Latest compiled JavaScript -->
<script src="{{ URL::asset('website/bootstrap/js/bootstrap.min.js')}}"></script>
</body>
</html>
<!--<script src="assets/slideinpanel/js/jquery-2.1.1.js"></script>-->
<script src="{{ URL::asset('website/assets/slideinpanel/js/main.js')}}"></script> <!-- Resource jQuery -->

<div class="cd-panel from-right">

    <div class="cd-panel-container">
        <div class="cd-panel-content">
            <div class="col-md-12 col-lg-12 col-sm-12">
                <div class="col-md-12" style="padding-left:0"> <a href="" class="cd-panel-close"><img src="{{url('website/assets/img/chevron-circle-outline.png')}}" style="height:40px; width:40px; padding-right:0px; float: left; margin: -12px 10px" alt="back">
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
                    <h2 class="font-sky-color font-eligible-regular">{{$tour->title}}</h2>
                    <br>
                    {!! $tour->description !!}
                </div>
                <div class="clearfix" style="padding:10px;">&nbsp;</div>
                <div class="tour_details_gallery">
                        <div class="row">
                            @foreach($checkpoints as $checkpoint)
                                <div class="col-md-3 padding-top-bottom-10 padding-left-0">
                                    <div class="gallery-img">
                                        <ul class="img-list">
                                            <li>
                                                <div style="background: url({{url($checkpoint->image)}});background-position: center;background-size: cover;background-repeat: no-repeat;height: 235px;"></div>
                                                {{--<img src="{{url($checkpoint->image)}}" class="img-responsive gallery_img" alt="media temple"> --}}
                                                <span class="text-content"><span><a href="" data-toggle="modal" data-target="#myModal{{$checkpoint->id}}">{{$checkpoint->title}}</a></span></span> </li>
                                            <span class="image_gallery_title">{{$checkpoint->title}}</span>
                                        </ul>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                </div>
                <hr class="blue-hr">

                <div class="col-md-12">
                    @foreach($tour['Tourhighlight'] as $highlight)
                    <span class="font-sky-color font-eligible-regular" style="font-size: 20px;color:#6FA4C3;"> {{$highlight->title}} </span>
                    <br>
                    <br>
                        <div>
                            <label for="option"><span><span></span></span>{!! $highlight->content  !!}</label>
                        </div>
                    @endforeach
                </div>


                @foreach($TourPricegroup as $pricegroup)
                    <div class="clearfix" style="padding:5px;">&nbsp;</div>
                    <hr class="grey-bg-hr">

                    <div class="clearfix">&nbsp;</div>

                    <div class="col-md-12 text-center">

                        <table class="table table-responsive table-bordered">

                            <tr>
                                <td colspan="2"><span class="font-sky-color font-eligible-regular">{{$pricegroup->title}}</span></td>
                            </tr>

                            <?php $TourPrice = \App\TourPrice::where('pricegroupid',$pricegroup->id)->where('status', 'active')->get(); ?>
                            @foreach($TourPrice as $price)

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

							@if (count($pricegroup['TourPrice']) > 0)
	                            <a href="{{url('reserve/tour/'.$menuid.'/'.$pricegroup->id)}}"><button type="submit" class="btn btn-submit-jasbir"><i class="fa fa-calendar"></i> &nbsp;
                                        @if($cookie == 'English')
                                            Reserve Tour
                                        @elseif($cookie == '繁中')
                                            預訂
                                        @elseif($cookie == '簡')
                                            预订
                                        @endif
                                    </button></a>
                            @endif

                        </div>

                    </div>
                @endforeach


                <a id="backTop">Back To Top</a>
                <script src="{{ URL::asset('website/jquery/1.11.2/jquery.min.js')}}"></script>
                <script src="{{ URL::asset('website/assets/backtotop/src/jquery.backTop.js')}}"></script>
                <script>
                    $(document).ready( function() {
                        $('#backTop').backTop({
                            'position' : 100,
                            'speed' : 500,
                            'color' : 'red',
                        });
                    });
                </script>


                <div class="clearfix" style="padding:20px;">&nbsp;</div>


            </div>
        </div> <!-- cd-panel-content -->
    </div> <!-- cd-panel-container -->
</div> <!-- cd-panel -->


<!-- Modal -->

@foreach($tour['TourCheckpoint'] as $checkpoint)
    <div id="myModal{{$checkpoint['id']}}" class="modal fade" role="dialog">

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