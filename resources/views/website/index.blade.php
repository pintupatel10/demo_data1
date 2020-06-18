<!doctype html>
<html>
<head>
    <title>Gray Line Tours | Home</title>
    <link rel="icon" href="{!! asset('favicon.ico') !!}"/>
    @include('website.header')
    <link href="{{ url('assets/plugins/fancybox3/jquery.fancybox.min.css') }}" rel="stylesheet">
</head>

<body>
@include('website.menu')
<div class="col-md-10 col-lg-10 col-sm-10 padding-0">

    {{--Home Layout start--}}

            @foreach($layout as $list)
                        <div class="col-md-12 top-banner home-page-banner" style="background-image:url({{ $list['image']}})">
                        <div><span class="heading">{!! $list['title'] !!}</span></div>
                        </div>
            @endforeach

    {{--end Home Layout--}}

    <div class="col-md-12">
        <div class="clearfix">&nbsp;</div>

        {{--Home Post Start--}}

        @foreach($post as $list)
            @if($list['image_position']=='left')
                <div class="col-md-12">
                        <div class="col-md-6">
                            @if($list['image_upload']!="" && file_exists($list['image_upload']))
                            <img src="{{ url($list->image_upload) }}" class="img-responsive" alt=""><br>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <h1>{{$list['title']}}</h1>
                            <hr class="blue-hr">
                            <p>{!! $list['description'] !!}</p>
                        </div>
                </div>
            @else
                <div class="col-md-12">
                    <div class="col-md-6">
                        <h1>{{$list['title']}}</h1>
                        <hr class="blue-hr">
                        <p> {!! $list['description'] !!} </p>
                    </div>
                    <div class="col-md-6"> <br>
                        @if($list['image_upload']!="" && file_exists($list['image_upload']))
                            <img src="{{ url($list->image_upload) }}" class="img-responsive" alt=""><br>
                        @endif
                     </div>
                </div>
            @endif
        @endforeach
        {{--End Home Post--}}
        <div class="clearfix" style="padding:40px;">&nbsp;</div>
    </div>
</div>


@include('website.footer')
<script src="{{ url('assets/plugins/fancybox3/jquery.fancybox.min.js') }}"></script>

@if ($popup)
    <script>
        $(function (){
            var $instance = $.fancybox.open([
                {
                    src: "{{ url($popup->image) }}",
                    type: "image",
                    opts: {
                        afterLoad: function (){
                            $(".fancybox-image").click(function (){
                                window.open("{{ $popup->url }}");
                            });
                        }
                    }
                }
            ], {
                touch: false,
                infobar: false,
                buttons: true,
            })
        });
    </script>
@endif
</body>
</html>
