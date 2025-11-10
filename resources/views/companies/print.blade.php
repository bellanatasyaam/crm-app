<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Customer Report</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            color: #1f2937;
        }

        h2 {
            text-align: center;
            margin-bottom: 5px;
        }
        p {
            text-align: center;
            margin: 0;
            font-size: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #d1d5db;
            padding: 6px 8px;
            text-align: left;
            font-size: 10px;
            vertical-align: top;
        }

        th {
            background-color: #f3f4f6;
            font-weight: bold;
        }

        .signature-boxes {
            display: flex;
            justify-content: space-between;
            margin-top: 60px;
        }

        .signature {
            text-align: center;
            width: 18%;
        }

        .signature .line {
            border-bottom: 1px solid #000;
            margin-top: 50px;
            margin-bottom: 5px;
        }
    </style>
</head>
<body>

    <h2>Customer Report</h2>
    <p>Generated at: {{ now()->format('d M Y, H:i') }}</p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Company Name</th>
                <th>Assigned Staff</th>
                <th>Status</th>
                <th>Potential Revenue</th>
                <th>Last Follow Up</th>
                <th>Next Follow Up</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($companies as $index => $c)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $c->name }}</td>
                    <td>{{ $c->assignedStaff->name ?? '-' }}</td>
                    <td>{{ $c->status ?? '-' }}</td>
                    <td>
                        {{ $c->currency ?? 'IDR' }}
                        {{ number_format($c->potential_revenue ?? 0, 0, ',', '.') }}
                    </td>
                    <td>{{ $c->last_follow_up ? \Carbon\Carbon::parse($c->last_follow_up)->format('d M Y') : '-' }}</td>
                    <td>{{ $c->next_follow_up ? \Carbon\Carbon::parse($c->next_follow_up)->format('d M Y') : '-' }}</td>
                    <td>{{ $c->description ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align:center; color:#6b7280;">No data available</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="signature-boxes">
        <div class="signature">
            <p>Prepared by:</p>
            <div class="line"></div>
            <p><strong>Admin</strong></p>
        </div>
        <div class="signature">
            <p>Reviewed by:</p>
            <div class="line"></div>
            <p><strong>Marketing</strong></p>
        </div>
        <div class="signature">
            <p>Reviewed by:</p>
            <div class="line"></div>
            <p><strong>Finance</strong></p>
        </div>
        <div class="signature">
            <p>Acknowledged by:</p>
            <div class="line"></div>
            <p><strong>Manager</strong></p>
        </div>
        <div class="signature">
            <p>Approved by:</p>
            <div class="line"></div>
            <p><strong>Director</strong></p>
        </div>
    </div>

</body>
</html>
