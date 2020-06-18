{!! Form::hidden('redirects_to', URL::previous()) !!}


<div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
    <label class="col-sm-2 control-label" for="tour_list">Title <span class="text-red">*</span></label>
    <div class="col-sm-5">
        {!! Form::select('title[]',['please Select']+$name, !empty($modes)?$modes:null, ['class' => 'select2 select2-hidden-accessible form-control', 'style' => 'width: 100%']) !!}
        @if ($errors->has('title'))
            <span class="help-block">
                <strong>{{ $errors->first('title') }}</strong>
            </span>
        @endif
    </div>
</div>

<div class="form-group{{ $errors->has('dquota') ? ' has-error' : '' }}">
    <label class="col-sm-2 control-label" for="dquota">Default Quota <span class="text-red">*</span></label>
    <div class="col-sm-5">
        {!! Form::number('dquota', null, ['class' => 'form-control', 'placeholder' => 'Default Quota']) !!}
        @if ($errors->has('dquota'))
            <span class="help-block">
                <strong>{{ $errors->first('dquota') }}</strong>
            </span>
        @endif
    </div>
</div>


<div class="form-group">
    <div class="col-sm-6">
        <label class="col-sm-2 control-label" for="dquota">Special Quota </label>
        <input class="btn btn-info pull-left" type="button" value="Add" onClick="addEvent1216();">
    </div>
</div>

<div id="myDiv4">
    <?php  $inventory_id = ""; ?>
    @if(isset($inventory->TicketQuota[0]) && !empty($inventory->TicketQuota[0]))
            <?php $count4 = 1;
            ?>
        @foreach ($inventory->TicketQuota as $key => $value)
            <?php $inventory_id.=$value->id.",";
            ?>

            <div class="col-sm-12" id="Account4<?php echo $count4; ?>">
                <br><br>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Quota<span class="text-red"></span></label>
                    <div class="col-sm-2">
                        <input type="text" name="quota<?php echo $count4; ?>" id="quota<?php echo $count4; ?>" value="{{$value->quota}}" class="form-control" > <br><br>
                    </div>

                    <label class="col-sm-2 control-label">Date </label>
                    <div class="col-sm-2">
                        <input type="text" name="date<?php echo $count4; ?>" id="datepicker<?php echo $count4; ?>" value="{{$value->date}}" class="form-control" >
                    </div>


                    <label class="col-sm-2 control-label">To </label>
                    <div class="col-sm-2">
                        <input type="text" name="to<?php echo $count4; ?>" id="datepicker123<?php echo $count4; ?>" value="{{$value->to}}" class="form-control" >
                    </div>


                    <a href="javascript:;" onclick="removeEvent1216('Account4<?php echo $count4; ?>','<?php echo $value->id;?>')">
                        <strong>[x]</strong>
                    </a>
                </div>
                
                <script>
                $('#datepicker' + num4).datepicker({
                autoclose: true
                });

                $('#datepicker123' + num4).datepicker({
                autoclose: true
                });
                </script>
            </div>
                <?php $count4++; ?>
        @endforeach
    @endif
</div>

<script>
    function displayunicode(e){

        $(this).keydown(function(e)
        {
            var key = e.charCode || e.keyCode || 0;
            // allow backspace, tab, delete, enter, arrows, numbers and keypad numbers ONLY
            // home, end, period, and numpad decimal
            return (
            key == 8 ||
            key == 9 ||
            key == 13 ||
            key == 46 ||
            key == 110 ||
            key == 190 ||
            (key >= 35 && key <= 40) ||
            (key >= 48 && key <= 57) ||
            (key >= 96 && key <= 105));
        });

    }


    function addEvent1216() {
        var ni = document.getElementById('myDiv4');
        var numi4 = document.getElementById('theValue1216');
        var num4 = (document.getElementById("theValue1216").value - 1) + 2;
        numi4.value = num4;

        var divIdName4 = "Account4" + num4;
        var newdiv4 = document.createElement('div');
        newdiv4.setAttribute("id", divIdName4);
        newdiv4.setAttribute("class", "col-sm-12");
        newdiv4.innerHTML ="<div class='form-group'>" +
                "<label class='col-sm-2 control-label'>Quota<span class='text-red'></span></label>" +
                "<div class='col-sm-2'><input type='text' id=\"quota" + num4 + "\"  name=\"quota" + num4 + "\"  class='form-control'></div>"+
                "<label class='col-sm-2 control-label'>Date <span class='text-red'></span></label>" +
                "<div class='col-sm-2'><input type='text'  id=\"datepicker" + num4 + "\"  name=\"date" + num4 + "\"  class='form-control pull-right'></div>" +
                "<label class='col-sm-2 control-label'>To<span class='text-red'></span></label>" +
                "<div class='col-sm-2'><input type='text' id=\"datepicker123" + num4 + "\"  name=\"to" + num4 + "\"  class='form-control pull-right'></div>" +
                "<a  href=\"javascript:;\" onclick=\"removeEvent1216(\'" + divIdName4 + "\')\"><strong>[x]</strong></a>"+"</div>";
        ni.appendChild(newdiv4);
        document.getElementById("i").value = document.getElementById("i").value + 1;

        $('#datepicker' + num4).datepicker({
            autoclose: true
        });

        $('#datepicker123' + num4).datepicker({
            autoclose: true
        });
    }

    function removeEvent1216(divNum4, id) {
        var remove4 = document.getElementById('remove_id4').value;
        if (remove4 == "") {
            remove4 = id;
        }
        else {
            remove4 = remove4 + "," + id;
        }

        document.getElementById('remove_id4').value = remove4;
        var d4 = document.getElementById('myDiv4');
        var olddiv4 = document.getElementById(divNum4);
        d4.removeChild(olddiv4);
        document.getElementById("theValue1216").value = document.getElementById("theValue1216").value - 1;
    }
</script>

<input type="hidden" value="{{ trim($inventory_id,",") }}" name="inventory_id" id="inventory_id">
<input type="hidden" value="" name="remove_id4" id="remove_id4">
<input type="hidden" value="{{ !empty($count4)?$count4-1:''  }}" name="theValue1216" id="theValue1216"/>
<input type="hidden" value="{{ !empty($count4)?$count4-1:''  }}" name="i" id="i"/>


<div class="form-group{{ $errors->has('status') ? ' has-error' : '' }}">
    <label class="col-sm-2 control-label" for="role">Status <span class="text-red">*</span></label>

    <div class="col-sm-5">

        @foreach (\App\TicketInventory::$status as $key => $value)
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






