<div id="myDiv1">
    <?php  $attribute_id = ""; ?>

    @if(isset($email->MailAddress[0]) && !empty($email->MailAddress[0]))
        <?php $count1 = 1;
        ?>

        @foreach ($email->MailAddress as $key => $value)
            <?php $attribute_id.=$value->id.",";
            ?>

            <div class="col-sm-12" id="Account1<?php echo $count1; ?>" >
                <div class="form-group">

                    <div style="margin-left: 140px;"><a href="javascript:;" onclick="removeEvent1212('Account1<?php echo $count1; ?>','<?php echo $value->id;?>')"></a>
                        <button class="btn btn-danger" type="button" data-toggle="modal" data-target="#myModal1{{$value['id']}}"><i class="fa fa-trash"></i></button>
                        <span style="margin-left: 15px;">{{$value->mail_address}}</span>
                    </div>
                </div>
            </div>
                <div id="myModal1{{$value['id']}}" class="fade modal modal-danger" role="dialog">

                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title">Delete Email</h4>
                            </div>
                            <div class="modal-body">
                                <p>Are you sure you want to delete this Email ?</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Close</button>
                                <a href="{{url('admin/site/emailset/emaildelete/'.$value['id']) }}" data-method="get" name="emaildelete">
                                    <button type="button" class="btn btn-outline">Delete</button>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>


            <?php $count1++; ?>

        @endforeach
    @endif
</div>


<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDA64cfke8eDuQuc8R_9gFFM6fgezeo7ps&callback=myMap"></script>


<table class="table" id="myTable" border="0">
    <tbody>
    <tr id="row0">
        <td>
            <div class="col-sm-10" id="Account1">
                <div class="form-group">
                    <label class="col-sm-1 control-label">Email</label>
                    <div class="col-sm-6" style="width: 51.5%;">
                         <input type="email" id="mail_address" name="mail_address" class="form-control" >
                    </div>
                    <button id="btn0" type="button" class="btn btn-primary" onclick="addEvent1212(this)">
                        <span id="icon0" class="glyphicon glyphicon-plus"></span>
                    </button>
                </div>
            </div>
        </td>
    </tr>
    </tbody>
</table>


<script>
    function addEvent1212() {

        var ni = document.getElementById('myDiv1');
        var numi1 = document.getElementById('theValue1212');
        var num1 = (document.getElementById("theValue1212").value - 1) + 2;
        numi1.value = num1;

        var divIdName1 = "Account1" + num1;
        var newdiv1 = document.createElement('div');
        newdiv1.setAttribute("id", divIdName1);
        newdiv1.setAttribute("class", "col-sm-11");
        newdiv1.innerHTML = "<div class='form-group'>" +
                "<label class='col-sm-1 control-label'>Email<span class='text-red'></span></label>" +
                "<div class='col-sm-5' style='width: 46%;'><input type='email' id=\"mail_address" + num1 + "\"  name=\"mail_address" + num1 + "\"  class='form-control'></div>" +
                "<div class='col-sm-1' style='margin-left: -14px;'>" +
                "<a href='javascript:;' onclick=\"removeEvent1212(\'" + divIdName1 + "\')\">" +
                "<button id='btn0' type='button' class='btn btn-primary'><span id='icon0' class='glyphicon glyphicon-minus'></span></button></a>" +
                "</div>"+
                "</div>";
        ni.appendChild(newdiv1);
        document.getElementById("i").value = document.getElementById("i").value + 1;
    }

    function removeEvent1212(divNum1, id) {
        var remove1 = document.getElementById('remove_id1').value;
        if (remove1 == "") {
            remove1 = id;
        }
        else {
            remove1 = remove1 + "," + id;
        }

        document.getElementById('remove_id1').value = remove1;
        var d1 = document.getElementById('myDiv1');
        var olddiv1 = document.getElementById(divNum1);
        d1.removeChild(olddiv1);
        document.getElementById("theValue1212").value = document.getElementById("theValue1212").value - 1;
    }
</script>

<input type="hidden" value="{{ trim($attribute_id,",") }}" name="attribute_id" id="attribute_id">
<input type="hidden" value="" name="remove_id1" id="remove_id1">
<input type="hidden" value="{{ !empty($count1)?$count1-1:''  }}" name="theValue1212" id="theValue1212"/>
<input type="hidden" value="{{ !empty($count1)?$count1-1:''  }}" name="i" id="i"/>