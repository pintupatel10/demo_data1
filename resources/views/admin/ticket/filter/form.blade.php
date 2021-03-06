{!! Form::hidden('redirects_to', URL::previous()) !!}
<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
    <label class="col-sm-2 control-label" for="name">Name <span class="text-red">*</span></label>
    <div class="col-sm-5">
        {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Name']) !!}
        @if ($errors->has('name'))
            <span class="help-block">
                <strong>{{ $errors->first('name') }}</strong>
            </span>
        @endif
    </div>
</div>


<div class="form-group{{ $errors->has('status') ? ' has-error' : '' }}">
    <label class="col-sm-2 control-label" for="role">Status <span class="text-red">*</span></label>

    <div class="col-sm-5">

        @foreach (\App\TicketFilter::$status as $key => $value)
            <label>
                {!! Form::radio('status', $key, null, ['class' => 'flat-red']) !!} <span
                        style="margin-right: 10px">{{ $value }}</span>
            </label>
        @endforeach

        @if ($errors->has('status'))
            <span class="help-block">
             <strong>{{ $errors->first('status') }}</strong>
            </span>
        @endif
    </div>
</div>


<hr>
<h1>Ticket </h1>
<br>
<div class="box">
    <div class="box-body">

        <head>
            <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
            <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
            <link rel="stylesheet" href="http://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
            <style>
                /* this stype is for container in ordering list*/
                @media (min-width: 1200px) {
                    .container {
                        width: 1080px;
                    }
                }
            </style>
            <style>

                .item-container {
                    height: 450px;
                    overflow: auto;
                    border: solid 1px #ccc;
                    margin-top: 5px;
                    background-color: #ecf0f5;
                }

                .item-container > div {
                    padding: 20px 20px 10px 10px;
                    overflow: auto;
                    border-bottom: solid 1px #ddd;
                    cursor: pointer;
                    position: relative;
                    height: 60px;
                }

                .item-container > div label {
                    display: inline-block;
                    max-width: 100%;
                    margin-bottom: 5px;
                    font-weight: 700;
                }

                .item-container > div small {
                    color: #aaa;
                    display: block;
                    overflow: hidden;
                    font-weight: normal;
                    line-height: 1.4em;
                }

                .item-container > div:hover {
                    background: #f3f3f3;
                }

                #item-available > div::after {
                    font-family: 'Glyphicons Halflings';
                    content: '\e258';
                    display: block;
                    position: absolute;
                    right: 10px;
                    color: #a9a9a9;
                    top: 18px;
                }

                #item-selected > div::after {
                    font-family: 'Glyphicons Halflings';
                    content: '\e020';
                    display: block;
                    position: absolute;
                    right: 10px;
                    color: #d40000;
                    top: 18px;
                }

                #item-available > div.selected {
                    display: none !important;
                }

                .item-container > .sortable-placeholder {
                    height: 70px; /* Same as the drag item height */
                    border: 1px solid #fcefa1;
                    background-color: #fbf9ee;
                }

            </style>
        </head>


        <div class="container" style="margin-top: 20px">

            <div class="row">

                <div class="col-md-6">
                    <div>Items Available: (click to select)</div>

                    <div class="input-group">
                        <span class="input-group-addon"><span class="glyphicon glyphicon-search"></span></span>
                        <input type="text" class="form-control search" placeholder="Search"
                               data-container-id="item-available">
                    </div>

                    <div id="item-available" class="item-container">
                        <!-- You can generate item content by PHP Here -->
                    </div>

                </div>
                <div data-value="~"></div>

                <div class="col-md-6">
                    <div>Items Selected: (click to un-selected)</div>

                    <div class="input-group">
                        <span class="input-group-addon"><span class="glyphicon glyphicon-search"></span></span>
                        <input type="text" class="form-control search" placeholder="Search"
                               data-container-id="item-selected">
                    </div>

                    <div id="item-selected" class="item-container">
                        <!-- You can generate item content by PHP Here (make sure to add on the left with "selected" class as well) -->
                    </div>
                </div>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
        <script src="http://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
        <script src="http://gesquive.github.io/bootstrap-add-clear/js/bootstrap-add-clear.min.js"></script>

        <input type="hidden" name="itm_sel" id="itm_sel"
               value="@if(isset($filter['ticket_list'])){{$filter['ticket_list']}}@endif">
        {{--<input type="hidden" name="grp_sel" id="grp_sel"--}}
               {{--value="@if(isset($filter['group_list'])){{$filter['group_list']}}@endif">--}}
        <script type="text/javascript">
            function toggle_item1(item) {
                var selected_item = document.getElementById('itm_sel').value;

                if ($(item).closest("#item-available").length == 1) {
                    var current_id = $(item).attr('data-value');
                    if (selected_item == '') {
                        document.getElementById('itm_sel').value = current_id;
                    } else {
                        document.getElementById('itm_sel').value = selected_item + ',' + current_id;
                    }
                    $(item).addClass('selected').clone().appendTo("#item-selected");
                }
                else {
                    $("#item-available > div").each(function () {
                        if ($(this).attr('data-value') == $(item).attr('data-value'))
                            $(this).removeClass('selected');
                        var current_id1 = $(item).attr('data-value');
                        var separator = ",";
                        var values = selected_item.split(",");
                        for (var i = 0; i < values.length; i++) {
                            if (values[i] === current_id1) {
                                values.splice(i, 1);
                                selected_item = document.getElementById('itm_sel').value = values.join(separator);
                            }
                        }
                    });
                    $(item).remove();
                }

            }

            function toggle_item(item) {
                var selected_item = document.getElementById('itm_sel').value;
                if ($(item).closest("#item-available").length == 1) {
                    var current_id = $(item).attr('data-value');
                    if (selected_item == '') {
                        document.getElementById('itm_sel').value = current_id;
                    } else {
                        document.getElementById('itm_sel').value = selected_item + ',' + current_id;
                    }
                    $(item).addClass('selected').clone().appendTo("#item-selected");
                }
                else {
                    $("#item-available > div").each(function () {
                        if ($(this).attr('data-value') == $(item).attr('data-value'))
                            $(this).removeClass('selected');
                        var current_id1 = $(item).attr('data-value');
                        var separator = ",";
                        var values = selected_item.split(",");
                        for (var i = 0; i < values.length; i++) {
                            if (values[i] === current_id1) {
                                values.splice(i, 1);
                                selected_item = document.getElementById('itm_sel').value = values.join(separator);
                            }
                        }
                    });
                    $(item).remove();
                }
            }

            $(function () {

                /* ============ LEFT SIDE =============== */

                /* ============ ADD MODE ==========*/
                        <?php
                        if (isset($mode1)){
                        foreach($mode1 as $key5 => $value5){
                        ?>
                var d5 = '<?php echo $value5['id']; ?>';
                var text5 = "<?php echo $value5['title']; ?>";
                var html5 = "<div data-id='" +"G-" + d5 +"'data-value='" +"G-" + d5 + "' onclick='toggle_item1(this)' style='background-color:rgba(255, 248, 220, 0.72);'>" +
                        "    <label>" + text5 + "</label>" +
                        "</div>";

                $("#item-available").append($(html5));
                        <?php
                        }}
                        ?>

                        <?php
                        if (isset($mode)){
                        foreach($mode as $key1 => $value1){
                        ?>
                var d = '<?php echo $value1['id']; ?>';
                var text1 = "<?php echo $value1['title']; ?>";
                var html1 = "<div data-id='" +"L-" + d +"' data-value='" +"L-"+ d + "' onclick='toggle_item(this)' style='background-color: white;'>" +
                        "    <label>" + text1 + "</label>" +
                        "</div>";
                $("#item-available").append($(html1));
                <?php
                }}
                ?>
                /*============== EDIT MODE ================*/
                        <?php
                        if (isset($mode4)){
                        foreach($mode4 as $key5 => $value5){
                        ?>
                var d5 = '<?php echo $value5['id']; ?>';
                var text5 = "<?php echo $value5['title']; ?>";
                var html5 = "<div  data-id='" +"G-" + d5 +"'data-value='" +"G-" + d5 + "' onclick='toggle_item1(this)' style='background-color:rgba(255, 248, 220, 0.72);'>" +
                        "    <label>" + text5 + "</label>" +
                        "</div>";

                $("#item-available").append($(html5));
                        <?php
                        }}
                        ?>

                        <?php
                        if (isset($mode3)){
                        foreach($mode3 as $key1 => $value1){
                        ?>
                var d = '<?php echo $value1['id']; ?>';
                var text1 = "<?php echo $value1['title']; ?>";
                var html1 = "<div data-id='" +"L-" + d +"' data-value='" +"L-"+ d + "'  onclick='toggle_item(this)' style='background-color: white;'>" +
                        "    <label>" + text1 + "</label>" +
                        "</div>";
                $("#item-available").append($(html1));
                <?php
                }}
                ?>
                /* =====================  RIGHT SIDE ===================== */

                        <?php
                        if (isset($mode50)){
                        for($i=0;$i<count($mode50);$i++) {
                        if ($mode50[$i] == 'G') {
                        $mm = App\TicketGroup::where('id', $mode50[$i+1])->first();
                        ?>
                var d5 = '<?php echo $mm['id']; ?>';
                var text5 = "<?php echo $mm['title']; ?>";
                var html5 = "<div  data-id='" +"G-" + d5 +"'data-value='" +"G-" + d5 + "' onclick='toggle_item1(this)' style='background-color:rgba(255, 248, 220, 0.72);'>" +
                        "    <label>" + text5 + "</label>" +
                        "</div>";
                $("#item-available").append($(html5).addClass("selected"));
                $("#item-selected").append($(html5));

                        <?php
                        }
                        if ($mode50[$i] == 'L') {
                        $mm1 = App\TicketList::where('id', $mode50[$i+1])->first();
                        ?>
                var d = '<?php echo $mm1['id']; ?>';
                var text1 = "<?php echo $mm1['title']; ?>";
                var html1 = "<div data-id='" +"L-" + d +"' data-value='" +"L-"+ d + "'  onclick='toggle_item(this)' style='background-color: white;'>" +
                        "    <label>" + text1 + "</label>" +
                        "</div>";
                $("#item-available").append($(html1).addClass("selected"));
                $("#item-selected").append($(html1));

                <?php
                }
                }
                }

                ?>
                // set up search
                $("input.search").each(function () {
                    var container_id = $(this).attr('data-container-id');
                    $self = $(this);
                    $self.keyup(function () {
                        var value = $(this).val();
                        $("#" + container_id + " > div").each(function () {
                            if (value == '' || $(this).find("label, small").text().toLowerCase().search(value.toLowerCase()) != -1)
                                $(this).show();
                            else
                                $(this).hide();
                        });
                    });
//                    $self.addClear({
//                        onClear: function () {
//                            $("#" + container_id + " > div").show();
//                        }
//                    });
                });

                // Use jQuery UI sortable to make it drag
                $("#item-selected").sortable({
                    placeholder: 'sortable-placeholder',
                    helper: function (event, ui) {
                        var $clone = $(ui).clone();
                        $clone.css('position', 'absolute');
                        return $clone.get(0);
                    }
                });

            });

            //replace itm_sel with sorted order list
            $("form").submit(function() {
                var data = [];
                $('[data-value]').append(function(){
                    var a  = $(this).attr('id');
                    data.push($(this).attr('data-value'));
                });
                $("input[name=itm_sel]").val(data);
            });

        </script>
    </div>
</div>