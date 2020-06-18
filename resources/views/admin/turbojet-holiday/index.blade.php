@extends('admin.layouts.app')


@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                <h1>
                    Turbojet Holidays
                    <small>Add</small>
                </h1>

            </h1>
            <ol class="breadcrumb">
                <li class="active">Add</li>
            </ol>
        </section>

        <section class="content">

            @include("admin.error")

            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">Turbojet Holidays</h3>
                </div>
                <!-- /.box-header -->

                <div class="box-body table-responsive">


                    {!! Form::open(['id' => 'turbojet-holiday-form', 'url' => url('admin/turbojet-holiday/upload'), 'method' => 'post', 'class' => 'form-horizontal','files'=>true]) !!}
                        <span class='btn btn-success btn-file' style="margin-bottom: 10px;">
                            <i class='fa fa-upload'></i> Upload Holiday File <input name='turbojet-holiday' type='file'>
                        </span>
                    {!! Form::close() !!}

                    <div><a href="{{ url("admin/turbojet-holiday/create") }}" ><button class="btn bg-orange" type="button"  style="margin-bottom: 10px;">Add Holiday</button></a></div>

                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Date</th>
                            <th>Created At</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($holidays as $holiday)
                            <tr>
                                <td>{{ $holiday->{App\TurbojetHoliday::COLUMN_ID} }}</td>
                                <td>{{ $holiday->{App\TurbojetHoliday::COLUMN_DATE}->format('Y-m-d') }}</td>
                                <td>{{ $holiday->created_at }}</td>
                                <td>
                                    <div class="btn-group-horizontal">
                                        <a href="{{ url("admin/turbojet-holiday/$holiday->id") }}"> <button class="btn btn-success" type="button"><i class="fa fa-edit"></i></button></a>
                                        <button class="btn btn-danger" type="button" data-toggle="modal" data-target="#myModal{{$holiday['id']}}"><i class="fa fa-trash"></i></button>
                                    </div>
                                </td>
                            </tr>

                            <div id="myModal{{$holiday['id']}}" class="fade modal modal-danger" role="dialog" >
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span></button>
                                            <h4 class="modal-title">Delete Turbojet Holiday</h4>
                                        </div>
                                        <div class="modal-body">
                                            <p>Are you sure you want to delete this Turbojet Holiday ?</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Close</button>
                                            <button type="button" class="btn btn-outline" onclick="delete_item('{{ $holiday['id'] }}')">Delete</button>
                                        </div>
                                    </div>
                                    <!-- /.modal-content -->
                                </div>
                                {{--{{ Form::close() }}--}}
                            </div>
                        @endforeach

                    </table>


                    {!! Form::open(['id' => 'delete-form', 'url' => url("admin/turbojet-holiday"), 'method' => 'delete', 'class' => 'form-horizontal']) !!}
                    {{ Form::close() }}

                    <script type="text/javascript">
                        function delete_item(id) {
                            var url = $("#delete-form").attr("action") + "/" + id;
                            $("#delete-form").attr("action", url).submit();
                        }

                        $(function (){
                            $("input[name=turbojet-holiday]").change(function (){
                                $("#turbojet-holiday-form").submit();
                            });
                        })
                    </script>

                </div>

            </div>
        </section>
    </div>
@endsection



