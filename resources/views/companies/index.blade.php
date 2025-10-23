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
        .dashboard-header h2 {
            font-size: 18px;
            font-weight: 600;
        }

        /* === BUTTONS === */
        .btn {
            border-radius: 8px;
            transition: 0.2s ease-in-out;
        }
        .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 6px rgba(0,0,0,0.15);
        }

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
        table.custom-table th {
            background: #f8f9fa;
            font-weight: 600;
        }
        table.custom-table td { background: #fff; }

        table.custom-table td.desc-col,
        table.custom-table td.remark-col {
            white-space: normal !important;
            word-break: break-word;
            max-width: 250px;
            text-align: left;
        }

        .table-actions {
            display: flex;
            gap: 3px;
            justify-content: center;
        }
        .table-actions .btn {
            font-size: 11px;
            padding: 2px 5px;
        }

        .truncate-text {
            display: inline-block;
            max-width: 200px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            vertical-align: bottom;
        }
        .more-link {
            cursor: pointer;
            color: #0d6efd;
            font-size: 11px;
            margin-left: 5px;
            user-select: none; /* Prevents text selection on click */
        }

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
        .summary-card h4 {
            font-size: 20px;
            font-weight: bold;
            margin: 0;
        }
        .summary-card span {
            color: #6c757d;
            font-size: 13px;
        }

        /* === Chart Section === */
        .chart-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 25px;
        }

        @media(max-width: 992px) {
            .chart-section { grid-template-columns: 1fr; }
        }

        /* Chart adjustment */
        .chart-section canvas {
            max-height: 250px !important; /* Increased height for better visibility */
        }
        .chart-legend {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 8px;
            margin-top: 8px;
            font-size: 12px;
        }
        .chart-legend span {
            display: flex;
            align-items: center;
            gap: 4px;
        }
        .chart-legend i {
            display: inline-block;
            width: 12px;
            height: 12px;
            border-radius: 3px;
        }
    </style>

    <!-- === HEADER === -->
    <div class="dashboard-header d-flex justify-content-between align-items-center mb-3">
        <h2 class="mb-0">📊 Marketing Dashboard</h2>
        <div class="d-flex gap-2">
            <a href="{{ route('companies.print') }}" class="btn btn-light btn-sm text-success fw-semibold" target="_blank">
                <i class="fa fa-print"></i> Print Report
            </a>
            @can('create', App\Models\Company::class)
                <a href="{{ route('companies.create') }}" class="btn btn-light btn-sm text-primary fw-semibold">
                    + Add Report
                </a>
            @endcan
            <a href="{{ route('dashboard') }}" class="btn btn-outline-light btn-sm">
                Back to Master Menu
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- === Daily Report Filter === --}}
    <form method="GET" action="{{ route('dashboard') }}" class="d-flex mb-3 align-items-center gap-2">
        <label for="date" class="mb-0 fw-semibold">Filter Tanggal:</label>
        {{-- Use form-control-sm for better fit --}}
        <input type="date" name="date" id="date" value="{{ request('date') ?? date('Y-m-d') }}" class="form-control form-control-sm" style="max-width: 180px;">
        <button type="submit" class="btn btn-primary btn-sm">Filter</button>
    </form>


    {{-- === Dashboard Summary === --}}
    <div class="summary-cards">
        <div class="summary-card">
            <h4>{{ $summary['total_customers'] ?? 0 }}</h4>
            <span>Total Customers</span>
        </div>
        <div class="summary-card">
            <h4>{{ $stats['quotation_sent'] ?? 0 }}</h4>
            <span>Quotation Sent</span>
        </div>
        <div class="summary-card">
            <h4>{{ $stats['follow_up'] ?? 0 }}</h4>
            <span>Follow Up</span>
        </div>
        <div class="summary-card">
            <h4>{{ $stats['on_progress'] ?? 0 }}</h4>
            <span>On Progress</span>
        </div>
        <div class="summary-card">
            <h4>{{ $stats['done'] ?? 0 }}</h4>
            <span>Done / Closing</span>
        </div>
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

    <!-- Customer Table -->
    <div class="table-responsive">
        <table class="custom-table">
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
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($companies as $c)
                <tr>
                    <td class="desc-col" title="{{ $c->description }}">
                        @if($c->description)
                            {{-- Use a generalized class for truncation --}}
                            <span class="truncate-text">{{ $c->description }}</span>
                            {{-- Use a generalized toggle function --}}
                            <span class="more-link" onclick="toggleText(this)">More</span>
                            <span class="full-text d-none">{{ $c->description }}</span>
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ $c->name }}</td>
                    <td class="email-col" title="{{ $c->email }}">
                        @if($c->email)
                            {{-- Use a generalized class for truncation --}}
                            <span class="truncate-text">{{ \Illuminate\Support\Str::limit($c->email, 30) }}</span>
                            <span class="more-link" onclick="toggleText(this)">More</span>
                            <span class="full-text d-none">{{ $c->email }}</span>
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ $c->phone ?? '-' }}</td>
                    <td>{{ $c->assigned_staff }}</td>
                    <td>{{ $c->last_followup_date ?? '-' }}</td>
                    <td>{{ $c->next_followup_date ?? '-' }}</td>
                    <td>
                        @php
                            $statusColors = [
                                'Follow up'         => 'badge bg-primary',
                                'On progress'       => 'badge bg-info text-dark',
                                'Request'           => 'badge bg-warning text-dark',
                                'Waiting approval'  => 'badge bg-secondary',
                                'Approve'           => 'badge bg-success',
                                'On going'          => 'badge bg-dark',
                                'Quotation send'    => 'badge bg-primary',
                                'Done / Closing'    => 'badge bg-success',
                            ];
                        @endphp
                        <span class="{{ $statusColors[$c->status] ?? 'badge bg-light text-dark' }}">
                            {{ $c->status }}
                        </span>
                    </td>
                    <td>{{ $c->currency }} {{ number_format($c->potential_revenue, 0) }}</td>
                    <td>
                        @forelse($c->vessels as $v)
                            <span class="badge bg-info text-dark">{{ $v->vessel_name }}</span>
                        @empty
                            -
                        @endforelse
                    </td>
                    <td class="remark-col" title="{{ $c->remark }}">
                        {{ \Illuminate\Support\Str::limit($c->remark, 20) }}
                    </td>
                    <td>
                        <div class="table-actions">
                            @can('update', $c)
                                <a href="{{ route('companies.edit', $c->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            @endcan
                            @can('view', $c)
                                <a href="{{ route('companies.show', $c->id) }}" class="btn btn-info btn-sm">Detail</a>
                            @endcan
                            @can('delete', $c)
                                <form action="{{ route('companies.destroy', $c->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    {{-- Replaced confirm() with a visual indicator/modal approach, though for brevity, keeping the inline approach here --}}
                                    <button class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Del</button>
                                </form>
                            @endcan
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

