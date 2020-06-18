
<link rel="stylesheet" type="text/css" href="{{URL::asset('assets/bootstrap/css/bootstrap.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{URL::asset('assets/test/prettify.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{URL::asset('assets/test/src/bootstrap-duallistbox.css')}}">
<script src="{{URL::asset('assets/jquery.min.js')}}"></script>
<script src="{{URL::asset('assets/bootstrap/js/bootstrap.min.js')}}"></script>
{{--<script src="{{URL::asset('assets/test/run_prettify.min.js')}}"></script>--}}
<script src="{{URL::asset('assets/test/src/jquery.bootstrap-duallistbox.js')}}"></script>

        <head>
            <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
            <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
            <link rel="stylesheet" href="http://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
        </head>
<style>
    .foo {
        width: 15px;
        height: 15px;
        margin: 5px;
        border: 1px solid rgba(0, 0, 0, .2);
    }

    .goldenrod {
        background: rgba(107, 218, 219, 1);
    }
    .fuchsia {
        background:rgba(175, 150, 240, 1);
    }
    .yellowgreen {
        background:rgba(255, 153, 0, 1);
    }
    .rosybrown {
    background: rgba(255, 176, 208, 1);
    }
    .darkgrey {
        background:rgba(0, 153, 255, 1);
    }
    .dimgrey {
        background:rgba(102, 204, 0, 1);
    }
    .news {
        background: rgba(255, 226, 0, 1);
    }
    .white {
        background:rgba(255, 0, 0, 1);
    }

</style>

<table class="table" style="border: 1px solid rgba(0, 0, 0, .2);">
    <tbody>

    <tr>
        <td>Home</td>
        <td>News</td>
        <td>Contact</td>
        <td>Service</td>
        <td>Hotel</td>
        <td>Tour</td>
        <td>Ticket</td>
        <td>Transportation</td>
    </tr>
    <tr>
        <td><div class="foo white"></div></td>
        <td><div class="foo news"></div></td>
        <td><div class="foo dimgrey"></div></td>
        <td><div class="foo darkgrey"></div></td>
        <td><div class="foo rosybrown"></div></td>
        <td><div class="foo yellowgreen"></div></td>
        <td><div class="foo fuchsia"></div></td>
        <td><div class="foo goldenrod"></div></td>
    </tr>
    </tbody>
</table>
<br>
        <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
            <label class="col-sm-2 control-label" for="title">Title <span class="text-red">*</span></label>
            <div class="col-sm-5">
                {!! Form::text('title', null, ['class' => 'form-control', 'placeholder' => 'Title']) !!}
                @if ($errors->has('title'))
                    <span class="help-block">
                <strong>{{ $errors->first('title') }}</strong>
            </span>
                @endif
            </div>
        </div>

        <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }}">
            <label class="col-sm-2 control-label" for="role">Status <span class="text-red">*</span></label>
            <div class="col-sm-5">
                @foreach (\App\Menu::$status as $key => $value)
                    <label>
                        {!! Form::radio('status', $key, null, ['class' => 'flat-red']) !!} <span style="margin-right: 10px">{{ $value }}</span>
                    </label>
                @endforeach
                @if ($errors->has('status'))
                    <span class="help-block">
             <strong>{{ $errors->first('status') }}</strong>
            </span>
                @endif
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label" for="language">Select Menu For</label>
            <div class="col-sm-5">
                {!! Form::text('english', null, ['class' => 'form-control', 'placeholder' => 'English','disabled']) !!}
            </div>
        </div>

        @include('admin.site.menutype.english')
        <br>

        <div class="form-group">
            <label class="col-sm-2 control-label" for="繁中">Select Menu For</label>
            <div class="col-sm-5">
                {!! Form::text('繁中', null, ['class' => 'form-control', 'placeholder' => '繁中','disabled']) !!}
            </div>
        </div>
        @include('admin.site.menutype.traditionalchinese')
        <br>

        <div class="form-group">
            <label class="col-sm-2 control-label" for="簡">Select Menu For</label>
            <div class="col-sm-5">
                {!! Form::text('簡', null, ['class' => 'form-control', 'placeholder' => '簡','disabled']) !!}
            </div>
        </div>
        @include('admin.site.menutype.simplechinese')


