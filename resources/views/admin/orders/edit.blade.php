@extends('admin.layouts.app')


@section('content')

    <style>
        .form-group .text {
            margin-top: 7px;
        }

        .message-attachments {
            margin-top: 20px;
        }
    </style>

    <div class="content-wrapper" style="min-height: 946px;">
        <section class="content-header">
            <h1>
                Order #{{ $order->{App\Order::COLUMN_ID} }}
                <small>Edit</small>
            </h1>
            <ol class="breadcrumb">
                <li class="active">edit</li>
            </ol>
        </section>


        <section class="content">

            @if (Session::has('success'))
                <div class="alert alert-success">
                    <strong>Success!</strong> {{ Session::get('success') }}
                </div>
            @endif

            {!! Form::model($order, ['url' => url("admin/order/$order->id"), 'method' => 'put', 'class' => 'form-horizontal']) !!}
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">Order Detail</h3>
                </div>

                <div class="box-body">

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Order No.</label>
                        <div class="col-sm-4 text">{{ $order->{App\Order::COLUMN_ID} }}</div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Payment Method</label>
                        <div class="col-sm-4 text">{{ App\Order::$payment_methods[$order->{App\Order::COLUMN_PAYMENT_METHOD}] }}</div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Payment Reference</label>
                        <div class="col-sm-4 text">{{ $order->{App\Order::COLUMN_PAYMENT_REFERENCE} }}</div>
                    </div>

                    @if ($order->{App\Order::COLUMN_ACCOUNT_NO})
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Payment Account</label>
                        <div class="col-sm-4 text">{{ $order->{App\Order::COLUMN_ACCOUNT_NO} }}</div>
                    </div>
                    @endif

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Total Amount</label>
                        <div class="col-sm-4 text">HKD {{ number_format($order->{App\Order::COLUMN_TOTAL_AMOUNT}) }}</div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Language</label>
                        <div class="col-sm-4 text">{{ $order->{App\Order::COLUMN_LANGUAGE} }}</div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Order Date</label>
                        <div class="col-sm-4 text">{{ $order->{App\Order::CREATED_AT} }}</div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Completed</label>
                        <div class="col-sm-4 text">{{ $order->getCompletedCount() }}</div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Remark</label>
                        <div class="col-sm-4">{!! Form::textarea('remark', null, ['class' => 'form-control']) !!}</div>
                    </div>

                </div>

                <div class="box-footer">
                    <button class="btn btn-info pull-right" type="submit"><i class="fa fa-floppy-o"></i> Save</button>
                </div>
            </div>
            {!! Form::close() !!}
        </section>

        <section class="content-header">
            <h1>
                Order Products
            </h1>
            <div style="position: absolute; top: 15px; right: 15px;">
                <div class="checkbox" style="float: left; margin-right: 20px;">
                    <label>
                        <input type="checkbox" id="cb-select-all"> Select All Confirmations
                    </label>
                </div>

                <button id="btn-send-confirmation" class="btn btn-success"><i class="fa fa-envelope-o"></i> Send Confirmation Email</button>
            </div>
        </section>


        <section class="content">
            @foreach ($order->products as $order_product)
                <div class="box box-info" data-order-product-id="{{ $order_product->{App\OrderProduct::COLUMN_ID} }}">
                    <div class="box-header with-border">
                        <h3 class="box-title">
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

                            <span class="product-title">{{ $order_product->{App\OrderProduct::COLUMN_PRODUCT_PRICE_GROUP_TITLE} }}</span>
                        </h3>
                        <div class="pull-right box-tools">
                            <a href="{{ url("admin/order/$order->id/$order_product->id") }}" class="btn btn-default btn-sm" data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-pencil"></i> Edit</a>
                            <a href="{{ url("admin/order/$order->id/$order_product->id/confirm") }}" class="btn btn-info btn-sm" data-toggle="tooltip" data-original-title="Confirm"><i class="fa fa-check"></i> Confirm</a>
                            <button type="button" class="btn btn-success btn-sm product-complete-button" data-toggle="tooltip" data-original-title="Confirm"><i class="fa fa-thumbs-o-up"></i> Complete</button>
                            <button type="button" class="btn btn-danger btn-sm product-cancel-button" data-toggle="tooltip" data-original-title="Confirm"><i class="fa fa-ban"></i> Cancel</button>
                        </div>
                    </div>

                    <div class="box-body">
                        <div class="row">
                            <!-- Order Product Summary -->
                            <div class="col-md-6">
                                {!! Form::model($order_product, ['url' => '', 'method' => 'put', 'class' => 'form-horizontal']) !!}

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Order Product No.</label>
                                    <div class="col-sm-8 text">{{ $order_product->{App\OrderProduct::COLUMN_ID} }}</div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Type</label>
                                    <div class="col-sm-8 text">
                                        {{ App\OrderProduct::$types[$order_product->{App\OrderProduct::COLUMN_TYPE}] }}
                                        @if ($order_product->{App\OrderProduct::COLUMN_IS_PRIVATE})
                                            <span class="label label-primary">Private</span>
                                        @endif
                                    </div>
                                </div>

                                @if ($order_product->{App\OrderProduct::COLUMN_REFERENCE_NO})
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Reference No</label>
                                        <div class="col-sm-8 text">{{ $order_product->{App\OrderProduct::COLUMN_REFERENCE_NO} }}</div>
                                    </div>
                                @endif

                                @if ($order_product->{App\OrderProduct::COLUMN_VOUCHER_NO})
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Voucher No</label>
                                        <div class="col-sm-8 text">{{ $order_product->{App\OrderProduct::COLUMN_VOUCHER_NO} }}</div>
                                    </div>
                                @endif

                                @if ($order_product->{App\OrderProduct::COLUMN_E_TICKET_NO})
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">e-Ticket No</label>
                                        <div class="col-sm-8 text">{{ $order_product->{App\OrderProduct::COLUMN_E_TICKET_NO} }}</div>
                                    </div>
                                @endif

                                @if ($order_product->{App\OrderProduct::COLUMN_LIVE_TICKET_NO})
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Live Ticket No</label>
                                        <div class="col-sm-8 text">{{ $order_product->{App\OrderProduct::COLUMN_LIVE_TICKET_NO} }}</div>
                                    </div>
                                @endif

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Tour Date</label>
                                    <div class="col-sm-8 text">{{ $order_product->{App\OrderProduct::COLUMN_DATE}->format('Y-m-d') }}</div>
                                </div>

                                @if ($order_product->{\App\OrderProduct::COLUMN_TIME})
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Travel Time</label>
                                        <div class="col-sm-8 text">{{ $order_product->{App\OrderProduct::COLUMN_TIME} }}</div>
                                    </div>
                                @endif

                                @foreach ($order_product->packages as $package)
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">{{ $package->{\App\OrderProductPackage::COLUMN_TITLE} }}</label>
                                        <div class="col-sm-8 text">
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
                                        <label class="col-sm-4 control-label">Promo Code</label>
                                        <div class="col-sm-8 text">{{ $order_product->{App\OrderProduct::COLUMN_PROMOCODE} }}</div>
                                    </div>
                                @endif

                                @if ($order_product->{App\OrderProduct::COLUMN_UNIT_SERVICE_CHARGE} > 0)
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Service Charge</label>
                                        <div class="col-sm-8 text">{{ $order_product->{App\OrderProduct::COLUMN_PACKAGE_QUANTITY} }} x HKD {{ number_format($order_product->{App\OrderProduct::COLUMN_UNIT_SERVICE_CHARGE}, 0) }}</div>
                                    </div>
                                @endif

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Total</label>
                                    <div class="col-sm-8 text">HKD {{ number_format($order_product->{App\OrderProduct::COLUMN_TOTAL_AMOUNT}, 0) }}</div>
                                </div>

                                @if ($order_product->disneyland_reserves->count() > 0)
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Disneyland Ticket</label>
                                        <div class="col-sm-8 text">{{ $order_product->disneyland_reserves->count() }} Reservations</div>
                                    </div>
                                @endif

                                @if ($order_product->oceanpark_reserves->count() > 0)
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">OceanPark Ticket</label>
                                        <div class="col-sm-8 text">{{ $order_product->oceanpark_reserves->count() }} Reservations</div>
                                    </div>
                                @endif

                                @if ($order_product->turbojet_reserve)
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Turbojet Ticket</label>
                                        <div class="col-sm-8 text">1 Reservations</div>
                                    </div>
                                @endif
                                {!! Form::close() !!}
                            </div>

                            <!-- Confirmation Preview -->
                            <div class="col-md-6">
                                @if ($order_product->{\App\OrderProduct::COLUMN_CONFIRMATION})
                                    <div class="bs-callout bs-callout-info">
                                        <h4><i class="fa fa-envelope-o"></i> Confirmation</h4>
                                        @if ($order_product->{App\OrderProduct::COLUMN_STATUS} != App\OrderProduct::STATUS_CONFIRM_DRAFTED && $order_product->{App\OrderProduct::COLUMN_CONFIRMATION_SENT_AT})
                                            <div style="margin-bottom: 10px">
                                                <i class="fa fa-clock-o"></i> Sent at {{ $order_product->{App\OrderProduct::COLUMN_CONFIRMATION_SENT_AT} }}
                                            </div>
                                        @endif

                                        <div style="position: absolute; top: 13px; right: 20px;" class="checkbox">
                                            <label>
                                                <input type="checkbox" class="cb-send"> Send This Confirmation
                                            </label>
                                        </div>

                                        <div class="confirmation-preview">
                                            {!! $order_product->{\App\OrderProduct::COLUMN_CONFIRMATION} !!}
                                        </div>

                                        @if ($order_product->attachments->count() > 0)
                                            <h4 style="margin-top: 30px"><i class="fa fa-paperclip"></i> Attachments</h4>
                                            <div class="attachment-preview">
                                            @foreach ($order_product->attachments as $attachment)
                                                <div>
                                                    <a href="{{ url("admin/order/$order->id/$order_product->id/attachment/$attachment->id") }}" target="_blank">
                                                        <i class="fa fa-file-o"></i> {{ $attachment->{\App\OrderProductAttachment::COLUMN_NAME} }}
                                                    </a>
                                                </div>
                                            @endforeach
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>
            @endforeach
        </section>

        <section class="content-header">
            <h1>
                Order Confirmations
            </h1>
        </section>

        <section class="content">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">Order Confirmation Histories</h3>
                </div>
                <div class="box-body">
                    <table id="order-confirmations-table" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Content</th>
                            <th>Order Products</th>
                            <th>Attachments</th>
                            <th>Sent By</th>
                            <th>Sent At</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($order->confirmations()->orderBy('created_at', 'desc')->get() as $confirmation)
                            <tr>
                                <td>{{ $confirmation->{\App\OrderConfirmation::COLUMN_ID} }}</td>
                                <td><a href="{{ url("admin/order/$order->id/confirmation/$confirmation->id") }}" target="_blank" class="btn btn-default"><i class="fa fa-file-o"></i> Show</a> </td>
                                <td>
                                    @foreach ($confirmation->{\App\OrderConfirmation::COLUMN_ORDER_PRODUCT_IDS} as $value)
                                        <div>{{ $value }}</div>
                                    @endforeach
                                </td>
                                <td>
                                    @foreach ($confirmation->getAttachments() as $attachment)
                                        <a href="{{ url("admin/order/$order->id/$attachment->order_product_id/attachment/$attachment->id") }}" target="_blank">
                                            <i class="fa fa-file-o"></i> {{ $attachment->{\App\OrderProductAttachment::COLUMN_NAME} }}
                                        </a>
                                    @endforeach
                                </td>
                                <td>{{ $confirmation->user->name }}</td>
                                <td>{{ $confirmation->created_at }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <section class="content-header">
            <h1>
                Order Messages
            </h1>
        </section>

        <section class="content">
            <div class="box box-warning">
                <div class="box-header with-border">
                    <h3 class="box-title">Order Messages</h3>
                    <div class="pull-right box-tools">
                        <a href="{{ url("admin/order/$order->id/reply") }}" class="btn btn-primary btn-sm" data-toggle="tooltip" data-original-title="Reply"><i class="fa fa-mail-reply-all"></i> Reply</a>
                    </div>
                </div>
                <div class="box-body">
                    @foreach ($order->messages()->orderBy('created_at', 'desc')->get() as $message)
                        <div class="message bs-callout {{ $message->{\App\OrderMessage::COLUMN_TYPE} == App\OrderMessage::TYPE_ENQUIRE ? "bs-callout-info" : "bs-callout-success" }}">
                            @if ($message->{\App\OrderMessage::COLUMN_TYPE} == App\OrderMessage::TYPE_ENQUIRE)
                                <h4>Enquiry from Guest</h4>
                            @else
                                <h4>Reply from {{ $message->reply_user ? $message->reply_user->name : "Grayline" }}</h4>
                            @endif
                            <div style="margin-bottom: 10px">
                                <i class="fa fa-clock-o"></i> {{ $message->created_at }}
                            </div>

                            <div class="message-body">
                                {!! nl2br($message->{\App\OrderMessage::COLUMN_MESSAGE}) !!}
                            </div>

                            @if ($message->attachments->count() > 0)
                                <div class="message-attachments">
                                    @foreach ($message->attachments as $attachment)
                                        <a href="{{ url("admin/order/$order->id/attachment/$attachment->id") }}" target="_blank">
                                            <i class="fa fa-file-o"></i> {{ $attachment->{\App\OrderMessageAttachment::COLUMN_NAME} }}
                                        </a>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

    </div>

    <!-- Confirm Modal -->
    <div class="modal fade" id="send-confirm-modal" tabindex="-1" role="dialog" aria-labelledby="send-confirm-modal-title">
        {!! Form::open(['url' => url("admin/order/$order->id/send-confirm"), 'method' => 'put']) !!}
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="send-confirm-modal-title">Send Order Product Confirmation</h4>
                </div>
                <div class="modal-body">
                    Are you sure to send confirmation letter for below order products?
                    <input type="hidden" name="order-product-ids" value="" />

                    <div class="confirmation-preview">

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-envelope-o"></i> Send</button>
                </div>
            </div>
        </div>
        {!! Form::close() !!}
    </div>

    <!-- Complete Modal -->
    <div class="modal fade" id="complete-modal" tabindex="-1" role="dialog" aria-labelledby="complete-modal-title">
        {!! Form::open(['url' => url("admin/order/$order->id/complete"), 'method' => 'put']) !!}
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="complete-modal-title">Complete Order Product</h4>
                </div>
                <div class="modal-body">
                    Are you sure to complete below Order Product?
                    <input type="hidden" name="order-product-id" value="" />

                    <div class="bs-callout bs-callout-success">
                        <h4 class="order-product-title"></h4>
                        <div>
                            <span style="margin-right: 10px;">Order Product No.</span>
                            <span class="order-product-id"></span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Yes, Complete Order Product</button>
                </div>
            </div>
        </div>
        {!! Form::close() !!}
    </div>

    <!-- Cancel Modal -->
    <div class="modal fade" id="cancel-modal" tabindex="-1" role="dialog" aria-labelledby="cancel-modal-title">
        {!! Form::open(['url' => url("admin/order/$order->id/cancel"), 'method' => 'put']) !!}
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="cancel-modal-title">Cancel Order Product</h4>
                </div>
                <div class="modal-body">
                    Are you sure to cancel below Order Product?
                    <input type="hidden" name="order-product-id" value="" />

                    <div class="bs-callout bs-callout-danger">
                        <h4 class="order-product-title"></h4>
                        <div>
                            <span style="margin-right: 10px;">Order Product No.</span>
                            <span class="order-product-id"></span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Yes, Cancel Order Product</button>
                </div>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
@endsection

@section("jquery")
    <script type="text/javascript">
        $(function (){
            $(".product-complete-button").click(function (){
                var order_product_id = $(this).closest(".box").attr("data-order-product-id");
                var order_product_title = $(this).closest(".box").find(".product-title").text();
                $("#complete-modal input[name=order-product-id]").val(order_product_id);
                $("#complete-modal .order-product-id").text(order_product_id);
                $("#complete-modal .order-product-title").text(order_product_title);
                $("#complete-modal").modal('show');
            });

            $(".product-cancel-button").click(function (){
                var order_product_id = $(this).closest(".box").attr("data-order-product-id");
                var order_product_title = $(this).closest(".box").find(".product-title").text();
                $("#cancel-modal input[name=order-product-id]").val(order_product_id);
                $("#cancel-modal .order-product-id").text(order_product_id);
                $("#cancel-modal .order-product-title").text(order_product_title);
                $("#cancel-modal").modal('show');
            });

            $("#btn-send-confirmation").click(function (){
                // make sure there something to send
                if ($(".cb-send:checked").length == 0)
                    return;

                $("#send-confirm-modal .confirmation-preview").empty();

                var ids = [];
                $(".cb-send:checked").each(function (){
                    var id = $(this).closest(".box").attr('data-order-product-id');
                    ids.push(id);

                    var preview = "<div class='bs-callout bs-callout-info'><h4>" + id + "</h4>" +
                        $(this).closest(".bs-callout").find(".confirmation-preview").html();

                    if ($(this).closest(".bs-callout").find(".attachment-preview").length > 0)
                        preview += "<div style='margin-top: 20px'>" + $(this).closest(".bs-callout").find(".attachment-preview").html() + "</div>";

                    preview += "</div>";

                    $(preview).appendTo("#send-confirm-modal .confirmation-preview")
                });

                $("#send-confirm-modal input[name=order-product-ids]").val(JSON.stringify(ids));
                $("#send-confirm-modal").modal("show");
            });

            var reload_send_button = function (){
                var checked = $(".cb-send:checked").length;
                var total = $(".cb-send").length;

                var button_text = '<i class="fa fa-envelope-o"></i> Send Confirmation Email (' + checked + '/' + total + ')';

                $("#btn-send-confirmation").html(button_text);
                $("#btn-send-confirmation").prop("disabled", checked == 0);
            };

            reload_send_button();

            $(".cb-send").change(function (){
                reload_send_button();

                $("#cb-select-all").prop('checked', ($(".cb-send:checked").length == $(".cb-send").length));
            });

            $("#cb-select-all").change(function (){
                $(".cb-send").prop('checked', $(this).prop('checked'));
                reload_send_button();
            });


            $('#order-confirmations-table').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": false,
                "info": true,
                "autoWidth": true
            });
        });
    </script>
@endsection

