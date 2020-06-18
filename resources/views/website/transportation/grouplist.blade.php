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
    <div class="col-md-12 top-banner tour_group_list-page-banner"  style="background:url({{url($group->landscape_image) }}); background-position:center; background-size: cover;">
        <span class="heading peak_tram_heading">@if(isset($group)){{$group->title}}@endif</span> </div>
    <div class="col-md-12">
        <div class="clearfix">&nbsp;</div>

        <div class="col-md-10 light_blue_color center_align_margin">
            @if(isset($group)) {!!  $group->description !!}@endif
        </div>
        <div class="clearfix" style="padding:20px;">&nbsp;</div>

        <div class="col-md-6 light_blue_color center_align_margin">
            @if(isset($group))
            {{$group->select_sentence}}
            <br>
            <br>
            <?php $tr_list=explode(',',$group->transportation_list)?>
            @foreach($tr_list as $list)
               <?php  $list_query=\App\TransportationList::where('id',$list)->where('status','active')->first();?>
               @if(isset($list_query))
                    @if($list_query->display == "Simplified")
                    <a href="{{url('transportation/'.$menuid.'/simplelist/'.$list_query->id)}}">{{$list_query->title}}</a><br>
                    @else
                    <a href="{{url('transportation/'.$menuid.'/fulllist/'.$list_query->id)}}">{{$list_query->title}}</a><br>
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