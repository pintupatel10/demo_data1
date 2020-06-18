<style>

    .item-sim-chinese-container {
        height: 450px;
        overflow: auto;
        border: solid 1px #ccc;
        margin-top: 5px;
        background-color: white;
    }

    .item-sim-chinese-container > div {
        padding: 20px 20px 10px 10px;
        overflow: auto;
        border-bottom: solid 1px #ddd;
        cursor: pointer;
        position: relative;
        height: 80px;
    }

    .item-sim-chinese-container > div label {
        display: inline-block;
        max-width: 100%;
        margin-bottom: 5px;
        font-weight: 700;
    }

    .item-sim-chinese-container > div small {
        color: #aaa;
        display: block;
        overflow: hidden;
        font-weight: normal;
        line-height: 1.4em;
    }

    .item-sim-chinese-container > div:hover {
        background: #f3f3f3;
    }

    #item-sim-chinese-available > div::after {
        font-family: 'Glyphicons Halflings';
        content: '\e258';
        display: block;
        position: absolute;
        right: 10px;
        color: #a9a9a9;
        top: 18px;
    }

    #item-sim-chinese-selected > div::after {
        font-family: 'Glyphicons Halflings';
        content: '\e020';
        display: block;
        position: absolute;
        right: 10px;
        color: #d40000;
        top: 18px;
    }

    #item-sim-chinese-available > div.selected {
        display: none !important;
    }

    .item-sim-chinese-container > .sortable-placeholder {
        height: 70px;           /* Same as the drag item height */
        border: 1px solid #fcefa1;
        background-color: #fbf9ee;
    }

</style>

<div class="container" style="margin-top: 20px">

    <div class="row">

        <div class="col-md-6">
            <div>Items Available: (click to select)</div>

            <div class="input-group">
                <span class="input-group-addon"><span class="glyphicon glyphicon-search"></span></span>
                <input type="text" class="form-control search" placeholder="Search" data-container-id="item-sim-chinese-available">
            </div>

            <div id="item-sim-chinese-available" class="item-sim-chinese-container">
                <!-- You can generate item content by PHP Here -->
            </div>
        </div>
        <div data-value="%"></div>

        <div class="col-md-6">
            <div>Items Selected: (click to un-selected)</div>

            <div class="input-group">
                <span class="input-group-addon"><span class="glyphicon glyphicon-search"></span></span>
                <input type="text" class="form-control search" placeholder="Search" data-container-id="item-sim-chinese-selected">
            </div>

            <div id="item-sim-chinese-selected" class="item-sim-chinese-container">
                <!-- You can generate item content by PHP Here (make sure to add on the left with "selected" class as well) -->
            </div>
        </div>

    </div>
</div>
<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
<script src="http://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
<script src="http://gesquive.github.io/bootstrap-add-clear/js/bootstrap-add-clear.min.js"></script>

<input type="hidden" name="itm_sim_chinese" id="itm_sim_chinese"
       value="@if(isset($data['mtype']['list_id'])){{$data['mtype']['list_id']}}@endif">

{{--<input type="hidden" name="itm_sim_chinese" id="itm_sim_chinese"--}}
{{--value="@if(isset($mtype['news_id'])){{$mtype['news_id']}}@endif">--}}

{{--<input type="hidden" name="itm_sim_chinese" id="itm_sim_chinese"--}}
{{--value="@if(isset($mtype['contactus_id'])){{$mtype['contactus_id']}}@endif">--}}

{{--<input type="hidden" name="itm_sim_chinese" id="itm_sim_chinese"--}}
{{--value="@if(isset($mtype['service_id'])){{$mtype['service_id']}}@endif">--}}

{{--<input type="hidden" name="itm_sim_chinese" id="itm_sim_chinese"--}}
{{--value="@if(isset($mtype['hotel_id'])){{$mtype['hotel_id']}}@endif">--}}


{{--<input type="hidden" name="itm_sim_chinese" id="itm_sim_chinese"--}}
{{--value="@if(isset($mtype['tour_id'])){{$mtype['tour_id']}}@endif">--}}

