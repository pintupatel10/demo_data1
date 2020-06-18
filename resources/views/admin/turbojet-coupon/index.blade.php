@extends('admin.layouts.app')


@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                <h1>
                    Turbojet Coupons
                    <small>Add</small>
                </h1>

            </h1>
            <ol class="breadcrumb">
                <li class="active">Add</li>
            </ol>
        </section>

        <section class="content">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">Turbojet Coupons</h3>
                </div>
                <!-- /.box-header -->

                <div class="box-body table-responsive">
                    <div><a href="{{ url("admin/turbojet-coupon/create") }}" ><button class="btn bg-orange margin" type="button">Add Coupon</button></a></div>

                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>Id</th>
                            <th>Code</th>
                            <th>Route</th>
                            <th>Type</th>
                            <th>Seat Class</th>
                            <th>Weekday</th>
                            <th>Weekend</th>
                            <th>Day Sail</th>
                            <th>Night Sail</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($coupons as $coupon)
                            <tr>
                                <td>{{ $coupon->{App\TurbojetCoupon::COLUMN_ID} }}</td>
                                <td>{{ $coupon->{App\TurbojetCoupon::COLUMN_CODE} }}</td>
                                <td>{{ $coupon->route ? $coupon->route->{App\TurbojetCouponRoute::COLUMN_NAME} : "" }}</td>
                                <td>{{ App\TurbojetCoupon::$types[$coupon->{App\TurbojetCoupon::COLUMN_TYPE}] }}</td>
                                <td>{{ App\TurbojetCoupon::$classes[$coupon->{App\TurbojetCoupon::COLUMN_SEAT_CLASS}] }}</td>
                                <td>@if ($coupon->{App\TurbojetCoupon::COLUMN_IS_WEEKDAY})<i class="fa fa-check"></i>@endif</td>
                                <td>@if ($coupon->{App\TurbojetCoupon::COLUMN_IS_WEEKEND})<i class="fa fa-check"></i>@endif</td>
                                <td>@if ($coupon->{App\TurbojetCoupon::COLUMN_IS_DAY})<i class="fa fa-check"></i>@endif</td>
                                <td>@if ($coupon->{App\TurbojetCoupon::COLUMN_IS_NIGHT})<i class="fa fa-check"></i>@endif</td>
                                <td>
                                    <div class="btn-group-horizontal">
                                        <a href="{{ url("admin/turbojet-coupon/$coupon->id") }}"> <button class="btn btn-success" type="button"><i class="fa fa-edit"></i></button></a>
                                        <button class="btn btn-danger" type="button" data-toggle="modal" data-target="#myModal{{$coupon['id']}}"><i class="fa fa-trash"></i></button>
                                    </div>
                                </td>
                            </tr>

                            <div id="myModal{{$coupon['id']}}" class="fade modal modal-danger" role="dialog" >
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span></button>
                                            <h4 class="modal-title">Delete Turbojet Coupon</h4>
                                        </div>
                                        <div class="modal-body">
                                            <p>Are you sure you want to delete this Turbojet Coupon ?</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Close</button>
                                            <button type="button" class="btn btn-outline" onclick="delete_item('{{ $coupon['id'] }}')">Delete</button>
                                        </div>
                                    </div>
                                    <!-- /.modal-content -->
                                </div>
                                {{--{{ Form::close() }}--}}
                            </div>
                        @endforeach

                    </table>


                    {!! Form::open(['id' => 'delete-form', 'url' => url("admin/turbojet-coupon"), 'method' => 'delete', 'class' => 'form-horizontal']) !!}
                    {{ Form::close() }}

                    <script type="text/javascript">
                        function delete_item(id) {
                            var url = $("#delete-form").attr("action") + "/" + id;
                            $("#delete-form").attr("action", url).submit();
                        }
                    </script>

                </div>

            </div>
        </section>
    </div>
@endsection



