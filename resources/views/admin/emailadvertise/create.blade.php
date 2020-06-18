@extends('admin.layouts.app')

@section('content')
    <div class="content-wrapper" style="min-height: 946px;">
        <section class="content-header">
            <h1>
                <h1>
                    {{ $menu }}
                    <small>Add</small>
                </h1>

            </h1>
            <ol class="breadcrumb">
                <li> <i class="fa fa-dashboard"></i> <a href="{{ url('admin/emailadvertise') }}"> {{ $menu }} </a></li>
                <li class="active">Add</li>
            </ol>
        </section>

        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title">ADD Email Advertise </h3>
                        </div>
                        {!! Form::open(['url' => url('admin/emailadvertise'), 'class' => 'form-horizontal','files'=>true]) !!}
                        <div class="box-body">

                            @include ('admin.emailadvertise.form')
                            <div class="box box-info">
                                <br>

                            <table id="example1" class="table table-bordered table-striped">

                                <thead>
                                <th><input type="checkbox"  onClick="toggle(this)" />Select All</th>
                                <tr>
                                    <th>Select</th>
                                    <th>Id</th>
                                    <th>Title</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>

                                @foreach ($emailcollection as $list)
                                    <tr>
                                        <td><input type="checkbox" name="email[]" id="checkbox<?php echo $list['id']; ?>" value="<?php echo $list['email']; ?>" @if(  isset($event_product) && in_array($list['id'],$event_product)) checked @endif /></td>
                                        <td>{{ $list['id'] }}</td>
                                        <td>{{ $list['title'] }}</td>
                                        <td>{{ $list['name'] }}</td>
                                        <td>{{ $list['email'] }}</td>
                                        <td>
                                            <div class="btn-group-horizontal">
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
                                                    <h4 class="modal-title">Delete Email</h4>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Are you sure you want to delete this Email ?</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Close</button>
                                                    <a href="{{ url('admin/emailcollect/'.$list['id'] )}}" data-method="get" name="delete_item">
                                                        <button type="button" class="btn btn-outline">Delete</button>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </table>
                                <br>
                            </div>
                        </div>
                        <div class="box-footer">
                            <a href="{{ url('admin/emailadvertise') }}" ><button class="btn btn-default" type="button">Back</button></a>
                            <button class="btn btn-info pull-right" type="submit">Send</button>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
<script language="JavaScript">
    function toggle(source) {
        checkboxes = document.getElementsByName('email[]');
        for(var i=0, n=checkboxes.length;i<n;i++) {
            checkboxes[i].checked = source.checked;
        }
    }
</script>

