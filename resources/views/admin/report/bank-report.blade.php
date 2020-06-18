<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>

<table>
    <tr>
        <td>BANK RECORD</td>
    </tr>
    <tr>
        <td></td>
    </tr>
    <tr>
        <td>Transaction Date</td>
        <td>Order no.</td>
        <td>Type</td>
        <td>Tour / Entry Date</td>
        <td>Product</td>
        <td>Quantity</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td>Subtotal</td>
        <td>Discount</td>
        <td>Amount</td>
        <td>Bank Reference</td>
        <td>Batch no</td>
        <td>Approval code</td>
        <td>Card no.</td>
    </tr>
    <tr>
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
            <td>{{ $order_product->{\App\OrderProduct::CREATED_AT} }}</td>
            <td>{{ $order_product->{\App\OrderProduct::COLUMN_ORDER_ID} }}</td>
            <td>{{ App\OrderProduct::$types[$order_product->{\App\OrderProduct::COLUMN_TYPE}] }}</td>
            <td>{{ $order_product->{\App\OrderProduct::COLUMN_DATE}->format('Y-m-d') }}</td>
            <td>{{ $order_product->{\App\OrderProduct::COLUMN_PRODUCT_PRICE_GROUP_TITLE} }}</td>
            <td>{{ $order_product->getReportTypeCount(App\TourPrice::TYPE_Adult) }}</td>
            <td>{{ $order_product->getReportTypePrice(App\TourPrice::TYPE_Adult) }}</td>
            <td>{{ $order_product->getReportTypeCount(App\TourPrice::TYPE_Child) }}</td>
            <td>{{ $order_product->getReportTypePrice(App\TourPrice::TYPE_Child) }}</td>
            <td>{{ $order_product->getReportTypeCount(App\TourPrice::TYPE_Senior) }}</td>
            <td>{{ $order_product->getReportTypePrice(App\TourPrice::TYPE_Senior) }}</td>
            <td>{{ $order_product->{\App\OrderProduct::COLUMN_SUB_TOTAL} }}</td>
            <td>{{ $order_product->{\App\OrderProduct::COLUMN_DISCOUNT} }}</td>
            <td>{{ $order_product->{\App\OrderProduct::COLUMN_TOTAL_AMOUNT} }}</td>
            <td>{{ $order_product->order->{\App\Order::COLUMN_PAYMENT_REFERENCE} }}</td>
            <td></td>
            <td></td>
            <td>{{ $order_product->order->{\App\Order::COLUMN_ACCOUNT_NO} }}</td>
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