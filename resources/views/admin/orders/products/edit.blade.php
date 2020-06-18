@extends('admin.layouts.app')


@section('content')
    <style>
        @import url("{{ URL::asset('website/assets/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css')}}");

        .form-group .text {
            margin-top: 7px;
        }
    </style>


    <div class="content-wrapper" style="min-height: 946px;">
        <section class="content-header">
            <h1>
                Order Product #{{ $order_product->{App\OrderProduct::COLUMN_ID} }}
                @if ($order_product->{App\OrderProduct::COLUMN_STATUS} == App\OrderProduct::STATUS_IN_QUEUE)
                    <span class="label label-default">In-Queue</span>
                @elseif ($order_product->{App\OrderProduct::COLUMN_STATUS} == App\OrderProduct::STATUS_PENDING)
                    <span class="label label-warning">Pending</span>
                @elseif ($order_product->{App\OrderProduct::COLUMN_STATUS} == App\OrderProduct::STATUS_CONFIRM_DRAFTED)
                    <span class="label label-primary">Confirm Drafted</span>
                @elseif ($order_product->{App\OrderProduct::COLUMN_STATUS} == App\OrderProduct::STATUS_CONFIRMED)
                    <span class="label label-info">Confirmed</span>
                @elseif ($order_product->{App\OrderProduct::COLUMN_STATUS} == App\OrderProduct::STATUS_COMPLETED)
                    <span class="label label-success">Completed</span>
                @elseif ($order_product->{App\OrderProduct::COLUMN_STATUS} == App\OrderProduct::STATUS_CANCELED)
                    <span class="label label-danger">Cancelled</span>
                @endif
            </h1>
            <ol class="breadcrumb">
                <li class="active">edit</li>
            </ol>
        </section>


        {!! Form::model($order_product, ['url' => url("admin/order/$order->id/$order_product->id"), 'method' => 'put', 'class' => 'form-horizontal']) !!}

        <section class="content">

            @if (Session::has('success'))
                <div class="alert alert-success">
                    <strong>Success!</strong> {{ Session::get('success') }}
                </div>
            @endif

            <div class="row" style="margin-bottom: 10px">
                <div class="col-xs-6">
                    <a href="{{ url("admin/order/$order->id") }}" class="btn btn-default"><span class="glyphicon glyphicon-chevron-left"></span> Order Detail</a>
                </div>
                <div class="col-xs-6 text-right">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-floppy-o"></i> Save</button>
                </div>
            </div>

            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">Order Product Detail</h3>
                </div>

                <div class="box-body">

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Order Product No.</label>
                        <div class="col-sm-4 text">{{ $order_product->{App\OrderProduct::COLUMN_ID} }}</div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Title</label>
                        <div class="col-sm-4 text">{{ $order_product->{App\OrderProduct::COLUMN_PRODUCT_PRICE_GROUP_TITLE} }}</div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Type</label>
                        <div class="col-sm-4 text">
                            {{ App\OrderProduct::$types[$order_product->{App\OrderProduct::COLUMN_TYPE}] }}
                            @if ($order_product->{App\OrderProduct::COLUMN_IS_PRIVATE})
                                <span class="label label-primary">Private</span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Total</label>
                        <div class="col-sm-4 text">HKD {{ number_format($order_product->{App\OrderProduct::COLUMN_TOTAL_AMOUNT}, 0) }}</div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Language</label>
                        <div class="col-sm-4 text">{{ $order_product->{App\OrderProduct::COLUMN_LANGUAGE} }}</div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Reference No</label>
                        <div class="col-sm-4">{!! Form::text('reference_no', null, ['class' => 'form-control']) !!}</div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Voucher No</label>
                        <div class="col-sm-4">{!! Form::text('voucher_no', null, ['class' => 'form-control']) !!}</div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">e-Ticket No</label>
                        <div class="col-sm-4">{!! Form::text('e_ticket_no', null, ['class' => 'form-control']) !!}</div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Live Ticket No</label>
                        <div class="col-sm-4">{!! Form::text('live_ticket_no', null, ['class' => 'form-control']) !!}</div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Last Update</label>
                        <div class="col-sm-4 text">{{ $order_product->{App\OrderProduct::UPDATED_AT} }}</div>
                    </div>


                </div>
            </div>

            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">Customer Information</h3>
                </div>

                <div class="box-body">

                    <div class="form-group @if ($errors->has('date')) has-error @endif">
                        <label class="col-sm-2 control-label">Tour Date</label>
                        <div class="col-sm-4">{!! Form::text('date', null, ['class' => 'form-control']) !!}</div>
                        @if ($errors->has('date'))<span class="help-block"><strong>{{ $errors->first('date') }}</strong></span> @endif
                    </div>

                    @if ($order_product->{\App\OrderProduct::COLUMN_TIME})
                        <div class="form-group @if ($errors->has('time')) has-error @endif">
                            <label class="col-sm-2 control-label">Travel Time</label>
                            <div class="col-sm-4">{!! Form::text('time', null, ['class' => 'form-control']) !!}</div>
                            @if ($errors->has('time'))<span class="help-block"><strong>{{ $errors->first('time') }}</strong></span> @endif
                        </div>
                    @endif

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Hotel</label>
                        <div class="col-sm-4 text">{{ $order_product->{App\OrderProduct::COLUMN_HOTEL} }}</div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Title</label>
                        <div class="col-sm-4 text">{{ $order_product->{App\OrderProduct::COLUMN_TITLE} }}</div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">First Name</label>
                        <div class="col-sm-4 text">{{ $order_product->{App\OrderProduct::COLUMN_FIRST_NAME} }}</div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Last Name</label>
                        <div class="col-sm-4 text">{{ $order_product->{App\OrderProduct::COLUMN_LAST_NAME} }}</div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Nationality</label>
                        <div class="col-sm-4 text">{{ $order_product->nationality->getNameOfLocale($order_product->{\App\OrderProduct::COLUMN_LANGUAGE}) }}</div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Email</label>
                        <div class="col-sm-4 text">{{ $order_product->{App\OrderProduct::COLUMN_EMAIL} }}</div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Tel</label>
                        <div class="col-sm-4 text">{{ $order_product->{App\OrderProduct::COLUMN_COUNTRY_CODE} }} {{ $order_product->{App\OrderProduct::COLUMN_TEL} }}</div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Remark</label>
                        <div class="col-sm-4 text">{{ $order_product->{App\OrderProduct::COLUMN_REMARK} }}</div>
                    </div>


                </div>
            </div>

            <div class="box box-warning">
                <div class="box-header with-border">
                    <h3 class="box-title">Price Table</h3>
                </div>

                <div class="box-body">

                    @foreach ($order_product->packages as $package)
                        <div class="form-group">
                            <label class="col-sm-2 control-label">{{ $package->{\App\OrderProductPackage::COLUMN_TITLE} }}</label>
                            <div class="col-sm-4 text">
                                {{ $package->{\App\OrderProductPackage::COLUMN_QUANTITY} }} x
                                @if ($package->{App\OrderProductPackage::COLUMN_UNIT_DISCOUNT} == 0)
                                    HKD {{ number_format($package->{App\OrderProductPackage::COLUMN_UNIT_FINAL_PRICE}, 0) }}
                                @else
                                    <span style="text-decoration: line-through; opacity: 0.5;">HKD {{ number_format($package->{App\OrderProductPackage::COLUMN_UNIT_ORIGINAL_PRICE}, 0) }}</span>
                                    HKD {{ number_format($package->{App\OrderProductPackage::COLUMN_UNIT_FINAL_PRICE}, 0) }}
                                @endif
                            </div>
                        </div>
                    @endforeach

                    @if ($order_product->{App\OrderProduct::COLUMN_PROMOCODE})
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Promo Code</label>
                            <div class="col-sm-4 text">{{ $order_product->{App\OrderProduct::COLUMN_PROMOCODE} }}</div>
                        </div>
                    @endif

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Sub-Total</label>
                        <div class="col-sm-4 text">HKD {{ number_format($order_product->{App\OrderProduct::COLUMN_SUB_TOTAL}, 0) }}</div>
                    </div>

                    @if ($order_product->{App\OrderProduct::COLUMN_UNIT_SERVICE_CHARGE} > 0)
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Service Charge</label>
                            <div class="col-sm-4 text">{{ $order_product->{App\OrderProduct::COLUMN_PACKAGE_QUANTITY} }} x HKD {{ number_format($order_product->{App\OrderProduct::COLUMN_UNIT_SERVICE_CHARGE}, 0) }}</div>
                        </div>
                    @endif

                    @if ($order_product->{App\OrderProduct::COLUMN_DISCOUNT} > 0)
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Discount</label>
                            <div class="col-sm-4 text">-HKD {{ number_format($order_product->{App\OrderProduct::COLUMN_DISCOUNT}, 0) }}</div>
                        </div>
                    @endif

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Total</label>
                        <div class="col-sm-4 text">HKD {{ number_format($order_product->{App\OrderProduct::COLUMN_TOTAL_AMOUNT}, 0) }}</div>
                    </div>

                </div>
            </div>

            @if ($order_product->disneyland_reserves->count() > 0)
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Disneyland Ticket Reservations</h3>
                </div>

                <div class="box-body">

                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>Id</th>
                            <th>Success?</th>
                            <th>Confirmation Letter</th>
                            <th>Reservation No.</th>
                            <th>Voucher No.</th>
                            <th>Reserved at</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach ($order_product->disneyland_reserves as $reserve)
                                <tr>
                                    <td>{{ $reserve->{\App\DisneylandReserve::COLUMN_ID} }}</td>
                                    <td>
                                        @if ($reserve->{\App\DisneylandReserve::COLUMN_IS_SUCCESS})
                                            <span class="label label-success">Success</span>
                                        @else
                                            <span class="label label-danger">Error</span>
                                            <button type="button" class="btn btn-default" data-toggle="modal" data-target="#modal_disneyland_reserve_{{ $reserve->id }}"><i class="fa fa-info"></i></button>

                                            <div class="modal fade" id="modal_disneyland_reserve_{{ $reserve->id }}" tabindex="-1" role="dialog">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                            <h4 class="modal-title">Error Message for Disneyland Reservation #{{ $reserve->id }}</h4>
                                                        </div>
                                                        <div class="modal-body">
                                                            {{ $reserve->{\App\DisneylandReserve::COLUMN_ERROR_MESSAGE} }}
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($reserve->{\App\DisneylandReserve::COLUMN_IS_SUCCESS})
                                            <a href="{{ url("admin/order/$order->id/$order_product->id/disneyland-letter/$reserve->id") }}" target="_blank" class="btn btn-default"><i class="fa fa-download"></i> Download</a>
                                        @endif
                                    </td>
                                    <td>{{ $reserve->{\App\DisneylandReserve::COLUMN_RESERVATION_NO} }}</td>
                                    <td>{{ $reserve->{\App\DisneylandReserve::COLUMN_VOUCHER_NO} }}</td>
                                    <td>{{ $reserve->{\App\DisneylandReserve::CREATED_AT} }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
            @endif

            @if ($order_product->oceanpark_reserves->count() > 0)
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">OceanPark Ticket Reservations</h3>
                </div>

                <div class="box-body">

                    <table id="oceanpark-reserve-table" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>Id</th>
                            <th>Success?</th>
                            <th>Confirmation Letter</th>
                            <th>Reservation No.</th>
                            <th>Voucher No.</th>
                            <th>Reserved at</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach ($order_product->oceanpark_reserves as $reserve)
                                <tr>
                                    <td>{{ $reserve->{\App\OceanParkReserve::COLUMN_ID} }}</td>
                                    <td>
                                        @if ($reserve->{\App\OceanParkReserve::COLUMN_IS_SUCCESS})
                                            <span class="label label-success">Success</span>
                                        @else
                                            <span class="label label-danger">Error</span>
                                            <button type="button" class="btn btn-default" data-toggle="modal" data-target="#modal_oceanpark_reserve_{{ $reserve->id }}"><i class="fa fa-info"></i></button>

                                            <div class="modal fade" id="modal_oceanpark_reserve_{{ $reserve->id }}" tabindex="-1" role="dialog">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                            <h4 class="modal-title">Error Message for OceanPark Reservation #{{ $reserve->id }}</h4>
                                                        </div>
                                                        <div class="modal-body">
                                                            {{ $reserve->{\App\OceanParkReserve::COLUMN_ERROR_MESSAGE} }}
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($reserve->{\App\OceanParkReserve::COLUMN_IS_SUCCESS})
                                            <a href="{{ url("admin/order/$order->id/$order_product->id/oceanpark-letter/$reserve->id") }}" target="_blank" class="btn btn-default"><i class="fa fa-download"></i> Download</a>
                                        @endif
                                    </td>
                                    <td>{{ $reserve->{\App\OceanParkReserve::COLUMN_RESERVATION_NO} }}</td>
                                    <td>{{ $reserve->{\App\OceanParkReserve::COLUMN_VOUCHER_NO} }}</td>
                                    <td>{{ $reserve->{\App\OceanParkReserve::CREATED_AT} }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
            @endif

            @if ($order_product->turbojet_reserve)
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Turbojet Ticket Reservations</h3>
                </div>

                <div class="box-body">

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Id</label>
                        <div class="col-sm-4 text">{{ $order_product->turbojet_reserve->{\App\TurbojetReserve::COLUMN_ID} }}</div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Success?</label>
                        <div class="col-sm-4 text">
                            @if ($order_product->turbojet_reserve->{\App\TurbojetReserve::COLUMN_IS_SUCCESS})
                                <span class="label label-success">Success</span>
                            @else
                                <span class="label label-danger">Error</span>
                                <button type="button" class="btn btn-default" data-toggle="modal" data-target="#modal_turbojet_reserve_{{ $order_product->turbojet_reserve->id }}"><i class="fa fa-info"></i></button>

                                <div class="modal fade" id="modal_turbojet_reserve_{{ $order_product->turbojet_reserve->id }}" tabindex="-1" role="dialog">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                <h4 class="modal-title">Error Message for Turbojet Reservation #{{ $order_product->turbojet_reserve->id }}</h4>
                                            </div>
                                            <div class="modal-body">
                                                {{ $order_product->turbojet_reserve->{\App\TurbojetReserve::COLUMN_ERROR_MESSAGE} }}
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Confirmation Letter</label>
                        <div class="col-sm-4 text">
                            @if ($order_product->turbojet_reserve->{\App\TurbojetReserve::COLUMN_IS_SUCCESS})
                                <a href="{{ url("admin/order/$order->id/$order_product->id/turbojet-letter") . '/' . $order_product->turbojet_reserve->id }}" target="_blank" class="btn btn-default"><i class="fa fa-download"></i> Download</a>
                            @endif
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Transaction No.</label>
                        <div class="col-sm-4 text">{{ $order_product->turbojet_reserve->{\App\TurbojetReserve::COLUMN_TRANSACTION_ID} }}</div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Total Price</label>
                        <div class="col-sm-4 text">HKD {{ $order_product->turbojet_reserve->{\App\TurbojetReserve::COLUMN_TOTAL_PRICE} }}</div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Reserved at</label>
                        <div class="col-sm-4 text">{{ $order_product->turbojet_reserve->{\App\TurbojetReserve::CREATED_AT} }}</div>
                    </div>

                    <div style="margin: 40px 0 20px 0; padding-bottom: 5px; font-size: 18px; border-bottom: 1px solid #f4f4f4">Tickets</div>
                    <table id="turbojet-reserve-table" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>Departure Time</th>
                            <th>Route</th>
                            <th>Reservation No.</th>
                            <th>Seat Class</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach ($order_product->turbojet_reserve->getReservationTable() as $ticket)
                            <tr>
                                <td>{{ $ticket['time'] }}</td>
                                <td>{{ $ticket['route'] }}</td>
                                <td>{{ $ticket['reservation-no'] }}</td>
                                <td>{{ $ticket['class'] }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
            @endif

            <div class="box box-danger">
                <div class="box-header with-border">
                    <h3 class="box-title">Change Histories</h3>
                </div>

                <div class="box-body">

                    <table id="change-history-table" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>Old Values</th>
                            <th>New Values</th>
                            <th>Change By</th>
                            <th>Change At</th>
                            <th>Remark</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($order_product->audits()->orderBy('created_at', 'desc')->get() as $audit)
                            <tr>
                                <td>
                                    @foreach (json_decode($audit->old_values) as $key => $value)
                                        @if ($key == 'confirmation')
                                            <div>{{ $key }} : @if (strlen($value) > 0)<button class="btn btn-default btn-sm confirmation-preview" type="button" data-confirmation="{{ base64_encode($value) }}"><i class="fa fa-info"></i></button>@endif</div>
                                        @else
                                            <div>{{ $key . ' : ' . $value }}</div>
                                        @endif
                                    @endforeach
                                </td>
                                <td>
                                    @foreach (json_decode($audit->new_values) as $key => $value)
                                        @if ($key == 'confirmation')
                                            <div>{{ $key }} : @if (strlen($value) > 0)<button class="btn btn-default btn-sm confirmation-preview" type="button" data-confirmation="{{ base64_encode($value) }}"><i class="fa fa-info"></i></button>@endif</div>
                                        @else
                                            <div>{{ $key . ' : ' . $value }}</div>
                                        @endif
                                    @endforeach
                                </td>
                                <td>
                                    @if ($audit->url == "console")System
                                    @else
                                        {{ $audit->user ? $audit->user->name : "" }}
                                    @endif
                                </td>
                                <td>{{ $audit->created_at }}</td>
                                <td>
                                    {{ $audit->remark }}
                                    <button class="btn btn-default audit-remark" type="button" data-remark="{!! base64_encode($audit->remark) !!} " data-id="{{ $audit->id }}"><i class="fa fa-pencil"></i></button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </section>

        {!! Form::close() !!}

    </div>

    <!-- Audit Remark Modal -->
    <div class="modal fade" id="audit-remark-modal" tabindex="-1" role="dialog" aria-labelledby="audit-remark-title">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                {!! Form::open(['url' => url("admin/order/$order->id/$order_product->id/audit-remark"), 'method' => 'put']) !!}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="audit-remark-title">Edit Remark</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="audit_id" value="">
                    <div class="form-group">
                        <label>Remark</label>
                        <textarea class="form-control" name="remark"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>

    <!-- Confirmation Preview Modal -->
    <div class="modal fade" id="confirmation-modal" tabindex="-1" role="dialog" aria-labelledby=confirmation-title">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="confirmation-title">Confirmation Detail</h4>
                </div>
                <div class="modal-body"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection


@section("jquery")
    <script type="text/javascript" src="{{ URL::asset('website/assets/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js')}}"></script>
    <script type="text/javascript">

        function b64DecodeUnicode(str) {
            return decodeURIComponent(atob(str).split('').map(function(c) {
                return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
            }).join(''));
        }

        $(function (){
            $("input[name=date]").datetimepicker({
                useCurrent: false,
                format: 'YYYY-MM-DD',
            });

            $('#change-history-table, #oceanpark-reserve-table, #turbojet-reserve-table').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": false,
                "info": true,
                "autoWidth": true
            });

            $(".audit-remark").click(function (){
                $("#audit-remark-modal textarea[name=remark]").val(b64DecodeUnicode($(this).attr("data-remark")));
                $("#audit-remark-modal input[name=audit_id]").val($(this).attr("data-id"));
                $("#audit-remark-modal").modal('show');
            });

            $(".confirmation-preview").click(function (){
                $("#confirmation-modal .modal-body").html(b64DecodeUnicode($(this).attr("data-confirmation")));
                $("#confirmation-modal").modal('show');
            });
        });
    </script>
@endsection