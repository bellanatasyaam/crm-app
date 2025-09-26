<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Customer Report</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        h2 { text-align: center; margin-bottom: 5px; }
        p { text-align: center; margin: 0; font-size: 11px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; font-size: 11px; }
        th { background: #f2f2f2; }
    </style>
</head>
<body>
    <h2>Customer Report</h2>
    <p>Period: {{ now()->startOfMonth()->format('d M Y') }} - {{ now()->endOfMonth()->format('d M Y') }}</p>
    <p>Generated at: {{ now()->format('d M Y H:i') }}</p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Name</th>
                <th>Assigned Staff</th>
                <th>Status</th>
                <th>Potential Revenue</th>
                <th>Last Follow Up</th>
                <th>Next Follow Up</th>
                <th>Notes</th>
            </tr>
        </thead>
        <tbody>
            @foreach($customers as $i => $c)
                <tr>
                    <td>{{ $i+1 }}</td>
                    <td>{{ $c->name }}</td>
                    <td>{{ $c->assigned_staff }}</td>
                    <td>{{ $c->status }}</td>
                    <td>{{ $c->currency }} {{ number_format($c->potential_revenue, 0) }}</td>
                    <td>{{ $c->last_followup_date ?? '-' }}</td>
                    <td>{{ $c->next_followup_date ?? '-' }}</td>
                    <td>{{ $c->notes ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
