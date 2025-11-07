@extends('layouts.app')

@section('content')
<div class="container py-4">

    <style>
        .container { max-width: 100% !important; }

        /* === HEADER === */
        .dashboard-header {
            background: linear-gradient(90deg, #007bff 0%, #00b4d8 100%);
            color: #fff;
            padding: 15px 20px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .dashboard-header h2 { font-size: 18px; font-weight: 600; }

        /* === BUTTONS === */
        .btn { border-radius: 8px; transition: 0.2s ease-in-out; }
        .btn:hover { transform: translateY(-1px); box-shadow: 0 2px 6px rgba(0,0,0,0.15); }

        /* === TABLE STYLES === */
        table.custom-table {
            font-size: 13px;
            border-collapse: collapse;
            width: 100%;
        }
        table.custom-table th,
        table.custom-table td {
            padding: 5px 6px;
            text-align: center;
            vertical-align: middle;
            border: 1px solid #dee2e6;
            white-space: nowrap;
        }
        table.custom-table th { background: #f8f9fa; font-weight: 600; }
        table.custom-table td { background: #fff; }

        .table-actions { display: flex; gap: 3px; justify-content: center; }
        .table-actions .btn { font-size: 11px; padding: 2px 5px; }

        .truncate-text {
            display: inline-block;
            max-width: 200px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            vertical-align: bottom;
        }
        .more-link { cursor: pointer; color: #0d6efd; font-size: 11px; margin-left: 5px; user-select: none; }

        /* === Dashboard Summary === */
        .summary-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 10px;
            margin-bottom: 15px;
        }
        .summary-card {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.08);
            padding: 12px;
            text-align: center;
        }
        .summary-card h4 { font-size: 20px; font-weight: bold; margin: 0; }
        .summary-card span { color: #6c757d; font-size: 13px; }

        /* === Chart Section === */
        .chart-section { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 25px; }
        @media(max-width: 992px) { .chart-section { grid-template-columns: 1fr; } }
        .chart-section canvas { max-height: 250px !important; }
        .chart-legend { display: flex; flex-wrap: wrap; justify-content: center; gap: 8px; margin-top: 8px; font-size: 12px; }
        .chart-legend span { display: flex; align-items: center; gap: 4px; }
        .chart-legend i { display: inline-block; width: 12px; height: 12px; border-radius: 3px; }
    </style>

    <!-- === HEADER === -->
    <div class="dashboard-header d-flex justify-content-between align-items-center mb-3">
        <h2 class="mb-0">Customers Dashboard</h2>
        <div class="d-flex gap-2">
            <a href="{{ route('companies.print') }}" class="btn btn-light btn-sm text-success fw-semibold" target="_blank">
                <i class="fa fa-print"></i> Print
            </a>
            @can('create', App\Models\Company::class)
                <a href="{{ route('companies.create') }}" class="btn btn-light btn-sm text-primary fw-semibold">
                    + Add Customer
                </a>
            @endcan
            <a href="{{ route('dashboard') }}" class="btn btn-outline-light btn-sm">Back To Master Menu</a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- === Dashboard Summary === --}}
    <div class="summary-cards mb-3">
        <div class="summary-card"><h4>{{ $summary['total_customers'] ?? 0 }}</h4><span>Total Customers</span></div>
        <div class="summary-card"><h4>{{ $stats['quotation_sent'] ?? 0 }}</h4><span>Quotation Sent</span></div>
        <div class="summary-card"><h4>{{ $stats['follow_up'] ?? 0 }}</h4><span>Follow Up</span></div>
        <div class="summary-card"><h4>{{ $stats['on_progress'] ?? 0 }}</h4><span>On Progress</span></div>
        <div class="summary-card"><h4>{{ $stats['done'] ?? 0 }}</h4><span>Done / Closing</span></div>
    </div>

    {{-- === Chart Section === --}}
    <div class="chart-section">
        <div class="card p-3">
            <canvas id="statusChart"></canvas>
            <div id="statusChartLegendContainer"></div>
        </div>
        <div class="card p-3">
            <canvas id="staffChart"></canvas>
        </div>
    </div>

    <!-- === TABLE === -->
    <div class="table-responsive">
        <table class="custom-table">
            <thead>
                <tr>
                    <th>Company Name</th><th>Email</th><th>Phone</th><th>Website</th><th>Tax ID</th>
                    <th>Customer Type</th><th>Industry</th><th>Tier</th><th>Status</th>
                    <th>Address</th><th>City</th><th>Country</th><th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($companies as $c)
                @php
                    $canEdit = auth()->user()->role === 'super_admin' || $c->assigned_staff_id === auth()->id();
                @endphp
                <tr>
                    <td>{{ $c->name }}</td>
                    <td>{{ $c->email ?? '-' }}</td>
                    <td>{{ $c->phone ?? '-' }}</td>
                    <td>{{ $c->website ?? '-' }}</td>
                    <td>{{ $c->tax_id ?? '-' }}</td>
                    <td>{{ $c->type ?? '-' }}</td>
                    <td>{{ $c->industry ?? '-' }}</td>
                    <td>{{ ucfirst($c->customer_tier ?? '-') }}</td>
                    <td>
                        @php
                            $statusColors = [
                                'Follow up' => 'badge bg-primary',
                                'On progress' => 'badge bg-info text-dark',
                                'Quotation send' => 'badge bg-warning text-dark',
                                'Done / Closing' => 'badge bg-success',
                                'Inactive' => 'badge bg-secondary',
                            ];
                        @endphp
                        <span class="{{ $statusColors[$c->status] ?? 'badge bg-light text-dark' }}">{{ $c->status ?? '-' }}</span>
                    </td>
                    <td>{{ \Illuminate\Support\Str::limit($c->address, 30) }}</td>
                    <td>{{ $c->city ?? '-' }}</td>
                    <td>{{ $c->country ?? '-' }}</td>
                    <td>
                        <div class="table-actions">
                            <a href="{{ route('companies.show', $c->id) }}" class="btn btn-info btn-sm">Detail</a>
                            @if($canEdit)
                                <a href="{{ route('companies.edit', $c->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('companies.destroy', $c->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Del</button>
                                </form>
                            @endif
                            <a href="{{ route('companies.print_single', $c->id) }}" target="_blank" class="btn btn-secondary btn-sm">🖨</a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-center mt-3">
        {{ $companies->links() }}
    </div>

</div>

{{-- === CHARTS JS === --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const statusData = {
        labels: ['Follow up', 'On progress', 'Quotation send', 'Done / Closing', 'Inactive'],
        data: [
            {{ $stats['follow_up'] ?? 0 }},
            {{ $stats['on_progress'] ?? 0 }},
            {{ $stats['quotation_sent'] ?? 0 }},
            {{ $stats['done'] ?? 0 }},
            {{ $stats['inactive'] ?? 0 }},
        ],
        colors: ['#60a5fa', '#34d399', '#fbbf24', '#22c55e', '#9ca3af']
    };

    const staffData = {
        labels: @json(array_keys($staff_stats ?? [])),
        data: @json(array_values($staff_stats ?? [])),
        color: '#3b82f6'
    };

    const ctx1 = document.getElementById('statusChart');
    new Chart(ctx1, {
        type: 'pie',
        data: { labels: statusData.labels, datasets: [{ data: statusData.data, backgroundColor: statusData.colors }] },
        options: { plugins: { legend: { display: false }, title: { display: true, text: 'Customer Status' } } }
    });

    const legend = document.getElementById('statusChartLegendContainer');
    legend.classList.add('chart-legend');
    statusData.labels.forEach((l, i) => {
        if (statusData.data[i] > 0) {
            const item = document.createElement('span');
            item.innerHTML = `<i style="background:${statusData.colors[i]}"></i>${l} (${statusData.data[i]})`;
            legend.appendChild(item);
        }
    });

    const ctx2 = document.getElementById('staffChart');
    new Chart(ctx2, {
        type: 'bar',
        data: { labels: staffData.labels, datasets: [{ label: 'Handled', data: staffData.data, backgroundColor: staffData.color }] },
        options: {
            responsive: true,
            scales: { y: { beginAtZero: true } },
            plugins: { legend: { display: false }, title: { display: true, text: 'Customers Per Staff' } }
        }
    });
});
</script>
@endsection
