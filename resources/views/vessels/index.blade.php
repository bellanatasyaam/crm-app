@extends('layouts.app')

@section('content')
<div class="container py-4">

    <style>
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
        .table-actions {
            display: flex;
            gap: 3px;
            justify-content: center;
        }
        .table-actions .btn {
            font-size: 11px;
            padding: 2px 5px;
        }
    </style>

    <div class="d-flex justify-content-between mb-3">
        <h2 class="mb-0" style="font-size:18px;">
            @if($customer)
                Vessels for {{ $customer->name }}
            @else
                All Vessels
            @endif
        </h2>

        <div class="d-flex gap-2">
            @if($customer)
                <a href="{{ route('customers.vessels.create', $customer->id) }}" class="btn btn-primary btn-sm">
                    + Add Vessel
                </a>
                <a href="{{ route('customers.index') }}" class="btn btn-secondary btn-sm">
                    Back to Customers
                </a>
            @else
                <a href="{{ route('vessels.create') }}" class="btn btn-primary btn-sm">
                    + Add Vessel
                </a>
                <a href="{{ route('dashboard') }}" class="btn btn-secondary btn-sm">
                    Back to Master Menu
                </a>
            @endif
        </div>
    </div>

    @if($vessels->isEmpty())
        <div class="alert alert-info">No vessels found.</div>
    @else
        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>Vessel Name</th>
                        <th>Port of Call</th>
                        <th>Status</th>
                        <th>Revenue</th>
                        <th>Currency</th>
                        <th>Description</th>
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
                            <td>{{ $vessel->currency }}</td>
                            <td>{{ $vessel->description }}</td>
                            <td>{{ $vessel->remark ?? '-' }}</td>
                            <td>{{ $vessel->last_contact ?? '-' }}</td>
                            <td>{{ $vessel->next_follow_up ?? '-' }}</td>
                            <td>{{ $vessel->assignedStaff?->name ?? '-' }}</td>
                            @unless($customer)
                                <td>{{ $vessel->customer?->name ?? '-' }}</td>
                            @endunless
                            <td>
                                <div class="table-actions">
                                    @if($customer)
                                        <a href="{{ route('customers.vessels.edit', [$customer->id, $vessel->id]) }}" class="btn btn-warning btn-sm">Edit</a>
                                        <form action="{{ route('customers.vessels.destroy', [$customer->id, $vessel->id]) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-sm">Del</button>
                                        </form>
                                        <a href="{{ route('customers.vessels.show', [$customer->id, $vessel->id]) }}" class="btn btn-info btn-sm">Detail</a>
                                    @else
                                        <a href="{{ route('vessels.edit', $vessel->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                        <form action="{{ route('vessels.destroy', $vessel->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-sm">Del</button>
                                        </form>
                                        <a href="{{ route('vessels.show', $vessel->id) }}" class="btn btn-info btn-sm">Detail</a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

</div>
@endsection
