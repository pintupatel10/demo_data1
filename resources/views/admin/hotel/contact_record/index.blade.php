@extends('admin.layouts.app')
@section('content')

    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                Hotel Contact Record
            </h1>
            <ol class="breadcrumb">
                <li> <i class="fa fa-dashboard"></i> <a href="{{ url('admin/contact/contact_record') }}"> {{ $menu }} </a></li>
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

                                <td><div class="btn-group-horizontal">
                                        <a href="{{ route('admin.hotel.hotelcontact.show', $list->id) }}"> <button class="btn btn-info" type="button"><i class="fa fa-eye"></i></button></a>
                                    </div></td>
                            </tr>

                        @endforeach

                    </table>
                    {{ Form::close() }}

                </div>

            </div>

        </section>

    </div>
@endsection