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
                <li> <i class="fa fa-dashboard"></i> <a href="{{ url('admin/contact/contact_record') }}"> {{ $menu }} </a></li>
                <li class="active">View</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">View Hotel Contact Record Detail</h3>
                        </div>
                        <div class="box-body">
                            <table class="table table-bordered">
                                <tbody>
                                <tr>
                                    <th>Id</th>
                                    <td>{{$hotel->id}}</td>
                                </tr>
                                <tr>
                                    <th>Title</th>
                                    <td>{{$hotel->title}}</td>
                                </tr>
                                <tr>
                                    <th>LastName</th>
                                    <td>{{$hotel->lastname}}</td>
                                </tr>
                                <tr>
                                    <th>FirstName</th>
                                    <td>{{$hotel->firstname}}</td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td>{{$hotel->email}}</td>
                                </tr>
                                <tr>
                                    <th>Telephone No.</th>
                                    <td>{{$hotel->telephone}}</td>
                                </tr>
                                <tr>
                                    <th>Address</th>
                                    <td>{{$hotel->address}}</td>
                                </tr>
                                <tr>
                                    <th>Fax No.</th>
                                    <td>{{$hotel->fax_no}}</td>
                                </tr>
                                <tr>
                                    <th>Country</th>
                                    <td>{{$hotel->country}}</td>
                                </tr>
                                <tr>
                                    <th>Message</th>
                                    <td>{{$hotel->message}}</td>
                                </tr>

                                <tr>
                                    <th>Created At</th>
                                    <td>{{$hotel->created_at}}</td>
                                </tr>

                                </tbody></table>
                            <div class="box-footer">
                                <a href="{{ url('admin/hotel/contact_record') }}" ><button class="btn btn-default" type="button">Back</button></a>
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
