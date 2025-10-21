@extends('layouts.app')

@section('content')
<div class="container py-4">

    <style>
        /* --- Global --- */
        .container {
            max-width: 100% !important;
        }

        body {
            background: #f5f6fa;
        }

        h2 {
            font-weight: 600;
            color: #2c3e50;
        }

        /* --- Header --- */
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

        /* --- Button group --- */
        .btn {
            border-radius: 8px;
            transition: 0.2s ease-in-out;
        }
        .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 6px rgba(0,0,0,0.15);
        }

        /* --- Table wrapper --- */
        .table-wrapper {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            padding: 15px;
            margin-top: 20px;
        }

        /* --- Custom table --- */
        table.custom-table {
            font-size: 13px;
            border-collapse: collapse;
            width: 100%;
        }
        table.custom-table th {
            background: #f1f3f5;
            font-weight: 600;
            color: #495057;
            padding: 10px;
            text-align: center;
            border-bottom: 2px solid #dee2e6;
        }
        table.custom-table td {
            padding: 8px;
            text-align: center;
            vertical-align: middle;
            border-bottom: 1px solid #eee;
        }
        table.custom-table tr:hover {
            background-color: #f8f9fa;
        }

        /* Kolom panjang */
        table.custom-table td.desc-col,
        table.custom-table td.remark-col {
            white-space: normal !important;
            word-break: break-word;
            max-width: 250px;
            text-align: left;
        }

        /* Badge */
        .badge {
            font-size: 11px;
            border-radius: 6px;
            padding: 4px 7px;
        }

        /* Actions */
        .table-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 4px;
            justify-content: center;
        }
        .table-actions .btn {
            font-size: 11px;
            padding: 3px 6px;
        }

        /* Text ellipsis */
        .truncate {
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
        }
    </style>

    <!-- Header -->
    <div class="dashboard-header d-flex justify-content-between align-items-center mb-3">
        <h2 class="mb-0">
            @if($customer)
                🚢 Vessels for {{ $customer->name }}
            @else
                 Vessel List
            @endif
        </h2>
        <div class="d-flex gap-2">
            @if($customer)
                <a href="{{ route('customers.vessels.create', $customer->id) }}" class="btn btn-light btn-sm text-primary fw-semibold">
                    + Add Vessel
                </a>
                <a href="{{ route('customers.index') }}" class="btn btn-outline-light btn-sm">
                    Back to Customers
                </a>
            @else
                <a href="{{ route('vessels.create') }}" class="btn btn-light btn-sm text-primary fw-semibold">
                    + Add Vessel
                </a>
                <a href="{{ route('dashboard') }}" class="btn btn-outline-light btn-sm">
                    Back to Master Menu
                </a>
            @endif
        </div>
    </div>

    <!-- Alert -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show mt-2" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Table -->
    @if($vessels->isEmpty())
        <div class="alert alert-info mt-3">No vessels found.</div>
    @else
        <div class="table-wrapper table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>Description</th>
                        <th>Vessel Name</th>
                        <th>Port of Call</th>
                        <th>Status</th>
                        <th>Revenue</th>
                        <th>Currency</th>
                        <th>Remark</th>
                        <th>Last Contact</th>
                        <th>Next FU</th>
                        <th>Staff</th>
                        @unless($customer)
                            <th>Customer</th>
                        @endunless
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($vessels as $vessel)
                    <tr>
                        <td class="desc-col">
                            @if($vessel->description)
                                <span class="truncate">{{ $vessel->description }}</span>
                                <span class="more-link" onclick="toggleDesc(this)">More</span>
                                <span class="full-text d-none">{{ $vessel->description }}</span>
                            @else
                                -
                            @endif
                        </td>
                        <td>{{ $vessel->vessel_name }}</td>
                        <td>{{ $vessel->port_of_call ?? '-' }}</td>
                        <td>
                            @php
                                $statusColors = [
                                    'Follow up'        => 'badge bg-primary',
                                    'On progress'      => 'badge bg-info text-dark',
                                    'Request'          => 'badge bg-warning text-dark',
                                    'Waiting approval' => 'badge bg-secondary',
                                    'Approve'          => 'badge bg-success',
                                    'On going'         => 'badge bg-dark',
                                    'Quotation send'   => 'badge bg-primary',
                                    'Done / Closing'   => 'badge bg-success'
                                ];
                            @endphp
                            <span class="{{ $statusColors[$vessel->status] ?? 'badge bg-light text-dark' }}">
                                {{ $vessel->status }}
                            </span>
                        </td>
                        <td>{{ number_format($vessel->estimate_revenue, 0) }}</td>
                        <td>{{ $vessel->currency ?? '-' }}</td>
                        <td class="remark-col">{{ \Illuminate\Support\Str::limit($vessel->remark, 30) }}</td>
                        <td>{{ $vessel->last_contact ?? '-' }}</td>
                        <td>{{ $vessel->next_follow_up ?? '-' }}</td>
                        <td>{{ $vessel->assignedStaff?->name ?? '-' }}</td>
                        @unless($customer)
                            <td>{{ $vessel->customer?->name ?? '-' }}</td>
                        @endunless
                        <td>
                            <div class="table-actions">
                                @php
                                    $hasCustomer = isset($vessel->customer_id) && !empty($vessel->customer_id);
                                @endphp

                                {{-- Tombol untuk Staff yang Assigned atau Super Admin --}}
                                @if($vessel->assigned_staff_id == auth()->id() || auth()->user()->role == 'super_admin')
                                    @if($hasCustomer)
                                        <a href="{{ route('customers_vessels.edit', [$vessel->customer_id, $vessel->id]) }}" class="btn btn-warning btn-sm">Edit</a>
                                        <a href="{{ route('customers_vessels.show', [$vessel->customer_id, $vessel->id]) }}" class="btn btn-info btn-sm">Detail</a>
                                        <form action="{{ route('customers.vessels.destroy', [$vessel->customer_id, $vessel->id]) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Del</button>
                                        </form>
                                    @else
                                        <a href="{{ route('vessels.edit', $vessel->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                        <a href="{{ route('vessels.show', $vessel->id) }}" class="btn btn-info btn-sm">Detail</a>
                                        <form action="{{ route('vessels.destroy', $vessel->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Del</button>
                                        </form>
                                    @endif
                                @else
                                    {{-- Untuk staff lain (hanya bisa lihat Detail) --}}
                                    @if($hasCustomer)
                                        <a href="{{ route('customers.vessels.show', [$vessel->customer_id, $vessel->id]) }}" class="btn btn-info btn-sm">Detail</a>
                                    @else
                                        <a href="{{ route('vessels.show', $vessel->id) }}" class="btn btn-info btn-sm">Detail</a>
                                    @endif
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="d-flex justify-content-center mt-3">
                {{ $vessels->links() }}
            </div>
        </div>
    @endif

</div>

<script>
function toggleDesc(el) {
    let row = el.closest('td');
    let trunc = row.querySelector('.truncate');
    let full = row.querySelector('.full-text');
    if (full.classList.contains('d-none')) {
        trunc.style.display = 'none';
        full.classList.remove('d-none');
        el.innerText = "Less";
    } else {
        trunc.style.display = 'inline-block';
        full.classList.add('d-none');
        el.innerText = "More";
    }
}
</script>
@endsection
