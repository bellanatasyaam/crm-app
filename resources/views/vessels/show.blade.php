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

    <h2 class="mb-4">Customer Profile</h2>

    <div class="card mb-4">
        <div class="card-body">
            <h4>{{ $customer->name }}</h4>
            <p><strong>Email:</strong> {{ $customer->email ?? '-' }}</p>
            <p><strong>Phone:</strong> {{ $customer->phone ?? '-' }}</p>
            <p><strong>Address:</strong> {{ $customer->address ?? '-' }}</p>
        </div>
    </div>

    <div class="d-flex justify-content-between mb-3">
        <h4 class="mb-0">Vessels</h4>
        <a href="{{ route('customers.vessels.create', $customer->id) }}" class="btn btn-primary btn-sm">+ Add Vessel</a>
    </div>

    @if($customer->vessels->isEmpty())
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
                        <th>Customer</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($customer->vessels as $v)
                        <tr>
                            <td>{{ $v->vessel_name }}</td>
                            <td>{{ $v->port_of_call ?? '-' }}</td>
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
                                <span class="{{ $statusColors[$v->status] ?? 'badge bg-light text-dark' }}">
                                    {{ $v->status ?? '-' }}
                                </span>
                            </td>
                            <td>{{ number_format($v->estimate_revenue ?? 0, 0) }}</td>
                            <td>{{ $v->currency ?? '-' }}</td>
                            <td>{{ $v->description ?? '-' }}</td>
                            <td>{{ $v->remark ?? '-' }}</td>
                            <td>{{ $v->last_contact ?? '-' }}</td>
                            <td>{{ $v->next_follow_up ?? '-' }}</td>
                            <td>{{ $v->assignedStaff?->name ?? '-' }}</td>
                            <td>{{ $v->customer?->name ?? '-' }}</td>
                            <td>
                                <div class="table-actions">
                                    <a href="{{ route('customers.vessels.edit', [$customer->id, $v->id]) }}" class="btn btn-warning btn-sm">Edit</a>
                                    <form action="{{ route('customers.vessels.destroy', [$customer->id, $v->id]) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm" onclick="return confirm('Delete this vessel?')">Del</button>
                                    </form>
                                    <a href="{{ route('vessels.show', $v->id) }}" class="btn btn-info btn-sm">Detail</a>
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
