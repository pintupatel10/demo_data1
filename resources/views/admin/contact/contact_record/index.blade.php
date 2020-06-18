@extends('admin.layouts.app')
@section('content')

    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                Contact Record
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ url('admin/contact/contact_record') }}"> <i class="fa fa-dashboard"></i> Contact Record </a></li>
            </ol>

            <br>
            @include ('admin.error')
            <div class="box">
                <!-- /.box-header -->

                <div class="box-body table-responsive">
                    {{ Form::open(array('url' => array('admin/contact/contact_record'), 'method' => 'post','style'=>'display:inline')) }}
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>Title</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Telephone No.</th>
                            <th>Contact time</th>
                            <th>Follow up by</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($contact as $list)
                            <tr>
                                <td>{{ $list['title'] }}</td>
                                <td>{{ $list['firstname'] }}</td>
                                <td>{{ $list['email'] }}</td>
                                <td>{{ $list['telephone'] }}</td>
                                <td>{{ $list['created_at'] }}</td>
                                <td>{{ $list['follow_up'] }}</td>

                                <td><div class="btn-group-horizontal">
                                        <a href="{{ route('admin.contact.contact_record.show', $list->id) }}"> <button class="btn btn-info" type="button"><i class="fa fa-eye"></i></button></a>
                                        <button class="btn btn-social-icon btn-odnoklassniki" type="button" data-toggle="modal" data-target="#myModal{{$list['id']}}"><i class="fa fa-odnoklassniki"></i></button>
                                    </div></td>
                            </tr>


                            <div id="myModal{{$list['id']}}" class="fade modal modal-info" role="dialog" >
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span></button>
                                            <h4 class="modal-title">Follow up by</h4>
                                        </div>
                                        <div class="modal-body">
                                            <p>Are you sure you want to Follow ?</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">No</button>
                                            <a href="{{ url('admin/contact/contact_record/'.$list['id'].'/destroy') }}" data-method="delete" name="delete_item">
                                                <button type="button" class="btn btn-outline">Yes</button>
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

        </section>

    </div>
@endsection