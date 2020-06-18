<!doctype html>
<html>
<head>
    @include('website.header')
    <title>Gray Line Tours | {{$group->title}}</title>
    <link rel="icon" href="{!! asset('favicon.ico') !!}"/>

</head>

<body>
@include('website.menu')
<div class="col-md-10 col-lg-10 col-sm-10 padding-0">
    <div class="col-md-12 top-banner tour_group_list-page-banner" style="background: url({{url($group->landscape_image)}}) no-repeat center center; -webkit-background-size: cover; -moz-background-size: cover;-o-background-size: cover;background-size: cover;background-position: center;">
        <span class="heading peak_tram_heading">@if(isset($group)){{$group->title}} @endif</span> </div>
    <div class="col-md-12">
        <div class="clearfix">&nbsp;</div>

        <div class="col-md-10 light_blue_color center_align_margin">
           @if(isset($group)) {!!  $group->description !!} @endif
        </div>
        <div class="clearfix" style="padding:20px;">&nbsp;</div>

        <div class="col-md-6 light_blue_color center_align_margin">
            {{$group->select_sentence}}
            <br>
            <br>
            @if(isset($group))
            <?php $tr_list=explode(',',$group->ticket_list)?>
            @foreach($tr_list as $list)
                <?php  $list_query=\App\TicketList::where('id',$list)->where('status','active')->first(); ?>
                @if(isset($list_query))
                    @if($list_query->display == "Simplified")
                        <a href="{{url('ticket/'.$menuid.'/ticketsimplelist/'.$list_query->id)}}">{{$list_query->title}}</a><br>
                    @else
                        <a href="{{url('ticket/'.$menuid.'/ticketfulllist/'.$list_query->id)}}">{{$list_query->title}}</a><br>
                    @endif
                @endif
            @endforeach

            @endif
        </div>
    </div>
    <div class="clearfix" style="padding:40px;">&nbsp;</div>
</div>
@include('website.footer')
</body>
</html>