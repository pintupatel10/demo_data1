<!doctype html>
<html>
<head>
   @include('website.header')
    <title>Gray Line Tours | {{$hotel->title}}</title>
    <link rel="icon" href="{!! asset('favicon.ico') !!}"/>

</head>

<body>
@include('website.menu')
<div class="col-md-10 col-lg-10 col-sm-10 padding-0 xs-s-class">

    <div class="col-md-12">
        <div class="clearfix" style="padding:2px;">&nbsp;</div>
        <div class="col-xs-6 col-md-6 sm-class" style="padding: 0px 0">
            <h3 class="page_title">@if(isset($hotel)){{$hotel->title}} @endif</h3>
            @if(isset($hotel)) {!! $hotel->description !!} @endif
        </div>
        {{--$htl_filter=\App\HotelFilter::where('cid',$hotel->id)->where('status','active')->orderBy('displayorder','ASC')->get();--}}
        <div class="col-xs-6 col-md-6  padding-0 sm-class">
            <ul class="nav nav-tabs" style="float:right">
                <?php $c=1;?>
                    @foreach($htl_filter as $filter)
                {{--@for($i=0;$i<count($hotel['HotelFilter']);$i++)--}}
                {{--<li class="active"><a data-toggle="tab" href="#home">All</a></li>--}}
                     <li @if($c==1)class="active" @endif><a style="padding:6px 10px 2px 10px;" data-toggle="tab" href="#menu{{$filter->id}}">{{$filter->name}}</a></li>
                {{--@endfor--}}
                    <?php $c++; ?>
                    @endforeach
            </ul>
        </div>
        <div class="clearfix" style="padding:1px;"></div>
    </div>
    <div class="tab-content">

        <?php $c1=1;?>
            @foreach($htl_filter as $filter)
                <div id="menu{{$filter->id}}" class="tab-pane fade @if($c1==1) in active @endif">
                    <?php $htl_list=explode(',',$filter->hotel_list);
                    $count=1;
                    ?>
                    @foreach($htl_list as $list)
                        <?php $hotellist=\App\HotelDetail::where('id',$list)->first();?>
                        <div class="col-xs-6 col-md-3 padding-0 mansory-height-img grow" style="background:url({{url($hotellist->image)}}); background-position:center; background-size: cover; cursor: pointer" onclick="location.href = '{{url('hotel/'.$menuid.'/detail/'.$hotellist->id)}}'">
                            <div class="col-md-12 @if($count % 2 == 0) title_white_bg_mansory grow @else title_blue_bg_mansory grow @endif"><a href="{{url('hotel/'.$menuid.'/detail/'.$hotellist->id)}}">{{$hotellist->title}}</a></div>
                        </div>
                        <?php $count++;?>
                    @endforeach
                </div>
                <?php $c1++;?>
            @endforeach
    </div>
    <div class="clearfix" style="padding:40px;">&nbsp;</div>
</div>
@include('website.footer')
</body>
</html>


{{--<div id="home" class="tab-pane fade in active">--}}
{{--@foreach($hotel['HotelFilter'] as $filter)--}}
{{--$htl_list=explode(',',$filter->hotel_list); $c=0;--}}
{{--@foreach($htl_list as $list)--}}
{{-- $hotellist=\App\HotelDetail::where('id',$list)->first();--}}
{{--<div class="col-xs-6 col-md-3 padding-0 mansory-height-img grow" style="background:url({{url($hotellist->image)}}); background-position:center; background-size: cover; cursor: pointer" onclick="location.href = '{{url('hoteldetail/'.$hotellist->id)}}'">--}}
{{--<div class="col-md-12  @if($c % 2 == 0) title_white_bg_mansory grow @else title_blue_bg_mansory grow @endif"><a href="{{url('hoteldetail/'.$hotellist->id)}}">{{$hotellist->title}}</a></div>--}}
{{--</div>--}}
{{-- $c++ --}}
{{--@endforeach--}}
{{--@endforeach--}}
{{--</div>--}}