{{--<input type="hidden" name="itm_sim_chinese" id="itm_sim_chinese"--}}
{{--value="@if(isset($mtype['ticket_id'])){{$mtype['ticket_id']}}@endif">--}}

{{--<input type="hidden" name="itm_sim_chinese" id="itm_sim_chinese"--}}
{{--value="@if(isset($mtype['transportation_id'])){{$mtype['transportation_id']}}@endif">--}}

<script type="text/javascript">

    function toggle_sim_chinese_contact(item) {
        var selected_item = document.getElementById('itm_sim_chinese').value;

        if ($(item).closest("#item-sim-chinese-available").length == 1) {
            var current_id = $(item).attr('data-value');
            if (selected_item == '') {
                document.getElementById('itm_sim_chinese').value = current_id;
            } else {
                document.getElementById('itm_sim_chinese').value = selected_item + ',' + current_id;
            }
            $(item).addClass('selected').clone().appendTo("#item-sim-chinese-selected");
        }
        else {
            $("#item-sim-chinese-available > div").each(function () {
                if ($(this).attr('data-value') == $(item).attr('data-value'))
                    $(this).removeClass('selected');
                var current_id1 = $(item).attr('data-value');
                var separator = ",";
                var values = selected_item.split(",");
                for (var i = 0; i < values.length; i++) {
                    if (values[i] === current_id1) {
                        values.splice(i, 1);
                        selected_item = document.getElementById('itm_sim_chinese').value = values.join(separator);
                    }
                }
            });
            $(item).remove();
        }
    }

    function toggle_sim_chinese_news(item) {
        var selected_item = document.getElementById('itm_sim_chinese').value;

        if ($(item).closest("#item-sim-chinese-available").length == 1) {
            var current_id = $(item).attr('data-value');
            if (selected_item == '') {
                document.getElementById('itm_sim_chinese').value = current_id;
            } else {
                document.getElementById('itm_sim_chinese').value = selected_item + ',' + current_id;
            }
            $(item).addClass('selected').clone().appendTo("#item-sim-chinese-selected");
        }
        else {
            $("#item-sim-chinese-available > div").each(function () {
                if ($(this).attr('data-value') == $(item).attr('data-value'))
                    $(this).removeClass('selected');
                var current_id1 = $(item).attr('data-value');
                var separator = ",";
                var values = selected_item.split(",");
                for (var i = 0; i < values.length; i++) {
                    if (values[i] === current_id1) {
                        values.splice(i, 1);
                        selected_item = document.getElementById('itm_sim_chinese').value = values.join(separator);
                    }
                }
            });
            $(item).remove();
        }
    }

    function toggle_sim_chinese_home(item) {
        var selected_item = document.getElementById('itm_sim_chinese').value;
        if ($(item).closest("#item-sim-chinese-available").length == 1) {
            var current_id = $(item).attr('data-value');
            if (selected_item == '') {
                document.getElementById('itm_sim_chinese').value = current_id;
            } else {
                document.getElementById('itm_sim_chinese').value = selected_item + ',' + current_id;
            }
            $(item).addClass('selected').clone().appendTo("#item-sim-chinese-selected");
        }
        else {
            $("#item-sim-chinese-available > div").each(function () {
                if ($(this).attr('data-value') == $(item).attr('data-value'))
                    $(this).removeClass('selected');
                var current_id1 = $(item).attr('data-value');
                var separator = ",";
                var values = selected_item.split(",");
                for (var i = 0; i < values.length; i++) {
                    if (values[i] === current_id1) {
                        values.splice(i, 1);
                        selected_item = document.getElementById('itm_sim_chinese').value = values.join(separator);
                    }
                }
            });
            $(item).remove();
        }
    }
    function toggle_sim_chinese_service(item) {
        var selected_item = document.getElementById('itm_sim_chinese').value;
        if ($(item).closest("#item-sim-chinese-available").length == 1) {
            var current_id = $(item).attr('data-value');
            if (selected_item == '') {
                document.getElementById('itm_sim_chinese').value = current_id;
            } else {
                document.getElementById('itm_sim_chinese').value = selected_item + ',' + current_id;
            }
            $(item).addClass('selected').clone().appendTo("#item-sim-chinese-selected");
        }
        else {
            $("#item-sim-chinese-available > div").each(function () {
                if ($(this).attr('data-value') == $(item).attr('data-value'))
                    $(this).removeClass('selected');
                var current_id1 = $(item).attr('data-value');
                var separator = ",";
                var values = selected_item.split(",");
                for (var i = 0; i < values.length; i++) {
                    if (values[i] === current_id1) {
                        values.splice(i, 1);
                        selected_item = document.getElementById('itm_sim_chinese').value = values.join(separator);
                    }
                }
            });
            $(item).remove();
        }
    }

    function toggle_sim_chinese_hotel(item) {
        var selected_item = document.getElementById('itm_sim_chinese').value;
        if ($(item).closest("#item-sim-chinese-available").length == 1) {
            var current_id = $(item).attr('data-value');
            if (selected_item == '') {
                document.getElementById('itm_sim_chinese').value = current_id;
            } else {
                document.getElementById('itm_sim_chinese').value = selected_item + ',' + current_id;
            }
            $(item).addClass('selected').clone().appendTo("#item-sim-chinese-selected");
        }
        else {
            $("#item-sim-chinese-available > div").each(function () {
                if ($(this).attr('data-value') == $(item).attr('data-value'))
                    $(this).removeClass('selected');
                var current_id1 = $(item).attr('data-value');
                var separator = ",";
                var values = selected_item.split(",");
                for (var i = 0; i < values.length; i++) {
                    if (values[i] === current_id1) {
                        values.splice(i, 1);
                        selected_item = document.getElementById('itm_sim_chinese').value = values.join(separator);
                    }
                }
            });
            $(item).remove();
        }
    }
    function toggle_sim_chinese_tour(item) {
        var selected_item = document.getElementById('itm_sim_chinese').value;
        if ($(item).closest("#item-sim-chinese-available").length == 1) {
            var current_id = $(item).attr('data-value');
            if (selected_item == '') {
                document.getElementById('itm_sim_chinese').value = current_id;
            } else {
                document.getElementById('itm_sim_chinese').value = selected_item + ',' + current_id;
            }
            $(item).addClass('selected').clone().appendTo("#item-sim-chinese-selected");
        }
        else {
            $("#item-sim-chinese-available > div").each(function () {
                if ($(this).attr('data-value') == $(item).attr('data-value'))
                    $(this).removeClass('selected');
                var current_id1 = $(item).attr('data-value');
                var separator = ",";
                var values = selected_item.split(",");
                for (var i = 0; i < values.length; i++) {
                    if (values[i] === current_id1) {
                        values.splice(i, 1);
                        selected_item = document.getElementById('itm_sim_chinese').value = values.join(separator);
                    }
                }
            });
            $(item).remove();
        }
    }
    function toggle_sim_chinese_ticket(item) {
        var selected_item = document.getElementById('itm_sim_chinese').value;
        if ($(item).closest("#item-sim-chinese-available").length == 1) {
            var current_id = $(item).attr('data-value');
            if (selected_item == '') {
                document.getElementById('itm_sim_chinese').value = current_id;
            } else {
                document.getElementById('itm_sim_chinese').value = selected_item + ',' + current_id;
            }
            $(item).addClass('selected').clone().appendTo("#item-sim-chinese-selected");
        }
        else {
            $("#item-sim-chinese-available > div").each(function () {
                if ($(this).attr('data-value') == $(item).attr('data-value'))
                    $(this).removeClass('selected');
                var current_id1 = $(item).attr('data-value');
                var separator = ",";
                var values = selected_item.split(",");
                for (var i = 0; i < values.length; i++) {
                    if (values[i] === current_id1) {
                        values.splice(i, 1);
                        selected_item = document.getElementById('itm_sim_chinese').value = values.join(separator);
                    }
                }
            });
            $(item).remove();
        }
    }
    function toggle_sim_chinese_transportation(item) {
        var selected_item = document.getElementById('itm_sim_chinese').value;
        if ($(item).closest("#item-sim-chinese-available").length == 1) {
            var current_id = $(item).attr('data-value');
            if (selected_item == '') {
                document.getElementById('itm_sim_chinese').value = current_id;
            } else {
                document.getElementById('itm_sim_chinese').value = selected_item + ',' + current_id;
            }
            $(item).addClass('selected').clone().appendTo("#item-sim-chinese-selected");
        }
        else {
            $("#item-sim-chinese-available > div").each(function () {
                if ($(this).attr('data-value') == $(item).attr('data-value'))
                    $(this).removeClass('selected');
                var current_id1 = $(item).attr('data-value');
                var separator = ",";
                var values = selected_item.split(",");
                for (var i = 0; i < values.length; i++) {
                    if (values[i] === current_id1) {
                        values.splice(i, 1);
                        selected_item = document.getElementById('itm_sim_chinese').value = values.join(separator);
                    }
                }
            });
            $(item).remove();
        }
    }
    $(function () {
                <?php
                foreach($transportation_sim_chinese as $key1 => $value){
                ?>
        var c8 = '<?php echo $value->id; ?>';
        var text8 = "<?php echo $value->name; ?>";
        var html8 = "<div data-value='"  +"TR-"+ c8 + "' onclick='toggle_sim_chinese_transportation(this)' style='border-left: 5px solid rgba(107, 218, 219, 1)'>" +
                "    <label>" + text8 + "</label>" +"<BR>"+"Transportation"+
                "</div>";
        $("#item-sim-chinese-available").append($(html8));
                <?php
                }
                ?>
                <?php
                foreach($ticket_sim_chinese as $key1 => $value){
                ?>
        var c7 = '<?php echo $value->id; ?>';
        var text7 = "<?php echo $value->name; ?>";
        var html7 = "<div data-value='" +"TI-"+ c7 + "' onclick='toggle_sim_chinese_ticket(this)' style='border-left: 5px solid rgba(175, 150, 240, 1)'>" +
                "    <label>" + text7 +"</label>" +"<BR>"+"Ticket"+
                "</div>";
        $("#item-sim-chinese-available").append($(html7));
                <?php
                }
                ?>
                <?php
                foreach($tour_sim_chinese as $key1 => $value){
                ?>
        var c6 = '<?php echo $value->id; ?>';
        var text6 = "<?php echo $value->name; ?>";
        var html6 = "<div data-value='"  +"TO-"+ c6 + "' onclick='toggle_sim_chinese_tour(this)' style='border-left: 5px solid rgba(255, 153, 0, 1)'>" +
                "    <label>" + text6 + "</label>" +"<BR>"+"Tour"+
                "</div>";
        $("#item-sim-chinese-available").append($(html6));
                <?php
                }
                ?>
                <?php
                foreach($hotel_sim_chinese as $key1 => $value){
                ?>
        var c5 = '<?php echo $value->id; ?>';
        var text5 = "<?php echo $value->menu_name; ?>";
        var html5 = "<div data-value='"  +"HT-"+ c5 + "' onclick='toggle_sim_chinese_hotel(this)' style='border-left: 5px solid rgba(255, 176, 208, 1)'>" +
                "    <label>" + text5 + "</label>" +"<BR>"+"Hotel"+
                "</div>";
        $("#item-sim-chinese-available").append($(html5));
                <?php
                }
                ?>
                <?php
                foreach($service_sim_chinese as $key1 => $value){
                ?>
        var c4 = '<?php echo $value->id; ?>';
        var text4 = "<?php echo $value->menu_name; ?>";
        var html4 = "<div data-value='"  +"SR-"+ c4 + "' onclick='toggle_sim_chinese_service(this)' style='border-left: 5px solid rgba(0, 153, 255, 1);'>" +
                "    <label>" + text4 + "</label>" +"<BR>"+"Service"+
                "</div>";
        $("#item-sim-chinese-available").append($(html4));
                <?php
                }
                ?>

                <?php
                foreach($contact_sim_chinese as $key1 => $value){
                ?>
        var c3 = '<?php echo $value->id; ?>';
        var text3 = "<?php echo $value->menu_name; ?>";
        var html3 = "<div data-value='"  +"CN-"+ c3 + "' onclick='toggle_sim_chinese_contact(this)' style='border-left: 5px solid rgba(102, 204, 0, 1)'>" +
                "    <label>" + text3 +"</label>" +"<BR>"+"Contact"+
                "</div>";
        $("#item-sim-chinese-available").append($(html3));
                <?php
                }
                ?>
                <?php
                foreach( $news_sim_chinese as $key1 => $value){
                ?>
        var c2 = '<?php echo $value->id; ?>';
        var text2 = "<?php echo $value->menu_name; ?>";
        var html2 = "<div data-value='"  +"NW-"+ c2 + "' onclick='toggle_sim_chinese_news(this)' style='border-left: 5px solid rgba(255, 226, 0, 1);'>" +
                "    <label>" + text2 + "</label>" +"<BR>"+"News"+
                "</div>";

        //return html;
        $("#item-sim-chinese-available").append($(html2));
                <?php
                }
                ?>
                <?php
                foreach( $home_sim_chinese as $key => $value){
                ?>
        var c = '<?php echo $value->id; ?>';
        var text = "<?php echo $value->menu_name; ?>";
        var html = "<div data-value='"  +"HM-"+ c + "' onclick='toggle_sim_chinese_home(this)' style='border-left: 5px solid rgba(255, 0, 0, 1);'>" +
                "    <label>" + text +"</label>" +"<BR>"+"Home"+
                "</div>";

        //return hstml;
        $("#item-sim-chinese-available").append($(html));
        <?php
        }
        ?>
        /*edit*/

                <?php
                if (isset($mode52)){
                for($i=0;$i<count($mode52);$i++) {
                if ($mode52[$i] == 'HM') {
                $value = App\Homelayout::where('id', $mode52[$i+1])->first();
                ?>
        var d1 = '<?php echo $value['id']; ?>';
        var text11 = "<?php echo $value['menu_name']; ?>";
        var html11 = "<div data-value='" +"HM-"+ d1 + "' onclick='toggle_sim_chinese_home(this)' style='border-left: 5px solid rgba(255, 0, 0, 1)'>" +
                "    <label>" + text11 + "</label>" +"<BR>"+"Home"+
                "</div>";
        $("#item-sim-chinese-available").append($(html11).addClass("selected"));
        $("#item-sim-chinese-selected").append($(html11));

                <?php
                }
                if ($mode52[$i] == 'NW') {
                $value1 = App\Newslayout::where('id', $mode52[$i+1])->first();
                ?>
        var d2 = '<?php echo $value1['id']; ?>';
        var text21 = "<?php echo $value1['menu_name']; ?>";
        var html21 = "<div data-value='" +"NW-" + d2 + "' onclick='toggle_sim_chinese_news(this)' style='border-left: 5px solid rgba(255, 226, 0, 1);'>" +
                "    <label>" + text21 + "</label>" +"<BR>"+"News"+
                "</div>";
        $("#item-sim-chinese-available").append($(html21).addClass("selected"));
        $("#item-sim-chinese-selected").append($(html21));

                <?php
                }
                if ($mode52[$i] == 'CN') {
                $value2 = App\Contactus::where('id', $mode52[$i+1])->first();
                ?>
        var d3 = '<?php echo $value2['id']; ?>';
        var text31 = "<?php echo $value2['menu_name']; ?>";
        var html31 = "<div data-value='" +"CN-"+ d3 + "' onclick='toggle_sim_chinese_contact(this)' style='border-left: 5px solid rgba(102, 204, 0, 1);'>" +
                "    <label>" + text31 + "</label>" +"<BR>"+"Contact"+
                "</div>";
        $("#item-sim-chinese-available").append($(html31).addClass("selected"));

        $("#item-sim-chinese-selected").append($(html31));

                <?php
                }
                if ($mode52[$i] == 'SR') {
                $value3 = App\ServiceLayout::where('id', $mode52[$i+1])->first();
                ?>
        var d4 = '<?php echo $value3['id']; ?>';
        var text41 = "<?php echo $value3['menu_name']; ?>";
        var html41 = "<div data-value='" +"SR-"+ d4 + "' onclick='toggle_sim_chinese_service(this)' style='border-left: 5px solid rgba(0, 153, 255, 1);'>" +
                "    <label>" + text41 + "</label>" +"<BR>"+"Service"+
                "</div>";
        $("#item-sim-chinese-available").append($(html41).addClass("selected"));

        $("#item-sim-chinese-selected").append($(html41));

                <?php
                }
                if ($mode52[$i] == 'HT') {
                $value4 = App\Hotelcollection::where('id', $mode52[$i+1])->first();
                ?>
        var d5 = '<?php echo $value4['id']; ?>';
        var text51 = "<?php echo $value4['menu_name']; ?>";
        var html51 = "<div data-value='" +"HT-"+ d5 + "' onclick='toggle_sim_chinese_hotel(this)' style='border-left: 5px solid rgba(255, 176, 208, 1);'>" +
                "    <label>" + text51 + "</label>" +"<BR>"+"Hotel"+
                "</div>";
        $("#item-sim-chinese-available").append($(html51).addClass("selected"));

        $("#item-sim-chinese-selected").append($(html51));

                <?php
                }
                if ($mode52[$i] == 'TO') {
                $value5 = App\Tourcollection::where('id', $mode52[$i+1])->first();
                ?>
        var d6 = '<?php echo $value5['id']; ?>';
        var text61 = "<?php echo $value5['name']; ?>";
        var html61 = "<div data-value='" +"TO-"+ d6 + "' onclick='toggle_sim_chinese_tour(this)' style='border-left: 5px solid rgba(255, 153, 0, 1);'>" +
                "    <label>" + text61 +"</label>" +"<BR>"+"Tour"+
                "</div>";
        $("#item-sim-chinese-available").append($(html61).addClass("selected"));

        $("#item-sim-chinese-selected").append($(html61));

                <?php
                }
                if ($mode52[$i] == 'TI') {
                $value6 = App\Ticketcollection::where('id', $mode52[$i+1])->first();
                ?>
        var d7 = '<?php echo $value6['id']; ?>';
        var text71 = "<?php echo $value6['name']; ?>";
        var html71 = "<div data-value='" +"TI-"+ d7 + "' onclick='toggle_sim_chinese_ticket(this)' style='border-left: 5px solid rgba(175, 150, 240, 1);'>" +
                "    <label>" + text71 + "</label>" +"<BR>"+"Ticket"+
                "</div>";
        $("#item-sim-chinese-available").append($(html71).addClass("selected"));
        $("#item-sim-chinese-selected").append($(html71));

                <?php
                }
                if ($mode52[$i] == 'TR') {
                $value7 = App\Transportationcollection::where('id', $mode52[$i+1])->first();
                ?>
        var d8 = '<?php echo $value7['id']; ?>';
        var text81 = "<?php echo $value7['name']; ?>";
        var html81 = "<div data-value='" +"TR-"+ d8 + "' onclick='toggle_sim_chinese_transportation(this)' style='border-left: 5px solid rgba(107, 218, 219, 1);'>" +
                "    <label>" + text81 + "</label>" +"<BR>"+"Transportation"+
                "</div>";
        $("#item-sim-chinese-available").append($(html81).addClass("selected"));
        $("#item-sim-chinese-selected").append($(html81));

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
//            $self.addClear({
//                onClear: function () {
//                    $("#" + container_id + " > div").show();
//                }
//            });
        });

        // Use jQuery UI sortable to make it drag
        $("#item-sim-chinese-selected").sortable({
            placeholder: 'sortable-placeholder',
            helper: function (event, ui) {
                var $clone = $(ui).clone();
                $clone.css('position', 'absolute');
                return $clone.get(0);
            }
        });

    });

    //replace itm_sim_chinese with sorted order list
    $("form").submit(function() {
        var data = [];
        $('[data-value]').append(function(){
            var a  = $(this).attr('id');
            data.push($(this).attr('data-value'));
        });
        $("input[name=itm_sim_chinese]").val(data);
    });
</script>