@extends('admin.layouts.app')


@section('content')
    <style>
        @import url("{{ URL::asset('assets/plugins/summernote/summernote.css')}}");

        .form-group .text {
            margin-top: 7px;
        }

        .attachment-preview {
            position: relative;
        }

        .attachment-preview h4 {
            margin-right: 30px;
        }

        .attachment-preview .delete {
            color: red;
            position: absolute;
            top: 25px;
            right: 25px;
            cursor: pointer;
            padding: 10px;
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
                <li class="active">confirm</li>
            </ol>
        </section>


        {!! Form::model($order_product, ['id' => 'confirm-form', 'url' => url("admin/order/$order->id/$order_product->id/confirm"), 'method' => 'put', 'class' => 'form-horizontal', 'files' => true]) !!}

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
                    <h3 class="box-title">Confirm Order Product</h3>
                </div>

                <div class="box-body">

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Order Product No.</label>
                        <div class="col-sm-4 text">{{ $order_product->{App\Order::COLUMN_ID} }}</div>
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
                        <label class="col-sm-2 control-label">Product Title</label>
                        <div class="col-sm-4 text">{{ $order_product->{App\OrderProduct::COLUMN_PRODUCT_TITLE} }}</div>
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
                        <label class="col-sm-2 control-label">Confirm Template</label>
                        <div class="col-sm-4">
                            <select id="select-template" class="form-control">
                                <option value="tour-confirm">Tour booking - Confirmation with voucher</option>
                                <option value="ticket-confirm-api">e-Ticket - Confirmation with voucher (API)</option>
                                <option value="ticket-confirm">e-Ticket - Confirmation with voucher</option>
                                <option value="ticket-confirm-without-ticket">e-Ticket - Confirm but without ticket</option>
                                <option value="reject">Reject with suggestion</option>
                                <option value="refund">Refund</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="col-sm-1">
                            <button id="btn-load" class="btn btn-default" type="button">Load</button>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Confirmation</label>
                        <div class="col-sm-10">{!! Form::textarea('confirmation', null, ['class' => 'form-control']) !!}</div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Attachments</label>
                        <div class="col-sm-5">
                            <div id="btn-upload-container"></div>
                            <div id="attachment-preview-container">
                                @foreach ($order_product->attachments as $attachment)
                                    <div class="bs-callout bs-callout-info attachment-preview" data-attachment-id="{{ $attachment->id }}">
                                        <a href="{{ url("admin/order/$order->id/$order_product->id/attachment/$attachment->id") }}" target="_blank">
                                            <h4>
                                            {{ $attachment->{\App\OrderProductAttachment::COLUMN_NAME} }}
                                            </h4>
                                        </a>
                                        <div class="delete"><i class='fa fa-trash-o'></i></div>
                                        <span class="timestamp"><i class="fa fa-clock-o"></i> {{ $attachment->{\App\OrderProductAttachment::CREATED_AT} }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </section>

        {!! Form::close() !!}
    </div>

    <!-- Load Template Modal -->
    <div class="modal fade" id="load-template-modal" tabindex="-1" role="dialog" aria-labelledby="load-template-modal-title">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="load-template-modal-title">Load Template Confirmation</h4>
                </div>
                <div class="modal-body">
                    Are you sure to load template <span id="template-name"></span>?
                    <br />Current confirmation text will be overwritten by the template.
                    <div class="bs-callout bs-callout-info">
                        <h4>Template Preview</h4>
                        <div id="template-preview">

                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button id="btn-confirm-load" type="button" class="btn btn-primary" data-dismiss="modal">Load Template</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section("jquery")
    <script src="{{ URL::asset('assets/plugins/summernote/summernote.min.js')}}"></script>

    <script type="text/javascript">

        <?php
            $container_style = "padding: 10px 20px 0 20px;";
            $message_style = "background-color: #365F91; text-align: center; color: white; padding: 10px 20px; margin-top: 10px;";
        ?>

        @if ($order_product->{App\OrderProduct::COLUMN_LANGUAGE} == "en")
        var template = {
            "tour-confirm" : "<div style='{{ $container_style }}'>" +
                            "    <div>The booking has been confirmed.  Details are as follows:</div>" +
                            "    <table width='100%'>" +
                            "        <tr>" +
                            "            <td width='130px'>Pick-up Time:</td>" +
                            "            <td>09:45am</td>" +
                            "        </tr>" +
                            "        <tr>" +
                            "            <td>Pick-up Location:</td>" +
                            "            <td>Lobby of City Garden Hotel<br />9 City Garden Road, North Point, Hong Kong</td>" +
                            "        </tr>" +
                            "        <tr>" +
                            "            <td>Reminder:</td>" +
                            "            <td>Please take a 25-minute taxi ride to pick up location</td>" +
                            "        </tr>" +
                            "    </table>" +
                            "</div>" +
                            "<div style='{{ $message_style }}'>Please print out the attached confirmation letter for tour guide collection.</div>",

            "ticket-confirm-api" : "<div style='{{ $message_style }}'>Your E-ticket &lt;{{ $order_product->{App\OrderProduct::COLUMN_E_TICKET_NO} ?: "e-ticket no." }}&gt; has already been sent to you in another email at &lt;time&gt;.</div>",

            "ticket-confirm" : "<div style='{{ $container_style }}'>Your E-ticket &lt;{{ $order_product->{App\OrderProduct::COLUMN_E_TICKET_NO} ?: "e-ticket no." }}&gt; is attached in this email.</div><br />" +
                                "<div style='{{ $message_style }}'>Please find the attached electronic ticket for redemption.<br />Details of redemption can be found inside attachment.</div>",

            "ticket-confirm-without-ticket" : "<div style='{{ $message_style }}'>Thank you for your booking.  However, tickets are valid for 30 days from date of issue.  We will issue and email the tickets to you &lt;30&gt; days before your departure.</div>",

            "reject" : "<div style='{{ $message_style }}'>Thank you for your booking.  I am sorry to inform you that &lt;{{ $order_product->{App\OrderProduct::COLUMN_PRODUCT_TITLE} }}&gt; will not be operated on &lt;{{ $order_product->{App\OrderProduct::COLUMN_DATE}->format('Y-m-d') }}&gt;  due to &lt;reason&gt;.  Would you like to change &lt;something&gt; to &lt;something&gt; ?</div>",

            "refund" : "<div style='{{ $message_style }}'>Regarding the tour charge refund of HK$&lt;{{ number_format($order_product->{App\OrderProduct::COLUMN_TOTAL_AMOUNT}, 0) }}&gt; on your credit card, we have already submitted the refund transaction to bank on &lt;date&gt;.<br />Thank you for choosing Gray Line tours as your travel agent. We look forward to serving you in the near future.</div>",

            "other" : "<div style='{{ $container_style }}'>Free-style confirmation body</div><br />" +
                      "<div style='{{ $message_style }}'>&nbsp;</div>",
        };
        @elseif ($order_product->{App\OrderProduct::COLUMN_LANGUAGE} == "zh-hk")
        var template = {
                "tour-confirm" : "<div style='{{ $container_style }}'>" +
                                "    <div>已確認預訂。 詳情如下:</div>" +
                                "    <table>" +
                                "        <tr>" +
                                "            <td width='130px'>集合時間:</td>" +
                                "            <td>09:45am</td>" +
                                "        </tr>" +
                                "        <tr>" +
                                "            <td>集合地點:</td>" +
                                "            <td>Lobby of City Garden Hotel<br />9 City Garden Road, North Point, Hong Kong</td>" +
                                "        </tr>" +
                                "        <tr>" +
                                "            <td>備註:</td>" +
                                "            <td>Please take a 25-minute taxi ride to pick up location</td>" +
                                "        </tr>" +
                                "    </table>" +
                                "</div>" +
                                "<div style='{{ $message_style }}'>請打印附件中的旅行團憑證並在出發當日向導遊出示。</div>",

                "ticket-confirm-api" : "<div style='{{ $message_style }}'>您的電子票 &lt;{{ $order_product->{App\OrderProduct::COLUMN_E_TICKET_NO} ?: "e-ticket no." }}&gt; 已於 &lt;time&gt; 發送給您。</div>",

                "ticket-confirm" : "<div style='{{ $container_style }}'>請下載附件的電子門票 &lt;{{ $order_product->{App\OrderProduct::COLUMN_E_TICKET_NO} ?: "電子票號" }}&gt;。</div><br />" +
                                    "<div style='{{ $message_style }}'>請持電子換票證兌換入場卷。<br />換領詳情於電子換票證列明。</div>",

                "ticket-confirm-without-ticket" : "<div style='{{ $message_style }}'>感謝您的預訂！車票只於發票當日起30日內有效。我們將在您出發前的 &lt;30&gt; 天電郵發送門票給您。</div>",

                "reject" : "<div style='{{ $message_style }}'>感謝您的預訂！很抱歉 &lt;{{ $order_product->{App\OrderProduct::COLUMN_PRODUCT_TITLE} }}&gt; 於 &lt;{{ $order_product->{App\OrderProduct::COLUMN_DATE}->format('Y-m-d') }}&gt; 未能成團。 您要將 &lt;something&gt; 更改為 &lt;something&gt; 嗎？</div>",

                "refund" : "<div style='{{ $message_style }}'>我們已於 &lt;date&gt; 提交退款港幣總額 HK$&lt;{{ number_format($order_product->{App\OrderProduct::COLUMN_TOTAL_AMOUNT}, 0) }}&gt; 給銀行。<br />感謝您選擇錦倫旅運作為您您旅途的夥伴，期待再為您提供服務。</div>",

                "other" : "<div style='{{ $container_style }}'>Free-style confirmation body</div><br />" +
                            "<div style='{{ $message_style }}'>&nbsp;</div>",

            };
        @elseif ($order_product->{App\OrderProduct::COLUMN_LANGUAGE} == "zh-cn")
        var template = {
                "tour-confirm" : "<div style='{{ $container_style }}'>" +
                "    <div>已确认预订。详情如下:</div>" +
                "    <table>" +
                "        <tr>" +
                "            <td width='130px'>集合时间:</td>" +
                "            <td>09:45am</td>" +
                "        </tr>" +
                "        <tr>" +
                "            <td>集合地点:</td>" +
                "            <td>Lobby of City Garden Hotel<br />9 City Garden Road, North Point, Hong Kong</td>" +
                "        </tr>" +
                "        <tr>" +
                "            <td>备注:</td>" +
                "            <td>Please take a 25-minute taxi ride to pick up location</td>" +
                "        </tr>" +
                "    </table>" +
                "</div>" +
                "<div style='{{ $message_style }}'>请打印附件中的旅行团凭证并在出发当日向导游出示。</div>",

                "ticket-confirm-api" : "<div style='{{ $message_style }}'>您的电子票 &lt;{{ $order_product->{App\OrderProduct::COLUMN_E_TICKET_NO} ?: "e-ticket no." }}&gt; 已于 &lt;time&gt; 发送给您。</div>",

                "ticket-confirm" : "<div style='{{ $container_style }}'>请下载附件的电子门票 &lt;{{ $order_product->{App\OrderProduct::COLUMN_E_TICKET_NO} ?: "电子票号" }}&gt;。</div><br />" +
                "<div style='{{ $message_style }}'>请持电子换票证兑换入场卷。<br />换领详情于电子换票证列明。</div>",

                "ticket-confirm-without-ticket" : "<div style='{{ $message_style }}'>感谢您的预订！车票只于发票当日起30日内有效。我们将在您出发前的 &lt;30&gt; 天电邮发送门票给您。</div>",

                "reject" : "<div style='{{ $message_style }}'>感谢您的预订！很抱歉 &lt;{{ $order_product->{App\OrderProduct::COLUMN_PRODUCT_TITLE} }}&gt; 于 &lt;{{ $order_product->{App\OrderProduct::COLUMN_DATE}->format('Y-m-d') }}&gt; 未能成团。您要将 &lt;something&gt; 更改为 &lt;something&gt; 吗？</div>",

                "refund" : "<div style='{{ $message_style }}'>我们已于 &lt;date&gt; 提交退款港币总额 HK$&lt;{{ number_format($order_product->{App\OrderProduct::COLUMN_TOTAL_AMOUNT}, 0) }}&gt; 给银行。<br />感谢您选择锦伦旅运作为您您旅途的伙伴，期待再为您提供服务。</div>",

                "other" : "<div style='{{ $container_style }}'>Free-style confirmation body</div><br />" +
                          "<div style='{{ $message_style }}'>&nbsp;</div>",

            };
        @endif

        $(function (){
            var init = function () {
                $("textarea").summernote({
                    height: 200,
                    toolbar: [
                        // [groupName, [list of button]]
                        ['style', ['bold', 'italic', 'underline', 'clear']],
                        ['font', ['strikethrough', 'superscript', 'subscript']],
                        ['fontsize', ['fontsize']],
                        ['color', ['color']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['insert', ['link']],
                        ['height', ['height']],
                    ],
                });
            };

            init();

            $("#btn-load").click(function (){
                var text = $("#select-template option:selected").text();
                $("#template-name").text(text);
                $("#template-preview").html(template[$("#select-template").val()]);
                $("#load-template-modal").modal('show');
            });

            $("#btn-confirm-load").click(function (){
                $("textarea").summernote('destroy');
                $("textarea").val(template[$("#select-template").val()]);
                init();
            });

            var create_upload_button = function (){
                var $button = $("<span class='btn btn-default btn-file'> <i class='fa fa-upload'></i> New <input name='upload[]' type='file'></span>");
                $button.find("input").change(function (){
                    var filename = $(this).val().replace(/^.*[\\\/]/, '');

                    // Create preview
                    var $preview = $("<div class='bs-callout bs-callout-warning attachment-preview'><h4>" + filename + "</h4>Will upload after save...<div class='delete'><i class='fa fa-trash-o'></i></div></div>");
                    $preview.find(".delete").click(function (){
                        $button.remove();
                        $preview.remove();
                    });
                    $preview.prependTo("#attachment-preview-container");

                    // Hide the input control and create new one
                    $(this).closest(".btn-file").hide();
                    create_upload_button();
                });
                $button.appendTo("#btn-upload-container");
            };

            create_upload_button();

            // Delete action for existing attachment previews
            $(".attachment-preview .delete").click(function (){
                var $preview = $(this).closest(".attachment-preview");

                $("<input name='delete-attachment[]' type='hidden' value='" + $preview.attr("data-attachment-id") + "'>").appendTo("#confirm-form");
                $preview.find(".timestamp").text("Will delete after save...");
                $preview.removeClass("bs-callout-info").addClass("bs-callout-danger");
                $(this).remove();
            });
        });
    </script>
@endsection