<!doctype html>
<html>
<head>
    @include('website.header')
    <title>Gray Line Tours | News</title>
    <link rel="icon" href="{!! asset('favicon.ico') !!}"/>

</head>

<body>
@include('website.menu')
<style>
    .p1{
        line-height: 1.5em;
        height: 3em;
        overflow: hidden;
    }

    /*.text {*/
        /*position: relative;*/
    /*}*/

    /*.text-concat {*/
        /*display: inline-block;*/
        /*word-wrap: break-word;*/
        /*overflow: hidden;*/
        /*max-height: 3.6em; !* (Number of lines you want visible) * (line-height) *!*/
        /*line-height: 1.2em;*/
        /*text-align:justify;*/
    /*}*/

    /*.text.ellipsis::after {*/
        /*content: "...";*/
        /*position: absolute;*/
        /*right: -12px;*/
        /*bottom: 4px;*/
    /*}*/
</style>
<div class="col-md-10 col-lg-10 col-sm-10 padding-0">
    {{--News Layout start--}}
    @foreach($layout as $list)
        <div class="col-md-12 top-banner news-page-banner" style="background-image:url({{ $list['image']}})">
            <span class="heading">{!! $list['title'] !!}</span>
        </div>
    @endforeach
    {{--end News Layout--}}
    <div class="col-md-12">
        <div class="clearfix">&nbsp;</div>

        <div class="col-md-12">
            {{--News Post Start--}}
            @foreach($post as $list)
                <hr class="grey-bg-hr">
                <div class="row">
                    <div class="col-md-3">
                        @if($list['image_upload']!="" && file_exists($list['image_upload']))
                            <img src="{{ url($list->image_upload) }}" class="img-responsive" alt=""><br>
                        @endif
                    </div>
                    <div class="col-md-9">
                        <span class="news_title">{{$list['title']}}</span>
                        <br>
                        <div class="p1"> {!! $list['description']  !!} </div> <button type="button" class="btn btn-submit-jasbir" style="float: right" data-toggle="modal" data-target="#myModal{{$list['id']}}">
                            @if($list['language'] == 'English')
                                Read More
                            @elseif($list['language'] == '繁中')
                                查看更多
                            @elseif($list['language'] == '簡')
                                查看更多
                            @endif
                        </button>
                    </div>
                </div>

                <div id="myModal{{$list['id']}}" class="modal fade" role="dialog">
                    <div class="modal-dialog" style="width: 70%;">

                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                            <div class="modal-body">
                                <div class="col-md-6"> @if($list['image_upload']!="" && file_exists($list['image_upload']))
                                        <img src="{{ url($list->image_upload) }}" class="img-responsive" alt=""><br>
                                    @endif
                                </div>
                                <div class="col-md-6 text-left"><span class="modal_title_popup">{{$list['title']}}</span><br>
                                    <br>
                                    <div style="text-align:justify;"><p>{!! $list['description'] !!}</p></div>
                                </div>
                                <div class="clearfix" style="padding:1px;">&nbsp;</div>
                            </div>
                            <div class="clearfix" style="padding:1px;">&nbsp;</div>
                        </div>
                    </div>
                </div>

            @endforeach
        </div>
    </div>

    <div class="clearfix" style="padding:40px;">&nbsp;</div>
</div>
@include('website.footer')
</body>
</html>