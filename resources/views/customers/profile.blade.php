@extends('layouts.app')

@section('content')
<div class="container py-4">

    {{-- Bagian Profile Customer --}}
    <h2 class="mb-4">Customer Profile</h2>

    <div class="card mb-4">
        <div class="card-body">
            <p><strong>Name:</strong> {{ $customer->name }}</p>
            <p><strong>Email:</strong> {{ $customer->email }}</p>
            <p><strong>Phone:</strong> {{ $customer->phone }}</p>
            <p><strong>Address:</strong> {{ $customer->address }}</p>
        </div>
    </div>

    {{-- Bagian Vessels --}}
    <div class="d-flex justify-content-between mb-3">
        <h4>Vessels</h4>
        <a href="{{ route('customers.vessels.create', $customer->id) }}" class="btn btn-primary btn-sm">+ Add Vessel</a>
    </div>

    @if($customer->vessels->isEmpty())
        <div class="alert alert-info">No vessels for this customer.</div>
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Vessel Name</th>
                    <th>Port of Call</th>
                    <th>Status</th>
                    <th>Revenue</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($customer->vessels as $vessel)
                    <tr>
                        <td>{{ $vessel->vessel_name }}</td>
                        <td>{{ $vessel->port_of_call ?? '-' }}</td>
                        <td>{{ $vessel->status ?? '-' }}</td>
                        <td>{{ $vessel->estimate_revenue ?? 0 }} {{ $vessel->currency ?? '' }}</td>
                        <td>
                            <a href="{{ route('customers.vessels.edit', [$customer->id, $vessel->id]) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('customers.vessels.destroy', [$customer->id, $vessel->id]) }}" method="POST" style="display:inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"
                                    onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

</div>
@endsection
