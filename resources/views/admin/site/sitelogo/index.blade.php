@extends('admin.layouts.app')
@section('content')

    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                Site Logo
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ url('admin/site/sitelogo') }}"> <i class="fa fa-dashboard"></i>  {{ $menu }} </a></li>
            </ol>

            <br>
            @include ('admin.error')
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title" style="float:right;">
                        <a href="{{ url('admin/site/sitelogo/create/') }}" ><button class="btn btn-info" type="button"><span class="fa fa-plus"></span></button></a>
                        <a href="{{ url('admin/site/sitelogo/') }}" ><button class="btn btn-default" type="button"><span class="fa fa-refresh"></span></button></a>
                    </h3>
                </div>
                <!-- /.box-header -->

                <div class="box-body table-responsive">
                    {{ Form::open(array('url' => array('admin/site/sitelogo/update_display_order'), 'method' => 'post','style'=>'display:inline')) }}
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>Edit</th>
                            <th>Id</th>
                            <th>Language</th>
                            <th>Name</th>
                            <th>Logo</th>
                            <th>Status</th>
                            <th>Delete</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $count = 0;?>
                        @foreach ($sitelogo as $list)
                            <?php $count++; ?>
                            <input type="hidden" name="pid<?php echo $count;?>" id="pid<?php echo $count;?>" value="{{ $list['id'] }}" />
                            <tr>
                                <td align="center">
                                    <a href="{{ route('admin.site.sitelogo.edit', $list->id) }}"> <button class="btn btn-info" type="button"><i class="fa fa-edit"></i></button></a>
                                </td>

                                <td>{{ $list['id'] }}</td>
                                <td>{{ $list['language'] }}</td>
                                <td>{{ $list['name'] }}</td>
                                <td>
                                    @if($list['path']!="" && file_exists($list['path']))
                                        <img src="{{ url($list->path) }}" width="50">
                                    @endif
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
                                            <h4 class="modal-title">Delete Site Logo</h4>
                                        </div>
                                        <div class="modal-body">
                                            <p>Are you sure you want to delete this Site Logo ?</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Close</button>
                                            <a href="{{ route('admin.site.sitelogo.destroy',$list['id']) }}" data-method="delete" name="delete_item">
                                                <button type="button" class="btn btn-outline">Delete</button>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                    </table>
                    {{-- <div style="text-align:right;float:right;"> @include('admin.pagination.limit_links', ['paginator' => $sitelogo])</div>--}}
                    <input type="hidden" name="count" id="count" value="<?php echo $count;?>" />
                    {{ Form::close() }}

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
                url: '{{url('api/cms/site/sitelogo/assign')}}',
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
                url: '{{url('api/cms/site/sitelogo/unassign')}}',
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