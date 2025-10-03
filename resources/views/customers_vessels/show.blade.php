@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Customer Profile</h2>

    <div class="card mb-4">
        <div class="card-body">
            <h4>{{ $customer->name }}</h4>
            <p><strong>Email:</strong> {{ $customer->email }}</p>
            <p><strong>Phone:</strong> {{ $customer->phone }}</p>
            <p><strong>Address:</strong> {{ $customer->address }}</p>
        </div>
    </div>

    <h4>Vessels</h4>
    <a href="{{ route('customers_vessels.create', $customer->id) }}" class="btn btn-primary mb-3">+ Add Vessel</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Vessel Name</th>
                <th>IMO Number</th>
                <th>Flag</th>
                <th>Type</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($customer->vessels as $vessel)
                <tr>
                    <td>{{ $vessel->name }}</td>
                    <td>{{ $vessel->imo_number }}</td>
                    <td>{{ $vessel->flag }}</td>
                    <td>{{ $vessel->type }}</td>
                    <td>
                        <a href="{{ route('customers.vessels.edit', [$customer->id, $vessel->id]) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('customers.vessels.destroy', [$customer->id, $vessel->id]) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this vessel?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center">No vessels found.</td></tr>
            @endforelse
        </tbody>
    </table>
    <a href="{{ route('customers_vessels.index') }}" class="btn btn-secondary">Back to Customer List</a>
    <a href="{{ route('customers.index') }}" class="btn btn-secondary">Back to Marketing</a>
    <a href="{{ route('vessels.index') }}" class="btn btn-secondary">Back to Vessels List</a>
</div>
@endsection
