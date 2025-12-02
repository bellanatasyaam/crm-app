<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Marketing Report - {{ $staff->name }}</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table, th, td {
            border: 1px solid #444;
        }
        th {
            background: #f0f0f0;
            padding: 6px;
            font-weight: bold;
            text-align: left;
        }
        td {
            padding: 6px;
        }
        h2 {
            margin-bottom: 5px;
        }
    </style>
</head>
<body>

    <h2>Marketing Report</h2>
    <p><strong>Staff:</strong> {{ $staff->name }}</p>
    <p><strong>Total Data:</strong> {{ $data->count() }}</p>

    <table>
        <thead>
            <tr>
                <th>Description</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Staff</th>
                <th>Last Contact</th>
                <th>Next FU</th>
                <th>Status</th>
                <th>Revenue</th>
                <th>Vessels</th>
                <th>Remark</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($data as $row)
            <tr>
                <td>{{ $row->description }}</td>
                <td>{{ $row->client_name ?? '-' }}</td>
                <td>{{ $row->email }}</td>
                <td>{{ $row->phone }}</td>
                <td>{{ $row->staff->name ?? '-' }}</td>
                <td>{{ $row->last_contact ?? '-' }}</td>
                <td>{{ $row->next_fu ?? '-' }}</td>
                <td>{{ $row->status }}</td>
                <td>{{ $row->revenue ?? '-' }}</td>
                <td>
                    @if($row->vessels)
                        {{ $row->vessels->pluck('name')->join(', ') }}
                    @else
                        -
                    @endif
                </td>
                <td>{{ $row->remark ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
