@extends('layouts.app')
@section('content')
<div class="container py-4">
    <h2 class="mb-4">Customer Detail</h2>

    <div class="card mb-4">
        <div class="card-body">
            <p><strong>Name:</strong> {{ $customer->name }}</p>
            <p><strong>Assigned Staff:</strong> {{ $customer->assigned_staff }}</p>
            <p><strong>Last Contact Date:</strong> {{ $customer->last_followup_date }}</p>
            <p><strong>Next Follow-Up:</strong> {{ $customer->next_followup_date }}</p>
        </div>
    </div>

    <h4 class="mb-3">Vessels</h4>
    <a href="{{ route('customers.vessels.create', $customer->id) }}" class="btn btn-primary mb-3">+ Add Vessel</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Vessel Name</th>
                <th>Status</th>
                <th>Potential Revenue</th>
                <th>Next Follow-Up</th>
            </tr>
        </thead>
        <tbody>
            @forelse($customer->vessels as $vessel)
                <tr>
                    <td>{{ $vessel->vessel_name }}</td>
                    <td>{{ $vessel->status }}</td>
                    <td>{{ $vessel->currency }} {{ number_format($vessel->potential_revenue, 0) }}</td>
                    <td>{{ $vessel->next_followup_date }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">No vessels found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <a href="{{ route('customers.index') }}" class="btn btn-secondary">Back</a>
</div>
@endsection
