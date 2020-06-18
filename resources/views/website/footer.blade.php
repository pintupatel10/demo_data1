<!-- jQuery library -->
<script src="{{ URL::asset('website/jquery/3.1.1/jquery.min.js')}}"></script>

<!-- Latest compiled JavaScript -->
<script src="{{ URL::asset('website/bootstrap/js/bootstrap.min.js') }}"></script>
<script src="{{URL::asset('website/assets/slideinpanel/js/main.js')}}"></script> <!-- Resource jQuery -->

<a id="backTop">Back To Top</a>
<script src="{{ URL::asset('website/assets/backtotop/src/jquery.backTop.js') }}"></script>
<script>
    $(document).ready( function() {
        $('#backTop').backTop({
            'position' : 100,
            'speed' : 500,
            'color' : 'red',
        });
    });
</script>

