<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Customer Detail</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        h2 { text-align: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; }
    </style>
</head>
<body>
    <h2>Customer Detail</h2>

    <table>
        <tr>
            <th>Name</th>
            <td>{{ $customer->name }}</td>
        </tr>
        <tr>
            <th>Email</th>
            <td>{{ $customer->email ?? '-' }}</td>
        </tr>
        <tr>
            <th>Phone</th>
            <td>{{ $customer->phone ?? '-' }}</td>
        </tr>
        <tr>
            <th>Status</th>
            <td>{{ $customer->status }}</td>
        </tr>
        <tr>
            <th>Potential Revenue</th>
            <td>{{ $customer->currency }} {{ number_format($customer->potential_revenue, 0) }}</td>
        </tr>
        <tr>
            <th>Notes</th>
            <td>{{ $customer->notes ?? '-' }}</td>
        </tr>
    </table>
</body>
</html>
