@extends('admin.layouts.app')
@section('content')

    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                Tour Pricegroup
            </h1>
            <ol class="breadcrumb">
                <li> <i class="fa fa-dashboard"></i> Tour </li>
                <li class="active">Tour Pricegroup</li>
            </ol>

            <br>
            @include ('admin.error')
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title"><a href="{{ url('admin/tour/pricegroup/create/') }}" ><button class="btn bg-orange margin" type="button">Add Tour Pricegroup</button></a></h3>
                </div>
                <!-- /.box-header -->

                <div class="box-body table-responsive">
                    {{ Form::open(array('url' => array('admin/tour/pricegroup/update_display_order'), 'method' => 'post','style'=>'display:inline')) }}
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>Id</th>
                            <th>Title</th>
                            <th>Language</th>
                            <th>Display Order</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $count = 0;?>
                        @foreach ($pricegroup as $list)
                            <?php $count++; ?>
                            <input type="hidden" name="pid<?php echo $count;?>" id="pid<?php echo $count;?>" value="{{ $list['id'] }}" />
                            <tr>
                                <td>{{ $list['id'] }}</td>
                                <td>{{ $list['title'] }}</td>
                                <td>{{ $list['language'] }}</td>

                                <td  class="photo">
                                    <input  onKeyPress="return keycode();" size="5" type="textbox" name="disp<?php echo $count; ?>" value="{{$list['displayorder']}}" >
                                    <input onKeyPress="return keycode();" size="5" type="textbox" name="pid<?php echo $count; ?>" value="{{$list['id']}}" style="display:none" >
                                </td>
                                <td>
                                    @if($list['status'] == 'active')
                                        <div class="btn-group-horizontal" id="assign_remove_{{ $list['id'] }}" >
                                            <button class="btn btn-success unassign ladda-button" data-style="slide-left" id="remove" ruid="{{ $list['id'] }}"  type="button"><span class="ladda-label">Active</span> </button>
                                        </div>
                                        <div class="btn-group-horizontal" id="assign_add_{{ $list['id'] }}"  style="display: none"  >
                                            <button class="btn btn-danger assign ladda-button" data-style="slide-left" id="assign" uid="{{ $list['id'] }}"  type="button"><span class="ladda-label">De-active</span></button>
                                        </div>
                                    @endif
                                    @if($list['status'] == 'inactive')
                                        <div class="btn-group-horizontal" id="assign_add_{{ $list['id'] }}"   >
                                            <button class="btn btn-danger assign ladda-button" id="assign" data-style="slide-left" uid="{{ $list['id'] }}"  type="button"><span class="ladda-label">De-active</span></button>
                                        </div>
                                        <div class="btn-group-horizontal" id="assign_remove_{{ $list['id'] }}" style="display: none" >
                                            <button class="btn  btn-success unassign ladda-button" id="remove" ruid="{{ $list['id'] }}" data-style="slide-left"  type="button"><span class="ladda-label">Active</span></button>
                                        </div>
                                    @endif
                                </td>

                                <td>
                                    <div class="btn-group-horizontal">
                                        <a href="{{ route('admin.tour.pricegroup.edit', $list->id) }}"> <button class="btn btn-success" type="button"><i class="fa fa-edit"></i></button></a>
                                        <button class="btn btn-danger" type="button" data-toggle="modal" data-target="#myModal{{$list['id']}}"><i class="fa fa-trash"></i></button>
                                    </div>
                                </td>
                            </tr>

                            <div id="myModal{{$list['id']}}" class="fade modal modal-danger" role="dialog" >
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span></button>
                                            <h4 class="modal-title">Delete Tour Pricegroup</h4>
                                        </div>
                                        <div class="modal-body">
                                            <p>Are you sure you want to delete this Tour Pricegroup ?</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Close</button>
                                            <a href="{{ route('admin.tour.pricegroup.destroy',$list['id']) }}" data-method="delete" name="delete_item">
                                                <button type="button" class="btn btn-outline">Delete</button>
                                            </a>
                                        </div>
                                    </div>
                                    <!-- /.modal-content -->
                                </div>
                                {{--{{ Form::close() }}--}}
                            </div>
                        @endforeach

                    </table>
                    <div style="text-align:right;float:right;"> @include('admin.pagination.limit_links', ['paginator' => $pricegroup])</div>
                    <button class="btn bg-orange margin" type="submit">Update</button>
                    <input type="hidden" name="count" id="count" value="<?php echo $count;?>" />
                    {{ Form::close() }}

                </div>

            </div>

        </section>

    </div>
@endsection

<script src="{{ URL::asset('assets/jquery.js')}}"></script>
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
                url: '{{url('api/cms/tour/pricegroup/assign')}}',
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
                url: '{{url('api/cms/tour/pricegroup/unassign')}}',
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