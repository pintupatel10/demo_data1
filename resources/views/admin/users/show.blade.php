@extends('admin.layouts.app')
@section('content')
    <div class="content-wrapper" style="min-height: 946px;">

        <section class="content-header">
            <h1>
                User
                <small>View</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ url('admin/users') }}"> <i class="fa fa-dashboard"></i>  {{ $menu }} </a></li>
                <li class="active">View</li>
            </ol>
        </section>


        <section class="content">
            <div class="row">
                <div class="col-md-6">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">View User Detail</h3>
                        </div>
                        <div class="box-body">
                            <table class="table table-bordered">
                                <tbody>
                                <tr>
                                    <th>Id</th>
                                    <td>{{$article->id}}</td>
                                </tr>
                                <tr>
                                    <th>Name</th>
                                    <td>{{$article->name}}</td>
                                </tr>
                                <tr>
                                    <th>email</th>
                                    <td>{{$article->email}}</td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>{!!  $article['status']=='active'? '<span class="label label-success">Active</span>' : '<span class="label label-danger">In-active</span>' !!}</td>
                                </tr>
                                <tr>
                                    <th>Created At</th>
                                    <td>{{$article->created_at}}</td>
                                </tr>
                                <tr>
                                    <th>Updated At</th>
                                    <td>{{$article->updated_at}}</td>
                                </tr>
                                </tbody></table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection