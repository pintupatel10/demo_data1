<!doctype html>
<html>
<head>
    @include('website.header')
    <title>Gray Line Tours | Services</title>
    <link rel="icon" href="{!! asset('favicon.ico') !!}"/>

</head>

<body>
@include('website.menu')
<style>
    @media only screen and (max-device-width : 768px){

        .bigscreen{
            display:none;
        }
        .smallscreen{
            display:block;
        }
    }
    @media only screen and (min-device-width : 769px){

        .bigscreen{
            display:block;
        }
        .smallscreen{
            display:none;
        }
    }
</style>

<div class="smallscreen">

    <div class="col-md-10 col-lg-10 col-sm-10 padding-0">

        <div id="myCarousel1" class="carousel slide" data-ride="carousel">
            <!-- Indicators -->
            <ol class="carousel-indicators">
                <?php $count=0;?>
                @for($k=0;$k<count($service);$k++)
                    <li data-target="#myCarousel1" data-slide-to="{{$count}}" @if($count==0)class="active" @endif></li>
                    <?php $count++ ;?>
                @endfor
            </ol>
            <!-- Wrapper for slides -->
            <div class="carousel-inner" role="listbox">
                <?php $c=1;?>
                @for($i=0;$i<count($service);$i++)
                    <div class="item @if($c==1) active @endif" style="margin-top:35px;padding-top: 10px;">

                            <div class="col-md-3 padding-0 mansory-height-img-services" style="background:url({{ url($service[$i]->image) }}); background-position:center; background-size: cover;" onclick="location.href='{{$service[$i]->url}}'">
                                <div class="col-md-12 @if($i % 2 == 0)title_white_bg_mansory_services @else title_blue_bg_mansory_services @endif"><a style="line-height: 2.628571;font-size: 18px;" href="{{$service[$i]->url}}">{{$service[$i]->title}}</a></div>
                            </div>

                    </div>
                    <?php $c++;?>
                @endfor

            </div>
            <!-- Left and right controls -->
            <a class="left carousel-control" href="#myCarousel1" role="button" data-slide="prev">
                <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="right carousel-control" href="#myCarousel1" role="button" data-slide="next">
                <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>

        </div>

    </div>
</div>
<div class="bigscreen">
    <div class="col-md-10 col-lg-10 col-sm-10 padding-0">

        <div id="myCarousel" class="carousel slide" data-ride="carousel">
            <!-- Indicators -->
            <ol class="carousel-indicators">
                <?php $count=0;?>
                @for($k=0;$k<count($service);$k=$k+4)
                    <li data-target="#myCarousel" data-slide-to="{{$count}}" @if($count==0)class="active" @endif></li>
                    <?php $count++ ;?>
                @endfor
            </ol>
            <!-- Wrapper for slides -->
            <div class="carousel-inner" role="listbox">
                <?php $c=1;?>
                @for($i=0;$i<count($service);$i=$i+3)
                    <div class="item @if($c==1) active @endif">
                        @for($j=$i;$j<$i+4;$j++)
                            <?php if(isset($service[$j])){ ?>
                            <div class="col-md-3 padding-0 mansory-height-img-services" style="background:url({{ url($service[$j]->image) }}); background-position:center; background-size: cover;" onclick="location.href='{{$service[$j]->url}}'">
                                <div class="col-md-12 @if($j %2 == 0)title_white_bg_mansory_services @else title_blue_bg_mansory_services @endif"><a style="line-height: 2.628571;font-size: 18px;" href="{{$service[$j]->url}}">{{$service[$j]->title}}</a></div>
                            </div>
                            <?php }?>
                        @endfor
                    </div>
                    <?php $c++;?>
                @endfor

            </div>
            <!-- Left and right controls -->
            <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
                <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
                <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>
    </div>

</div>

@include('website.footer')
</body>
</html>
<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="col-md-6"><img src="assets/img/Jumbo Restaurant 3.jpg" class="img-responsive" alt="Jumbo"></div>
                <div class="col-md-6 text-left"><span class="modal_title_popup">Jumbo Kingdom</span><br>
                    <br>
                    <p>Since its' establishment in 1976, Jumbo Kingdom have served many world famous
                        celebrities and politicians, including HM Queen Elizabeth II, Tom Cruise, Chow Yun
                        Fat and many more. Now you can enjoy the same dim-sum as those mentioned above
                        with a lunch a t Jumbo Kingdom, e xclusively arranged for you.</p>
                </div>
                <div class="clearfix" style="padding:1px;">&nbsp;</div>
            </div>
            <div class="clearfix" style="padding:1px;">&nbsp;</div>
        </div>
    </div>
</div>