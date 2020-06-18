
<br><br>
<h1> TicketInventory </h1>
<br>
<div class="box">
    <div class="box-header" >
        <h3 class="box-title" style="float:right;">
            <a href="{{ url('admin/ticket/'.$detail."/".$pricegroup->id.'/inventory/create/')}}" ><button class="btn btn-info" type="button"><span class="fa fa-plus"></span></button></a>
            <a href="" ><button class="btn btn-default" type="button"><span class="fa fa-refresh"></span></button></a>
        </h3>
    </div>
    <div class="box-body">
        {{ Form::open(array('url' => array('admin/ticket/inventory/update_display_order'), 'method' => 'post','style'=>'display:inline')) }}
        <table id="example6" class="table table-bordered table-striped">
            <thead>
            <tr>
                <th>Edit</th>
                <th>ID</th>
                <th>Title</th>
                <th>Default Quota</th>
                <th>Status</th>
                <th>Delete</th>
            </tr>
            </thead>
            <tbody>
            <?php $count = 0;?>
            @foreach ($inventory as $list)
                <input type="hidden" name="pid<?php echo $count;?>" id="pid<?php echo $count;?>" value="{{ $list['id'] }}" />
                <tr>
                    <td align="center">
                        <a href="{{ url('admin/ticket/'.$detail."/".$pricegroup->id.'/inventory/'.$list->id.'/edit')}}"> <button class="btn btn-info" type="button"><i class="fa fa-edit"></i></button></a>
                    </td>

                    <td>{{ $list['id'] }}</td>
                    <td>{{ $list['title'] }}</td>
                    <td>{{ $list['dquota'] }}</td>

                    <td>
                        @if($list['status'] == 'active')
                            <div class="btn-group-horizontal" id="assign_remove_{{ $list['id'] }}" >
                                <span class="label label-success">Active</span>
                            </div>
                        @endif
                        @if($list['status'] == 'inactive')
                            <div class="btn-group-horizontal" id="assign_add_{{ $list['id'] }}"   >
                                <span class="label label-danger">In-active</span>
                            </div>
                        @endif
                    </td>

                    <td align="center">
                        <button class="btn btn-danger" type="button" data-toggle="modal" data-target="#myModal1{{$list['id']}}"><i class="fa fa-trash"></i></button>
                    </td>

                </tr>

                <div id="myModal1{{$list['id']}}" class="fade modal modal-danger" role="dialog" >
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title">Delete Ticket Inventory</h4>
                            </div>
                            <div class="modal-body">
                                <p>Are you sure you want to delete this Ticket Inventory ?</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Close</button>
                                <a href="{{ url('admin/ticket/'.$detail."/".$pricegroup->id.'/inventory/'.$list->id.'/destroy') }}" data-method="delete" name="delete_item">
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