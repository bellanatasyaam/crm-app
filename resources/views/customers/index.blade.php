@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Customer List</h2>
    <a href="{{ route('customers.create') }}" class="btn btn-primary mb-3">+ Add Customer</a>

    <div class="container mb-3">
            <form action="{{ route('customers.index') }}" method="GET" class="row g-2">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" 
                        placeholder="Search customer..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">-- Filter by Status --</option>
                        <option value="Lead" {{ request('status') == 'Lead' ? 'selected' : '' }}>Lead</option>
                        <option value="Quotation Sent" {{ request('status') == 'Quotation Sent' ? 'selected' : '' }}>Quotation Sent</option>
                        <option value="Negotiation" {{ request('status') == 'Negotiation' ? 'selected' : '' }}>Negotiation</option>
                        <option value="On Going Vessel Call" {{ request('status') == 'On Going Vessel Call' ? 'selected' : '' }}>On Going Vessel Call</option>
                        <option value="Pending Payment" {{ request('status') == 'Pending Payment' ? 'selected' : '' }}>Pending Payment</option>
                        <option value="Closing" {{ request('status') == 'Closing' ? 'selected' : '' }}>Closing</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="text" name="assigned_staff" class="form-control"
                        placeholder="Filter by Staff" value="{{ request('assigned_staff') }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                </div>
            </form>
        </div>

        <div class="row mb-3">
            <div class="col">
                <div class="border rounded p-2 text-center">
                    <small class="text-muted">Total Customers</small><br>
                    <span class="fw-semibold">{{ $summary['total_customers'] }}</span>
                </div>
            </div>
            <div class="col">
                <div class="border rounded p-2 text-center">
                    <small class="text-muted">Leads</small><br>
                    <span class="fw-semibold">{{ $summary['by_status']['Lead'] ?? 0 }}</span>
                </div>
            </div>
            <div class="col">
                <div class="border rounded p-2 text-center">
                    <small class="text-muted">Negotiation</small><br>
                    <span class="fw-semibold">{{ $summary['by_status']['Negotiation'] ?? 0 }}</span>
                </div>
            </div>
            <div class="col">
                <div class="border rounded p-2 text-center text-danger">
                    <small class="text-muted">Overdue</small><br>
                    <span class="fw-semibold">{{ $reminders['overdue'] }}</span>
                </div>
            </div>
            <div class="col">
                <div class="border rounded p-2 text-center text-warning">
                    <small class="text-muted">Due Today</small><br>
                    <span class="fw-semibold">{{ $reminders['today'] }}</span>
                </div>
            </div>
            <div class="col">
                <div class="border rounded p-2 text-center text-success">
                    <small class="text-muted">Upcoming (7 Days)</small><br>
                    <span class="fw-semibold">{{ $reminders['upcoming'] }}</span>
                </div>
            </div>
        </div>

    <table class="table table-bordered">
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
        @foreach($customers as $c)
        <tr>
            <td>{{ $c->name }}</td>
            <td>{{ $c->assigned_staff }}</td>
            <td>{{ $c->last_followup_date }}</td>
            <td>{{ $c->next_followup_date }}</td>
            <td>{{ $c->status }}</td>
            <td>{{ $c->currency }} {{ number_format($c->potential_revenue, 0) }}</td>
            <td>{{ $c->notes }}</td>
            <td>
                <a href="{{ route('customers.edit', $c->id) }}" class="btn btn-warning btn-sm">Edit</a>
                <form action="{{ route('customers.destroy', $c->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger btn-sm">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </table>

    <div class="d-flex justify-content-center mt-3">
        {{ $customers->links() }}
    </div>

</div>
@endsection
