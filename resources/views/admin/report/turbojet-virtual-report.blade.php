<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>

<table>
    <tr>
        <td>Off Line Sales Report</td>
    </tr>
    <tr>
        <td>Date: {{ $date }}</td>
    </tr>
    <tr>
        <td>Transaction No.</td>
        <td>Seq No.</td>
        <td>Passenger first name</td>
        <td>Passenger last name</td>
        <td>Travel document type</td>
        <td>Travel document no</td>
        <td>Route code</td>
        <td>Seat Class</td>
        <td>Sailing time</td>
        <td>Promotion code</td>
        <td>Collector name</td>
    </tr>
    @foreach ($reservations as $reservation)
        <tr>
            <td>{{ $reservation['transaction-id'] }}</td>
            <td>{{ $reservation['reservation-no'] }}</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>{{ $reservation['route'] }}</td>
            <td>{{ $reservation['class'] }}</td>
            <td>{{ $reservation['time'] }}</td>
            <td>{{ $reservation['promo-code'] }}</td>
            <td>{{ $reservation['collector'] }}</td>
        </tr>
    @endforeach
</table>
</body>
</html>