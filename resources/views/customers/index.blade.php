@extends('layouts.app')

@section('content')
<div class="container py-4">

    <div class="d-flex justify-content-between mb-4">
        <h2 class="mb-0">Customer Dashboard</h2>
        <div class="d-flex gap-2">
            <a href="{{ route('customers.print') }}" class="btn btn-success" target="_blank">
                <i class="fa fa-print"></i> Print Report
            </a>
            <a href="{{ route('customers.create') }}" class="btn btn-primary">
                + Add Customer
            </a>
        </div>
    </div>

    <!-- Filter Form -->
    <form action="{{ route('customers.index') }}" method="GET" class="row g-2 mb-4">
        <div class="col-md-4">
            <input type="text" name="search" class="form-control" placeholder="Search customer..." value="{{ request('search') }}">
        </div>
        <div class="col-md-3">
            <select name="status" class="form-select">
                <option value="">-- Filter by Status --</option>
                @foreach(['Lead','Quotation Sent','Negotiation','On Going Vessel Call','Pending Payment','Closing'] as $status)
                    <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>{{ $status }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <input type="text" name="assigned_staff" class="form-control" placeholder="Filter by Staff" value="{{ request('assigned_staff') }}">
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100">Filter</button>
        </div>
    </form>

    <!-- Summary Table -->
    <div class="mb-4">
        <table class="table table-sm table-bordered text-center align-middle">
            <thead class="table-light">
                <tr>
                    <th>Total Customers</th>
                    <th>Leads</th>
                    <th>Quotation Sent</th>
                    <th>Negotiation</th>
                    <th>On Going Vessel</th>
                    <th>Pending Payment</th>
                    <th>Closing</th>
                    <th class="text-danger">Overdue</th>
                    <th class="text-warning">Due Today</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $summary['total_customers'] ?? 0 }}</td>
                    <td>{{ $summary['by_status']['Lead'] ?? 0 }}</td>
                    <td>{{ $summary['by_status']['Quotation Sent'] ?? 0 }}</td>
                    <td>{{ $summary['by_status']['Negotiation'] ?? 0 }}</td>
                    <td>{{ $summary['by_status']['On Going Vessel Call'] ?? 0 }}</td>
                    <td>{{ $summary['by_status']['Pending Payment'] ?? 0 }}</td>
                    <td>{{ $summary['by_status']['Closing'] ?? 0 }}</td>
                    <td>{{ $reminders['overdue'] ?? 0 }}</td>
                    <td>{{ $reminders['today'] ?? 0 }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Customer Table -->
    <div class="table-responsive">
        <table class="table table-hover table-bordered align-middle">
            <thead class="table-light">
                <tr>
                    <th>Name</th>
                    <th>Assigned Staff</th>
                    <th>Last Contact</th>
                    <th>Next Follow-Up</th>
                    <th>Status</th>
                    <th>Revenue</th>
                    <th>Notes</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($customers as $c)
                    <tr>
                        <td>{{ $c->name }}</td>
                        <td>{{ $c->assigned_staff }}</td>
                        <td>{{ $c->last_followup_date ?? '-' }}</td>
                        <td>{{ $c->next_followup_date ?? '-' }}</td>
                        <td>
                            @php
                                $statusColors = [
                                    'Lead' => 'badge bg-primary',
                                    'Quotation Sent' => 'badge bg-info text-dark',
                                    'Negotiation' => 'badge bg-warning text-dark',
                                    'On Going Vessel Call' => 'badge bg-secondary',
                                    'Pending Payment' => 'badge bg-danger',
                                    'Closing' => 'badge bg-success'
                                ];
                            @endphp
                            <span class="{{ $statusColors[$c->status] ?? 'badge bg-light text-dark' }}">
                                {{ $c->status }}
                            </span>
                        </td>
                        <td>{{ $c->currency }} {{ number_format($c->potential_revenue, 0) }}</td>
                        <td>{{ $c->notes ?? '-' }}</td>
                        <td class="d-flex gap-1">
                            @can('update', $c)
                            <a href="{{ route('customers.edit', $c->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            @endcan
                            
                            @can('delete', $c)
                            <form action="{{ route('customers.destroy', $c->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm">Delete</button>
                            </form>
                            @endcan

                            <a href="{{ route('customers.print_single', $c->id) }}" target="_blank" class="btn btn-secondary btn-sm">
                                🖨 Print
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-3">
        {{ $customers->links() }}
    </div>

</div>
@endsection
