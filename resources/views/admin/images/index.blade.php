@extends('admin.layouts.app')
@section('content')

    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                Image Center
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ url('admin/images') }}"> <i class="fa fa-dashboard"></i>  Image Center</a></li>
            </ol>

            <br>
            @include ('admin.error')
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title" style="float:right;">
                        <a href="{{ url('admin/images/create') }}" ><button class="btn btn-info" type="button"><span class="fa fa-plus"></span></button></a>
                        <a href="{{ url('admin/images') }}" ><button class="btn btn-default" type="button"><span class="fa fa-refresh"></span></button></a>
                    </h3>
                </div>

                <div class="box-body table-responsive">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>Edit</th>
                            <th>Id</th>
                            <th>Image</th>
                            <th>Delete</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($images as $list)
                            <tr>
                                <td align="center">
                                    <a href="{{ route('admin.images.edit', $list->id) }}"> <button class="btn btn-info" type="button"><i class="fa fa-edit"></i></button></a>
                                </td>

                                <td>{{ $list['id'] }}</td>
                                <td>
                                    @if($list['image']!="" && file_exists($list['image']))
                                        <img src="{{ url($list->image) }}" width="50">
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
                                            <h4 class="modal-title">Delete Image</h4>
                                        </div>
                                        <div class="modal-body">
                                            <p>Are you sure you want to delete this Image ?</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Close</button>
                                            <a href="{{ url('admin/images/'.$list['id'].'/destroy') }}" data-method="delete" name="delete_item">
                                                <button type="button" class="btn btn-outline">Delete</button>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </table>
                </div>
            </div>
        </section>
    </div>
@endsection
