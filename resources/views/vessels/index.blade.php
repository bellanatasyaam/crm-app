@extends('layouts.app')

@section('content')
<div class="container py-4">

    <style>
        /* Style tabel biar kecil & rapi */
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
        table.custom-table td {
            background: #fff;
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
        <h2 class="mb-0" style="font-size:18px;">Vessels for {{ $customer->name }}</h2>
        <div class="d-flex gap-2">
            <a href="{{ route('customers.vessels.create', $customer->id) }}" class="btn btn-primary btn-sm">
                + Add Vessel
            </a>
            <a href="{{ route('customers.index') }}" class="btn btn-secondary btn-sm">
                Back to Customers
            </a>
        </div>
    </div>

    @if($vessels->isEmpty())
        <div class="alert alert-info">No vessels found for this customer.</div>
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
                            <td>
                                <div class="table-actions">
                                    <a href="{{ route('customers.vessels.edit', [$customer->id, $vessel->id]) }}" class="btn btn-warning btn-sm">Edit</a>
                                    <form action="{{ route('customers.vessels.destroy', [$customer->id, $vessel->id]) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm">Del</button>
                                    </form>
                                    <a href="{{ route('customers.vessels.show', [$customer->id, $vessel->id]) }}" class="btn btn-info btn-sm">Detail</a>
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
