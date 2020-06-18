@extends('admin.layouts.app')
@section('content')
    <div class="content-wrapper" style="min-height: 946px;">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                {{ $menu }}
                <small>View</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ url('admin/contact/contact_record') }}"> <i class="fa fa-dashboard"></i> {{ $menu }} </a>  </li>
                <li class="active">View</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">View Contact Record Detail</h3>
                        </div>
                        <div class="box-body">
                            <table class="table table-bordered">
                                <tbody>
                                <tr>
                                    <th>Id</th>
                                    <td>{{$user->id}}</td>
                                </tr>
                                <tr>
                                    <th>Title</th>
                                    <td>{{$user->title}}</td>
                                </tr>
                                <tr>
                                    <th>LastName</th>
                                    <td>{{$user->lastname}}</td>
                                </tr>
                                <tr>
                                    <th>FirstName</th>
                                    <td>{{$user->firstname}}</td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td>{{$user->email}}</td>
                                </tr>
                                <tr>
                                    <th>Telephone No.</th>
                                    <td>{{$user->telephone}}</td>
                                </tr>
                                <tr>
                                    <th>Address</th>
                                    <td>{{$user->address}}</td>
                                </tr>
                                <tr>
                                    <th>Fax No.</th>
                                    <td>{{$user->fax_no}}</td>
                                </tr>
                                <tr>
                                    <th>Country</th>
                                    <td>{{$user->country}}</td>
                                </tr>
                                <tr>
                                    <th>Message</th>
                                    <td>{{$user->message}}</td>
                                </tr>

                                <tr>
                                    <th>Created At</th>
                                    <td>{{$user->created_at}}</td>
                                </tr>

                                <tr>
                                    <th>Follow up by</th>
                                    <td>{{$user->follow_up}}</td>
                                </tr>

                                </tbody></table>
                            <div class="box-footer">
                                <a href="{{ url('admin/contact/contact_record') }}" ><button class="btn btn-default" type="button">Back</button></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>
@endsection
