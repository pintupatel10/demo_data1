<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="HandheldFriendly" content="true" />
    <meta name="viewport" content="user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, width=device-width" />
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('website/bootstrap/css/bootstrap.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('website/font-awesome/css/font-awesome.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('website/assets/css/custom-css.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('website/css/jquerysctipttop.css')}}">
    <link href="{{ URL::asset('website/assets/backtotop/css/backTop.css')}}" rel="stylesheet" type="text/css" />
    <title>Gray Line Tours | {{$tour->title}}</title>
    <link rel="icon" href="{!! asset('favicon.ico') !!}"/>

</head>

<body>
@include('website.menu')

<div class="col-md-10 col-lg-10 col-sm-10 padding-0">
    <div class="col-md-2 tour_details_single_banner_bg affix" style="background: url({{url($tour->image)}}) no-repeat center center fixed; -webkit-background-size: cover; -moz-background-size: cover;-o-background-size: cover;background-size: cover;"> </div>
    <div class="col-md-10 col-lg-10 col-sm-10 padding-left-100-desktop margin-left-15per-desktop" style="margin-left: 19%;!important;">
        <div class="col-md-12" style="padding-top:20px; padding-left:0"> <a href="{{url('tour/'.$menuid.'/tourfulllist/'.$tour->id)}}"><img src="{{URL::asset('website/assets/img/chevron-circle-outline.png')}}" style="height:40px; width:40px; padding-right:0px;" alt="back">
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
        </div>

        <hr class="blue-hr">


        <div class="col-md-12">
            @foreach($tour['Tourhighlight'] as $highlight)
                <span class="font-sky-color font-eligible-regular" style="font-size: 20px;color:#6FA4C3;">{{$highlight->title}}</span>
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

                    <a href="{{url('reserve/tour/'.$menuid.'/'.$pricegroup->id)}}"><button type="submit" class="btn btn-submit-jasbir"><i class="fa fa-calendar"></i> &nbsp;
                            @if($cookie == 'English')
                                Reserve Tour
                            @elseif($cookie == '繁中')
                                預訂
                            @elseif($cookie == '簡')
                                预订
                            @endif
                        </button></a>

                </div>

            </div>
        @endforeach

    </div>

    <div class="clearfix" style="padding:40px;">&nbsp;</div>
</div>

<!-- jQuery library -->
<script src="{{ URL::asset('website/jquery/3.1.1/jquery.min.js')}}"></script>


<!-- Latest compiled JavaScript -->
<script src="{{ URL::asset('website/bootstrap/js/bootstrap.min.js')}}"></script>



<a id="backTop">Back To Top</a>

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



</body>
</html>

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