@extends('admin.layouts.app')
@section('content')

    <div class="content-wrapper">

        <section class="content" style="min-height: 197px; !important;">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-info">

                        <div class="box-header with-border">
                            <h3 class="box-title">Filter </h3>
                        </div>

                        {!! Form::open(['url' => url('admin/news/post'), 'method' => 'get', 'class' => 'form-horizontal']) !!}
                        <div class="box-body">
                            <div class="form-group{{ $errors->has('language') ? ' has-error' : '' }}">
                                <label class="col-sm-2 control-label" for="language">Language </label>
                                <div class="col-sm-5">
                                    {!! Form::select('language',\App\Newspost::$language1,isset($filter)?$filter:null, ['class' => 'form-control', 'style' => 'width: 100%']) !!}
                                </div>
                            </div>

                            <button class="btn btn-info pull" type="submit">Submit</button>

                        </div>

                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </section>

        <section class="content-header">
            <h1>
                News Post
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ url('admin/news/post') }}"> <i class="fa fa-dashboard"></i>  {{ $menu }} </a></li>
            </ol>

            <br>
            @include ('admin.error')
            <div id="responce" name="responce" class="alert alert-success" style="display: none">
            </div>
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title" style="float:right;">
                        <a href="{{ url('admin/news/post/create/') }}" ><button class="btn btn-info" type="button"><span class="fa fa-plus"></span></button></a>
                        <a href="{{ url('admin/news/post/') }}" ><button class="btn btn-default" type="button"><span class="fa fa-refresh"></span></button></a>
                    </h3>
                </div>

                <div class="box-body table-responsive">
                    {{ Form::open(array('url' => array('admin/news/post/update_display_order'), 'method' => 'post','style'=>'display:inline')) }}
                    <table id="example4" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>Edit</th>
                            <th>Id</th>
                            <th>Title</th>
                            <th>Language</th>
                            <th>Image</th>
                            <th>Created Date Time</th>
                            <th>Status</th>
                            <th>Delete</th>
                        </tr>
                        </thead>
                        <tbody id="sortable">
                        <?php $count = 0;?>
                        @foreach ($post as $list)
                            <?php $count++; ?>
                            <input type="hidden" name="pid<?php echo $count;?>" id="pid<?php echo $count;?>" value="{{ $list['id'] }}" />
                            <tr class="ui-state-default" id="arrayorder_{{$list['id']}}">

                                <td align="center">
                                    <a href="{{ route('admin.news.post.edit', $list->id) }}"> <button class="btn btn-info" type="button"><i class="fa fa-edit"></i></button></a>
                                </td>

                                <td>{{ $list['id'] }}</td>
                                <td>{{ $list['title'] }}</td>
                                <td>{{ $list['language'] }}</td>
                                <td>
                                    @if($list['image_upload']!="" && file_exists($list['image_upload']))
                                        <img src="{{ url($list->image_upload) }}" width="50">
                                    @endif
                                </td>
                                <td>{{ $list['created_at'] }}</td>
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
                                            <h4 class="modal-title">Delete News Post</h4>
                                        </div>
                                        <div class="modal-body">
                                            <p>Are you sure you want to delete this News Post ?</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Close</button>
                                            <a href="{{ route('admin.news.post.destroy',$list['id']) }}" data-method="delete" name="delete_item">
                                                <button type="button" class="btn btn-outline">Delete</button>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        </tbody>
                    </table>
    {{--  <div style="text-align:right;float:right;"> @include('admin.pagination.limit_links', ['paginator' => $post])</div>--}}
      <input type="hidden" name="count" id="count" value="<?php echo $count;?>" />
      {{ Form::close() }}

  </div>

</div>

</section>

</div>
@endsection


<link rel="stylesheet" href="{{ URL::asset('assets/plugins/drag_drop/jquery-ui.css')}}">
<script src="{{ URL::asset('assets/plugins/drag_drop/jquery-1.12.4.js')}}"></script>
<script src="{{ URL::asset('assets/plugins/drag_drop/jquery-ui.js')}}"></script>
<script>

    function slideout() {
        setTimeout(function() {
            $("#responce").slideUp("slow", function() {
            });

        }, 3000);
    }

    $("#responce").hide();
    $( function() {
        $( "#sortable" ).sortable({opacity: 0.9, cursor: 'move', update: function() {
            var order = $(this).sortable("serialize") + '&update=update';
            //alert(order);
            $.post("{{url('api/cms/news/post/reorder')}}", order, function(theResponse) {
                $("#responce").html(theResponse);
                //alert(theResponse);
                $("#responce").slideDown('slow');
                slideout();
            });
        }});
        $( "#sortable" ).disableSelection();
    } );
</script>

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
  url: '{{url('api/cms/news/post/assign')}}',
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
  url: '{{url('api/cms/news/post/unassign')}}',
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