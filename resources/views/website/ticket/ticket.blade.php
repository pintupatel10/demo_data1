<!doctype html>
<html>
<head>
    @include('website.header')
    <title>Gray Line Tours | {{$ticket->name}}</title>
    <link rel="icon" href="{!! asset('favicon.ico') !!}"/>

</head>

<body>
@include('website.menu')
<div class="col-md-10 col-lg-10 col-sm-10 padding-0">

    <div class="col-md-12">
        <div class="clearfix" style="padding:2px;">&nbsp;</div>
        {{--<div class="col-md-6 " style="padding: 0px 0">--}}
            {{--<h3 class="page_title">{{$ticket->name}}</h3>--}}
            {{--@if(isset($ticket)) {!! $ticket->description !!} @endif--}}
        {{--</div>--}}
        <div class="col-xs-6 col-md-6 sm-class" style="padding: 0px 0">
            <h3 class="page_title">{{$ticket->name}}</h3>
            @if(isset($ticket)) {!! $ticket->description !!} @endif
        </div>
        <div class="col-md-6  padding-0">
            <ul class="nav nav-tabs" style="float:right">
                <?php $c=1;?>
                @foreach($ticketfilter as $filter)
                        @if($c%5 == 0)
                            <br><br>
                        @endif
                    <li @if($c==1)class="active" @endif><a style="padding:6px 10px 2px 10px;" data-toggle="tab" href="#menu{{$filter->id}}">{{$filter->name}}</a></li>
                    <?php $c++; ?>
                @endforeach
            </ul>
        </div>
        <div class="clearfix" style="padding:1px;"></div>
    </div>
    <div class="tab-content">
        <?php $c1=1; ?>
        @foreach($ticketfilter as $filter)
            <div id="menu{{$filter->id}}" class="tab-pane fade @if($c1==1) in active @endif">
                <?php $tr_list=explode(',',$filter['ticket_list']);
                $f=count($tr_list);
                foreach($tr_list as $key =>$v){
                $s=explode('-',$v);
                if($s[0] == 'L'){
                $list_query=\App\TicketList::where('id',$s[1])->where('status','active')->first();
                ?>
                    @if(isset($list_query))
                <div class="col-md-3 padding-0 mansory-height-img grow" style="background:url({{url($list_query->image_thumb) }}); background-position:center; background-size: cover; cursor: pointer" onclick="location.href=' @if($list_query->display == "Simplified") {{url('ticket/'.$menuid.'/ticketsimplelist/'.$list_query->id)}} @else {{url('ticket/'.$menuid.'/ticketfulllist/'.$list_query->id)}} @endif'">
                    <div class="col-md-12 @if($f % 2 == 0) title_white_bg_mansory grow @else title_blue_bg_mansory grow @endif"><a href=" @if($list_query->display == "Simplified") {{url('ticket/'.$menuid.'/ticketsimplelist/'.$list_query->id)}} @else {{url('ticket/'.$menuid.'/ticketfulllist/'.$list_query->id)}} @endif ">{{($list_query->title) }}</a></div>
                </div>
                    @endif
                <?php
                }
                if($s[0] == 'G'){
                $grp_query=\App\TicketGroup::where('id',$s[1])->where('status','active')->first();
                ?>
                    @if(isset($grp_query))
                <div class="col-md-3 padding-0 mansory-height-img grow" style="background:url({{url($grp_query->portrait_image_thumb) }}); background-position:center; background-size: cover; cursor: pointer" onclick="location.href='{{url('ticket/'.$menuid.'/ticketgroup/'.$grp_query->id)}}'">
                    <div class="col-md-12 @if($f % 2 == 0) title_white_bg_mansory grow @else title_blue_bg_mansory grow @endif"><a href="{{url('ticket/'.$menuid.'/ticketgroup/'.$grp_query->id)}}">{{($grp_query->title) }}</a></div>
                </div>
                    @endif
                <?php
                }
                $f--;
                }
                ?>
            </div>
            <?php $c1++; ?>
        @endforeach


    </div>
    <div class="clearfix" style="padding:40px;">&nbsp;</div>
</div>
@include('website.footer')
</body>
</html>

