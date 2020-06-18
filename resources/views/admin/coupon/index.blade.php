@extends('admin.layouts.app')
@section('content')

    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                Coupon
            </h1>
            <ol class="breadcrumb">
                <li> <i class="fa fa-dashboard"></i> <a href="{{ url('admin/coupon') }}"> {{ $menu }} </a></li>
            </ol>

            <br>
            @include ('admin.error')

            <div class="box">

                <div class="box-header">
                    <h3 class="box-title" style="float:right;">
                        <a href="{{ url('admin/coupon/create/') }}" ><button class="btn btn-info" type="button"><span class="fa fa-plus"></span></button></a>
                        <a href="{{ url('admin/coupon') }}" ><button class="btn btn-default" type="button"><span class="fa fa-refresh"></span></button></a>
                    </h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body table-responsive">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>Edit</th>
                            <th>Id</th>
                            <th>Title</th>
                            <th>Coupon Code</th>
                            <th>Type</th>
                            <th>Discount By</th>
                            <th>Remain Quota</th>
                            <th>Status</th>
                            <th>Delete</th>
                        </tr>
                        </thead>
                        <tbody>

                        @foreach ($coupon as $list)
                            <tr>
                                <td align="center">
                                    {{ Form::open(array('route' => array('admin.coupon.edit', $list['id']), 'method' => 'get','style'=>'display:inline')) }}
                                    <button class="btn btn-info" type="submit" ><i class="fa fa-edit"></i></button>
                                    {{ Form::close() }}
                                </td>
                                <td>{{ $list['id'] }}</td>
                                <td>{{ $list['title'] }}</td>
                                <td>{{ $list['couponcode'] }}</td>
                                <td>{{ $list['type'] }}</td>
                                <td>{{ $list['discountby'] }}</td>
                                <td>{{ $list['quota'] }}</td>
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

                                <td align="center">
                                    <button class="btn btn-danger" type="button" data-toggle="modal" data-target="#myModal{{$list['id']}}"><i class="fa fa-trash"></i></button>
                                </td>

                            </tr>

                            <div id="myModal{{$list['id']}}" class="fade modal modal-danger" role="dialog" >
                                {{ Form::open(array('route' => array('admin.coupon.destroy', $list['id']), 'method' => 'delete','style'=>'display:inline')) }}
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span></button>
                                            <h4 class="modal-title">Delete Coupon</h4>
                                        </div>
                                        <div class="modal-body">
                                            <p>Are you sure you want to delete this Coupon ?</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-outline">Delete</button>
                                        </div>
                                    </div>

                                </div>
                                {{ Form::close() }}
                            </div>
                        @endforeach

                    </table>
                </div>

            </div>

        </section>

    </div>
@endsection
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
                url: '{{url('api/cms/coupon/assign')}}',
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
                url: '{{url('api/cms/coupon/unassign')}}',
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