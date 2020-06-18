<!doctype html>
<html>
<head>
    <title>Gray Line Tours | Enquire</title>
    <link rel="icon" href="{!! asset('favicon.ico') !!}"/>
    <meta charset="utf-8">
    <meta name="HandheldFriendly" content="true" />
    <meta name="viewport" content="user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, width=device-width" />
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('website/bootstrap/css/bootstrap.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('website/font-awesome/css/font-awesome.css') }}">

    <script src="{{ URL::asset('website/jquery/3.1.1/jquery.min.js')}}"></script>
    <script src="{{ URL::asset('website/bootstrap/js/bootstrap.min.js') }}"></script>
</head>
<body>
    <div class="container" style="max-width: 600px;">
        <div class="text-center" style="margin-top: 50px;">
            <img src="http://grayline.shineway-enterprise.com/assets/logo.jpg" width="200px">
        </div>

        <div style="margin: 60px 0 40px 0; color: #183D6B; font-size: 18px; border-bottom: 1px solid #183D6B">
            {{ trans('contact.title') }}
        </div>

        @if (Session::has('success'))
            <div class="alert alert-success" role="alert">
                {!! trans('contact.success') !!}
            </div>
        @endif

        {!! Form::open(['url' => url("enquire/$language/$enquire_no")]) !!}
            <div class="form-group{{ $errors->has('order-no') ? ' has-error' : '' }}">
                <label class="control-label">{{ trans('contact.order-no') }} <span style="color: red;">*</span></label>
                {!! Form::text('order-no', null, ['class' => 'form-control']) !!}
                @if ($errors->has('order-no'))
                    <span class="help-block">
                        <strong>{{ $errors->first('order-no') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group{{ $errors->has('message') ? ' has-error' : '' }}">
                <label class="control-label">{{ trans('contact.message') }} <span style="color: red;">*</span></label>
                {!! Form::textarea('message', null, ['class' => 'form-control']) !!}
                @if ($errors->has('message'))
                    <span class="help-block">
                        <strong>{{ $errors->first('message') }}</strong>
                    </span>
                @endif
            </div>

            <div style="margin: 40px 0 100px 0;">
                <button type="submit" class="btn btn-primary"><i class="fa fa-paper-plane-o"></i> {{ trans('contact.send') }}</button>
            </div>
        {!! Form::close() !!}
    </div>
</body>
</html>