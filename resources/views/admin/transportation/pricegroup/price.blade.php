
<table id="example1" class="table table-bordered table-striped">
    <thead>
    <tr>
        <th>Edit</th>
        <th>ID</th>
        <th>Title</th>
        <th>Weekend/Weekday</th>
        <th>Price</th>
        <th>Default Quota</th>
        <th>Status</th>
        <th>Delete</th>
    </tr>
    </thead>
    <tbody>
    <?php $count = 0;?>
    @foreach ($price as $list)
        <input type="hidden" name="pid<?php echo $count;?>" id="pid<?php echo $count;?>" value="{{ $list['id'] }}" />
        <tr>
            <td align="center">
                <a href="{{ url('admin/transportation/'.$detail."/".$pricegroup->id.'/price/'.$list->id.'/edit')}}"> <button class="btn btn-info" type="button"><i class="fa fa-edit"></i></button></a>
            </td>

            <td>{{ $list['id'] }}</td>
            <td>{{ $list['title'] }}</td>
            <td>{{ $list['Weekend/Weekday'] }}</td>
            <td>{{$list['price']}}</td>
            <td>{{ $list['dquota'] }}</td>

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
                        <h4 class="modal-title">Delete Transportation Price</h4>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to delete this Transportation Price ?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Close</button>
                        <a href="{{ url('admin/transportation/'.$detail."/".$pricegroup->id.'/price/'.$list->id.'/destroy') }}"  name="delete_item">
                            <button type="button" class="btn btn-outline">Delete</button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

</table>

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
                url: '{{url('api/cms/transportation/transportationlist/{detailid}/pricegroup/{pricegroupid}/assign')}}',
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
                url: '{{url('api/cms/transportation/transportationlist/{detailid}/pricegroup/{pricegroupid}/unassign')}}',
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

</script>