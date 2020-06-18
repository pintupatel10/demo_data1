<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>

<table>
    <tr>
        <td>Title</td>
        <td>First Name</td>
        <td>Last Name</td>
        <td>Tel</td>
        <td>Email</td>
    </tr>
    @foreach ($customers as $customer)
        <tr>
            <td>{{ $customer->{\App\OrderProduct::COLUMN_TITLE} }}</td>
            <td>{{ $customer->{\App\OrderProduct::COLUMN_FIRST_NAME} }}</td>
            <td>{{ $customer->{\App\OrderProduct::COLUMN_LAST_NAME} }}</td>
            <td>{{ $customer->phone }}</td>
            <td>{{ $customer->{\App\OrderProduct::COLUMN_EMAIL} }}</td>
        </tr>
    @endforeach
</table>
</body>
</html>