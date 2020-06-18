<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>

<table>
    <tr>
        <td>{{ $title }}</td>
    </tr>
    <tr>
        <td></td>
    </tr>
    <tr>
        <td>Transaction Date</td>
        <td>Order no.</td>
        <td>Bank Reference</td>
        <td>Tour / Entry Date</td>
        <td>Guest Name</td>
        <td>Type</td>
        <td>Product</td>
        <td>Quantity</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td>Amount</td>
        <td>Manual Voucher no.</td>
        <td>Computer Voucher no.</td>
        <td>e-ticket no.</td>
        <td>Live Ticket no.</td>
        <td>Completed</td>
        <td>Remarks</td>
        <td>Handled by</td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td>Adult</td>
        <td>Price</td>
        <td>Child</td>
        <td>Price</td>
        <td>Senior</td>
        <td>Price</td>
    </tr>
    @foreach ($order_products as $order_product)
        <tr>
            <td>{{ $order_product->{\App\OrderProduct::CREATED_AT}->format('Y-m-d') }}</td>
            <td>{{ $order_product->{\App\OrderProduct::COLUMN_ORDER_ID} }}</td>
            <td>{{ $order_product->order->{\App\Order::COLUMN_PAYMENT_REFERENCE} }}</td>
            <td>{{ $order_product->{\App\OrderProduct::COLUMN_DATE}->format('Y-m-d') }}</td>
            <td>{{ $order_product->name }}</td>
            <td>{{ App\OrderProduct::$types[$order_product->{\App\OrderProduct::COLUMN_TYPE}] }}</td>
            <td>{{ $order_product->{\App\OrderProduct::COLUMN_PRODUCT_PRICE_GROUP_TITLE} }}</td>
            <td>{{ $order_product->getReportTypeCount(App\TourPrice::TYPE_Adult) }}</td>
            <td>{{ $order_product->getReportTypePrice(App\TourPrice::TYPE_Adult) }}</td>
            <td>{{ $order_product->getReportTypeCount(App\TourPrice::TYPE_Child) }}</td>
            <td>{{ $order_product->getReportTypePrice(App\TourPrice::TYPE_Child) }}</td>
            <td>{{ $order_product->getReportTypeCount(App\TourPrice::TYPE_Senior) }}</td>
            <td>{{ $order_product->getReportTypePrice(App\TourPrice::TYPE_Senior) }}</td>
            <td>{{ $order_product->{\App\OrderProduct::COLUMN_TOTAL_AMOUNT} }}</td>
            <td>{{ $order_product->{\App\OrderProduct::COLUMN_VOUCHER_NO} }}</td>
            <td>{{ $order_product->{\App\OrderProduct::COLUMN_ID} }}</td>
            <td>{{ $order_product->{\App\OrderProduct::COLUMN_E_TICKET_NO} }}</td>
            <td>{{ $order_product->{\App\OrderProduct::COLUMN_LIVE_TICKET_NO} }}</td>
            <td>
                @if ($order_product->{App\OrderProduct::COLUMN_STATUS} == \App\OrderProduct::STATUS_COMPLETED
                || $order_product->{App\OrderProduct::COLUMN_STATUS} == \App\OrderProduct::STATUS_CANCELED)
                    P
                @else
                    O
                @endif
            </td>
            <td></td>
            <td>{{ $order_product->confirmed_user ? $order_product->confirmed_user->name : "" }}</td>
        </tr>
    @endforeach
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td>Total Amount</td>
        <td></td>
        <td>{{ $order_products->sum(App\OrderProduct::COLUMN_TOTAL_AMOUNT) }}</td>
    </tr>
</table>
</body>
</html>