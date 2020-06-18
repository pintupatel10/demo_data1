<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="HandheldFriendly" content="true" />
    <meta name="viewport" content="user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, width=device-width" />
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('website/bootstrap/css/bootstrap.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('website/font-awesome/css/font-awesome.css')}}">

    <link rel="stylesheet" href="{{ URL::asset('website/assets/slideinpanel/css/reset.css')}}"> <!-- CSS reset -->
    <link rel="stylesheet" href="{{ URL::asset('website/assets/slideinpanel/css/style.css')}}"> <!-- Resource style -->
    <script src="{{ URL::asset('website/assets/slideinpanel/js/modernizr.js')}}"></script> <!-- Modernizr -->

    <link rel="stylesheet" type="text/css" href="{{ URL::asset('website/assets/css/custom-css.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('website/css/jquerysctipttop.css')}}">

    <link href="{{ URL::asset('website/assets/backtotop/css/backTop.css')}}" rel="stylesheet" type="text/css" />

    <title>Gray Line Tours | {{$ticket->title}}</title>
    <link rel="icon" href="{!! asset('favicon.ico') !!}"/>

</head>

<body>
@include('website.menu')
<div class="col-md-10 col-lg-10 col-sm-10 tour_details_full_bg scroll-dv" style="padding:20px; height: 101%">
    <div class="col-md-12 tour_details_full_bg_page_in zoomInUp scroll-dv">

        <h1 class="font-eligible-regular text-center font-white-color" >{{$ticket->title}}<br>
            {{$ticket->ticket_code}}</h1>

        <div class="tour_details_floating text-center" style=""><a href="#0" class="cd-btn">Ticket Details</a></div>

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
                <div class="col-md-12" style="padding-left:0"> <a href="" class="cd-panel-close"><img src="{{url('website/assets/img/chevron-circle-outline.png')}}" style="height:40px; width:40px; padding-right:0px; float: left; margin: -12px 10px" alt="back"> Back </a></div>
                <div class="clearfix" style="padding:10px;">&nbsp;</div>
                <div class="tour_details_single_text">
                    <h2 class="font-sky-color font-eligible-regular">{{$ticket->title}}</h2>
                    <br>
                    {!! $ticket->description !!}
                </div>
                <div class="clearfix" style="padding:10px;">&nbsp;</div>
                <div class="tour_details_gallery">
                    @for($i=0;$i<count($ticket['TicketCheckpoint']);$i=$i+4)
                    <div class="row">
                        @foreach($ticket['TicketCheckpoint'] as $checkpoint)
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
                @endfor
                </div>
                <hr class="blue-hr">
                <div class="col-md-12">
                    <span class="font-sky-color font-eligible-regular">Disneyland Magic Tour - Highlights</span>
                    <br>
                    <br>
                    @foreach($ticket['Tickethighlight'] as $highlight)
                    <div>
                        <label for="option"><span><span></span></span>{!! $highlight->content  !!}</label>
                    </div>
                    @endforeach
                </div>
                <div class="clearfix" style="padding:5px;">&nbsp;</div>
                <hr class="grey-bg-hr">

                <div class="clearfix">&nbsp;</div>
                <div class="col-md-12 text-center">
                    <table class="table table-responsive table-bordered">
                        <tr>
                            <td colspan="2"><span class="font-sky-color font-eligible-regular">124AIF-SK DELUXE HONG KONG ISLAND – FULL DAY (With Lunch and Sky Terrace 428 Admission)</span></td>
                        </tr>

                        <tr>
                            <td>Adult</td>

                            <td>HK$740</td>
                        </tr>

                        <tr>
                            <td>Child ( 3- 11 yrs )</td>

                            <td>HK$620</td>
                        </tr>

                    </table>
                    <div class="col-md-12 text-left padding-left-0">
                        Tour price include air -conditioned sightseeing coach, guided servic e, tram ticket to the Victoria Peak, admission t o Sky Terrace 428 and lunch a t Jumbo Kingdom.
                    </div>
                    <div class="clearfix" style="padding:10px;">&nbsp;</div>

                    <div class="col-md-12 text-left padding-left-0">

                        <a href="reserve_form.html"><button type="submit" class="btn btn-submit-jasbir"><i class="fa fa-calendar"></i> &nbsp;Reserver Tour</button></a>
                    </div>
                </div>
                <div class="clearfix" style="padding:5px;">&nbsp;</div>
                <hr class="grey-bg-hr">

                <div class="clearfix">&nbsp;</div>
                <div class="col-md-12 text-center">
                    <table class="table table-responsive table-bordered">
                        <tr>
                            <td colspan="2"><span class="font-sky-color font-eligible-regular">124AIF-SK DELUXE HONG KONG ISLAND – FULL DAY (With Lunch and Sky Terrace 428 Admission)</span></td>
                        </tr>

                        <tr>
                            <td>Adult</td>

                            <td>HK$740</td>
                        </tr>

                        <tr>
                            <td>Child ( 3- 11 yrs )</td>

                            <td>HK$620</td>
                        </tr>

                    </table>
                    <div class="col-md-12 text-left padding-left-0">
                        Tour price include air -conditioned sightseeing coach, guided servic e, tram ticket to the Victoria Peak, admission to Sky Terrace 428 and lunch at Jumbo Kingdom.
                    </div>
                    <div class="clearfix" style="padding:10px;">&nbsp;</div>

                    <div class="col-md-12 text-left padding-left-0">

                        <a href="reserve_form.html"><button type="submit" class="btn btn-submit-jasbir"><i class="fa fa-calendar"></i> &nbsp;Reserver Tour</button></a>
                    </div>
                </div>

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
@foreach($ticket['TicketCheckpoint'] as $checkpoint)
<div id="myModal{{$checkpoint->id}}" class="modal fade" role="dialog">
    <div class="modal-dialog">
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