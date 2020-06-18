
<hr>
<h1>Private Transportation Checkpoint </h1>
<br>
<div class="box">
    <div class="box-header" >

        <h3 class="box-title" style="float:right;">
            {{ Form::open(['url' => url('admin/privatetransportation/'.$detail->id.'/checkpoint'), 'id'=>'checkpoint_form','class' => 'form-horizontal','files'=>true]) }}
            <input type="hidden" name="checkpoint_id" value="" id="checkpoint_id">
            <button class="btn btn-sucess" type="button" data-toggle="modal" data-target="#CheckpointModal">Choose Checkpoint</button>
            <a href="{{ url('admin/privatetransportation/'.$detail->id .'/checkpoint/create/')}}" ><button class="btn btn-info" type="button"><span class="fa fa-plus"></span></button></a>
            <a href="{{ url('admin/privatetransportation/privatetransportationlist/'.$detail->id.'/edit') }}" ><button class="btn btn-default" type="button"><span class="fa fa-refresh"></span></button></a>
            {{ Form::close() }}

        </h3>
    </div>

    <div id="CheckpointModal" class="modal fade" role="dialog">

        <div class="modal-dialog" style="width: 85%; !important;">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" id="close" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Choose Checkpoint</h4>
                </div>
                <div class="modal-body">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>Id</th>
                            <th>Title</th>
                            <th>Image</th>
                            <th>Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($checkpoint_center as $chk_list)
                            <tr class="ui-state-default" onclick="get_checkpoint('{{$chk_list->id}}')">
                                <td>{{ $chk_list['id'] }}</td>
                                <td>{{ $chk_list['title'] }}</td>
                                <td>
                                    @if($chk_list['image']!="" && file_exists($chk_list['image']))
                                        <img src="{{ url($chk_list->image) }}" width="50">
                                    @endif
                                </td>
                                <td>
                                    @if($chk_list['status'] == 'active')
                                        <div class="btn-group-horizontal">
                                            <button disabled class="btn btn-success" data-style="slide-left"  type="button"><span class="">Active</span> </button>
                                        </div>
                                        <div class="btn-group-horizontal" style="display: none"  >
                                            <button  class="btn btn-danger" type="button"><span class="">De-active</span></button>
                                        </div>
                                    @endif
                                    @if($chk_list['status'] == 'inactive')
                                        <div class="btn-group-horizontal">
                                            <button class="btn btn-danger" type="button"><span class="">De-active</span></button>
                                        </div>
                                        <div class="btn-group-horizontal" style="display: none">
                                            <button disabled class="btn  btn-success " type="button"><span class="">Active</span></button>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @endforeach

                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="box-body">
        {{ Form::open(array('url' => array('admin/privatetransportation/checkpoint/update_display_order'), 'method' => 'post','style'=>'display:inline')) }}
        <table id="example1" class="table table-bordered table-striped">
            <thead>
            <tr>
                <th>Edit</th>
                <th>Title</th>
                <th>Image</th>
                <th>Status</th>
                <th>Delete</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($checkpoint as $list)
                <tr class="ui-state-default" id="arrayorder_{{$list['id']}}">
                    <td align="center">
                        <a href="{{ url('admin/privatetransportation/'.$detail->id.'/checkpoint/'.$list->id.'/edit')}}"> <button class="btn btn-info" type="button"><i class="fa fa-edit"></i></button></a>
                    </td>

                    <td>{{ $list['title'] }}</td>
                    <td>
                        @if($list['image']!="" && file_exists($list['image']))
                            <img src="{{ url($list->image) }}" width="50">
                        @endif
                    </td>
                    <td>
                        @if($list['status'] == 'active')
                            <div class="btn-group-horizontal" id="assign_remove_{{ $list['id'] }}">
                                <button class="btn btn-success unassign ladda-button" data-style="slide-left" id="remove" ruid="{{ $list['id'] }}"  type="button"><span class="ladda-label">Active</span> </button>
                            </div>
                            <div class="btn-group-horizontal" id="assign_add_{{ $list['id'] }}"  style="display: none"  >
                                <button class="btn btn-danger assign ladda-button" data-style="slide-left" id="assign" uid="{{ $list['id'] }}"  type="button"><span class="ladda-label">De-active</span></button>
                            </div>
                        @endif
                        @if($list['status'] == 'inactive')
                            <div class="btn-group-horizontal" id="assign_add_{{ $list['id'] }}">
                                <button class="btn btn-danger assign ladda-button" id="assign" data-style="slide-left" uid="{{ $list['id'] }}"  type="button"><span class="ladda-label">De-active</span></button>
                            </div>
                            <div class="btn-group-horizontal" id="assign_remove_{{ $list['id'] }}" style="display: none">
                                <button class="btn  btn-success unassign ladda-button" id="remove" ruid="{{ $list['id'] }}" data-style="slide-left"  type="button"><span class="ladda-label">Active</span></button>
                            </div>
                        @endif
                    </td>

                    <td align="center">
                        <button class="btn btn-danger" type="button" data-toggle="modal" data-target="#myModal{{$list['id']}}"><i class="fa fa-trash"></i></button>
                    </td>

                </tr>

                <div id="myModal{{$list['id']}}" class="fade modal modal-danger" role="dialog" >
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title">Delete Private Transportation Checkpoint</h4>
                            </div>
                            <div class="modal-body">
                                <p>Are you sure you want to delete this Private Transportation Checkpoint ?</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Close</button>
                                <a href="{{ url('admin/privatetransportation/'.$detail->id.'/checkpoint/'.$list->id.'/destroy') }}" name="delete_item">
                                    <button type="button" class="btn btn-outline">Delete</button>
                                </a>
                            </div>
                        </div>

                    </div>

                </div>
            @endforeach

        </table>

        {{ Form::close() }}

    </div>
