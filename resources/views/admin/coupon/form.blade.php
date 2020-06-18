
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

<div class="form-group{{ $errors->has('couponcode') ? ' has-error' : '' }}">
    <label class="col-sm-2 control-label" for="couponcode">Coupon Code <span class="text-red">*</span></label>
    <div class="col-sm-4">
        {!! Form::text('couponcode', null, ['class' => 'form-control', 'placeholder' => 'couponcode','id'=>'couponcode']) !!}
        @if ($errors->has('couponcode'))
            <span class="help-block">
                <strong>{{ $errors->first('couponcode') }}</strong>
            </span>
        @endif
    </div>
    <div class="col-sm-1">
        <button type="button" id="btn_pass" name="btn_pass" class="btn btn-info pull-right" onclick="randomCode()" >Regenerate Code</button>
    </div>

</div>

<div class="form-group{{ $errors->has('type') ? ' has-error' : '' }}">
    <label class="col-sm-2 control-label" for="type">Type<span class="text-red"></span>*</label>
    <div class="col-sm-5">
        {!! Form::select('type', [''=>'Please select']+ \App\Coupon::$type, null, ['class' => 'select2
                                select2-hidden-accessible form-control', 'style' => 'width: 100%','onchange'=>
                                'calltype(this.value);']) !!}
        @if ($errors->has('type'))
            <span class="help-block">
                <strong>{{ $errors->first('type') }}</strong>
            </span>
        @endif
    </div>
</div>

<div class="form-group{{ $errors->has('earlydate') ? ' has-error' : '' }}"
     style="@if(isset($coupon->type) && $coupon->type=="Eearly") display:block; @else display: none;@endif"
     id="early">
    <label class="col-sm-2 control-label" for="earlydate">Eearly Date</label>
    <div class="col-sm-5">

        {!! Form::text('earlydate', null, ['class' => 'form-control pull-right','id'=>'datepicker',
        'placeholder' => 'EarlyDate']) !!}
        @if ($errors->has('earlydate'))
            <span class="help-block">
                <strong>{{ $errors->first('earlydate') }}</strong>
                  </span>
        @endif
    </div>
</div>

<div class="form-group{{ $errors->has('orderdate') ? ' has-error' : '' }}"
     style="@if(isset($coupon->type) && $coupon->type=="Order") Display:block; @else display: none;@endif"
     id="order">
    <label class="col-sm-2 control-label" for="orderdate">Order Date</label>
    <div class="col-sm-5">
        {!! Form::text('orderdate', null, ['class' => 'form-control pull-right','id'=>'reservation',
        'placeholder' => 'orderdate']) !!}
        @if ($errors->has('orderdate'))
            <span class="help-block">
                <strong>{{ $errors->first('orderdate') }}</strong>
                  </span>
        @endif
    </div>
</div>

<div class="form-group{{ $errors->has('discountby') ? ' has-error' : '' }}">
    <label class="col-sm-2 control-label" for="discountby">DiscountBy<span class="text-red">*</span></label>

    <div class="col-sm-5">
        {!! Form::select('discountby',[''=>'Please select']+\App\Coupon::$discountby,null, ['class' => 'select2 select2-hidden-accessible form-control', 'style' => 'width: 100%']) !!}
        @if ($errors->has('discountby'))
            <span class="help-block">
                <strong>{{ $errors->first('discountby') }}</strong>
            </span>
        @endif
    </div>
</div>

<div class="form-group{{ $errors->has('discount') ? ' has-error' : '' }}">
    <label class="col-sm-2 control-label" for="discount">Discount<span class="text-red"*/></label>

    <div class="col-sm-5">
        {!! Form::text('discount', null, ['class' => 'form-control', 'placeholder' => 'discount']) !!}
        @if ($errors->has('discount'))
            <span class="help-block">
                <strong>{{ $errors->first('discount') }}</strong>
            </span>
        @endif
    </div>
</div>
<div class="form-group{{ $errors->has('quota') ? ' has-error' : '' }}">
    <label class="col-sm-2 control-label" for="quota">Quota<span class="text-red">*</span></label>

    <div class="col-sm-5">
        {!! Form::text('quota', null, ['class' => 'form-control', 'placeholder' => 'quota']) !!}
        @if ($errors->has('quota'))
            <span class="help-block">
                <strong>{{ $errors->first('quota') }}</strong>
            </span>
        @endif
    </div>
</div>


<div class="form-group{{ $errors->has('tourlist_id') ? ' has-error' : '' }}">
    <label class="col-sm-2 control-label" for="tourlist_id">Select Tour<span class="text-red">*</span></label>
    <div class="col-sm-5">
        {!! Form::select('tourlist_id[]',$tour, !empty($tour_selected)?$tour_selected:null, ['class' => 'select2 select2-hidden-accessible form-control', 'style' => 'width: 100%','multiple']) !!}
        @if ($errors->has('tourlist_id'))
            <span class="help-block">
                <strong>{{ $errors->first('tourlist_id') }}</strong>
            </span>
        @endif
    </div>
</div>
<div class="form-group{{ $errors->has('status') ? ' has-error' : '' }}">
    <label class="col-sm-2 control-label" for="role">Status <span class="text-red">*</span></label>

    <div class="col-sm-5">

        @foreach (\App\Coupon::$status as $key => $value)
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

<script>
    function randomCode()
    {
        var length = 6;
        chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
        code = "";
        for(x=0;x<length;x++)
        {
            i = Math.floor(Math.random() * 62);
            code += chars.charAt(i);
        }
        document.getElementById('couponcode').value = code;
    }

    function calltype(val) {
        if (val == 'Eearly') {
            document.getElementById('early').style.display = "block";
        }
        else {
            document.getElementById('early').style.display = "none";
        }
        if (val == 'Order') {
            document.getElementById('order').style.display = "block";
        }
        else {
            document.getElementById('order').style.display = "none";
        }
    }

</script>