{{-- Chart.js and custom JS --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Define the data for the charts outside the Chart initialization function
    const statusData = {
        labels: [
            'Follow up', 'On progress', 'Request', 'Waiting approval',
            'Approve', 'On going', 'Quotation send', 'Done / Closing'
        ],
        data: [
            {{ $stats['follow_up'] ?? 0 }},
            {{ $stats['on_progress'] ?? 0 }},
            {{ $stats['request'] ?? 0 }},
            {{ $stats['waiting_approval'] ?? 0 }},
            {{ $stats['approve'] ?? 0 }},
            {{ $stats['on_going'] ?? 0 }},
            {{ $stats['quotation_sent'] ?? 0 }},
            {{ $stats['done'] ?? 0 }}
        ],
        colors: [
            '#60a5fa', '#34d399', '#fbbf24', '#9ca3af', // Tailwind Blue-400, Emerald-400, Amber-400, Gray-400
            '#22c55e', '#111827', '#3b82f6', '#ef4444'  // Tailwind Green-600, Gray-900, Blue-500, Red-500
        ]
    };

    const staffData = {
        labels: @json(array_keys($staff_stats ?? ['Wika', 'Aulia', 'Leni'])), // Assuming $staff_stats is available or fallback
        data: @json(array_values($staff_stats ?? [10, 6, 8])),
        colors: '#3b82f6'
    };

    // Generalized function to toggle truncation for description/email
    function toggleText(el) {
        let row = el.closest('td');
        // Find the truncated and full text spans within the closest <td>
        let trunc = row.querySelector('.truncate-text');
        let full = row.querySelector('.full-text');

        if (!trunc || !full) return; // Guard clause

        if (full.classList.contains('d-none')) {
            // Show full text
            trunc.classList.add('d-none');
            full.classList.remove('d-none');
            el.innerText = "Less";
        } else {
            // Show truncated text
            trunc.classList.remove('d-none');
            full.classList.add('d-none');
            el.innerText = "More";
        }
    }

    // Initialize charts when the DOM is fully loaded
    document.addEventListener('DOMContentLoaded', function() {

        // === Status Chart (Pie) ===
        const ctx1 = document.getElementById('statusChart');
        const statusChart = new Chart(ctx1, {
            type: 'pie',
            data: {
                labels: statusData.labels,
                datasets: [{
                    data: statusData.data,
                    backgroundColor: statusData.colors
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false // We will render a custom legend
                    },
                    title: {
                        display: true,
                        text: 'Customer Status Distribution',
                        font: { size: 14, weight: '600' }
                    }
                },
                maintainAspectRatio: false
            }
        });

        // Custom Legend Generation
        const legendContainer = document.getElementById('statusChartLegendContainer');
        legendContainer.classList.add('chart-legend');
        statusData.labels.forEach((label, i) => {
            // Only add legend items for data points > 0
            if (statusData.data[i] > 0) {
                const item = document.createElement('span');
                item.innerHTML = `<i style="background:${statusData.colors[i]}"></i>${label} (${statusData.data[i]})`;
                legendContainer.appendChild(item);
            }
        });

        // === Staff Chart (Bar) ===
        const ctx2 = document.getElementById('staffChart');
        new Chart(ctx2, {
            type: 'bar',
            data: {
                labels: staffData.labels,
                datasets: [{
                    label: 'Total Customers Handled',
                    data: staffData.data,
                    backgroundColor: staffData.colors,
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                // Ensure ticks are integers
                                if (Number.isInteger(value)) {
                                    return value;
                                }
                            }
                        }
                    },
                },
                plugins: {
                    title: {
                        display: true,
                        text: 'Customers Per Staff Member',
                        font: { size: 14, weight: '600' }
                    },
                    legend: {
                        display: false
                    }
                },
                maintainAspectRatio: false
            }
        });
    });

    // Make the toggleText function globally available for inline onclick calls
    window.toggleText = toggleText;

</script>
@endsection
