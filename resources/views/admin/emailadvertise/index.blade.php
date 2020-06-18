
@extends('admin.layouts.app')
@section('content')

    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                Email Advertise
            </h1>
            <ol class="breadcrumb">
                <li> <i class="fa fa-dashboard"></i> <a href="{{ url('admin/emailadvertise') }}"> {{ $menu }} </a></li>
            </ol>
            <br>
            @include ('admin.error')
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title" style="float:right;">
                        <a href="{{ url('admin/emailadvertise/create/') }}" ><button class="btn btn-info" type="button"><span class="fa fa-plus"></span></button></a>
                        <a href="{{ url('admin/emailadvertise/') }}" ><button class="btn btn-default" type="button"><span class="fa fa-refresh"></span></button></a>
                    </h3>
                </div>

                <!-- /.box-header -->
                <div class="box-body">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>Edit</th>
                            <th>Id</th>
                            <th>Subject</th>
                            <th>No.of Receiver</th>
                            <th>Sending Date</th>
                            <th>Status</th>
                            <th>Delete</th>
                        </tr>
                        </thead>
                        <tbody>

                        @foreach ($emailadvertise as $list)

                            <tr>
                                <td align="center">
                                    <a href="{{ route('admin.emailadvertise.edit', $list->id) }}"> <button class="btn btn-info" type="button"><i class="fa fa-edit"></i></button></a>
                                </td>

                                <td>{{ $list['id'] }}</td>
                                <td>{{ $list['subject'] }}</td>
                                <td>{{ $list['no_of_mail'] }}</td>
                                <td>{{ $list['sendingdate'] }}</td>
                                <td>{{ $list['status'] }}</td>
                                <td align="center">
                                        <button class="btn btn-danger" type="button" data-toggle="modal" data-target="#myModal{{$list['id']}}"><i class="fa fa-trash"></i></button>
                                </td>
                            </tr>

                            <div id="myModal{{$list['id']}}" class="fade modal modal-danger" role="dialog" >
                                {{ Form::open(array('url' => 'admin/emailadvertise/'.$list['id'], 'method' => 'delete','style'=>'display:inline')) }}
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span></button>
                                            <h4 class="modal-title">Delete Advertise</h4>
                                        </div>
                                        <div class="modal-body">
                                            <p>Are you sure you want to delete this Advertise ?</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-outline">Delete</button>
                                        </div>
                                    </div>
                                    <!-- /.modal-content -->
                                </div>
                                {{ Form::close() }}
                            </div>

                        @endforeach

                    </table>
                </div>
                <!-- /.box-body -->
            </div>

        </section>

        <!-- Main content -->

        <!-- /.content -->
    </div>
@endsection