</div>



<script src="{{ URL::asset('assets/plugins/jQuery/jQuery-2.2.0.min.js')}}"></script>
<link rel="stylesheet" href="{{ URL::asset('assets/pk/ladda-theme.min.css')}}">
<script src="{{ URL::asset('assets/pk/sp.min.js')}}"></script>
<script src="{{ URL::asset('assets/pk/ladd.min.js')}}"></script>
<script>Ladda.bind( 'input[type=submit]' );</script>
<script type="text/javascript">
    $(document).ready(function(){
        $('.assign').click(function(){

            var user_id = $(this).attr('uid');
            var l = Ladda.create(this);
            l.start();
            $.ajax({
                url: '{{url('api/cms/transportation/transportationlist/{detailid}/assign')}}',
                type: "get",
                data: {'id': user_id,'X-CSRF-Token' : $('meta[name=_token]').attr('content')},
                success: function(data){
                    l.stop();
                    $('#assign_remove_'+user_id).show();
                    $('#assign_add_'+user_id).hide();
                }
            });
        });

        $('.unassign').click(function(){
            //alert('in');
            var user_id = $(this).attr('ruid');
            var l = Ladda.create(this);
            l.start();
            $.ajax({
                url: '{{url('api/cms/transportation/transportationlist/{detailid}/unassign')}}',
                type: "get",
                data: {'id': user_id,'X-CSRF-Token' : $('meta[name=_token]').attr('content')},
                success: function(data){
                    l.stop();
                    $('#assign_remove_'+user_id).hide();
                    $('#assign_add_'+user_id).show();
                }
            });
        });


        $('[data-method]').append(function(){
                    return "\n"+
                            "<form action='"+$(this).attr('href')+"' method='POST' name='delete_item' style='display:none'>\n"+
                            "   <input type='hidden' name='_method' value='"+$(this).attr('data-method')+"'>\n"+
                            "   <input type='hidden' name='_token' value='"+$('meta[name="_token"]').attr('content')+"'>\n"+
                            "</form>\n"
                })
                .removeAttr('href')
                .attr('style','cursor:pointer;')
                .attr('onclick','$(this).find("form").submit();');
    });

    function get_checkpoint(checkpoint_id){

        document.getElementById('checkpoint_id').value = checkpoint_id;

        document.getElementById('checkpoint_form').submit();
    }
</script>