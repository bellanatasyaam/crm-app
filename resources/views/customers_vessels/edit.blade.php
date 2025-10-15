@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2>Customer + Vessels Dashboard</h2>

    <div class="d-flex justify-content-between mb-3">
        <h5></h5>
        <div>
            <a href="{{ route('customers_vessels.create') }}" class="btn btn-primary">+ New Customer Vessel</a>
            <a href="{{ route('master.menu') }}" class="btn btn-secondary">Back to Master Menu</a>
        </div>
    </div>

    <table class="table table-bordered table-striped align-middle">
        <thead class="table-light">
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Address</th>
                <th>Vessels</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($customers as $customer)
            <tr>
                <td>{{ $customer->name }}</td>
                <td>{{ $customer->email }}</td>
                <td>{{ $customer->phone }}</td>
                <td>{{ $customer->address }}</td>
                <td>
                    @if ($customer->vessels->count() > 0)
                        @foreach ($customer->vessels as $vessel)
                            <span class="badge bg-info text-dark">{{ $vessel->name }}</span>
                        @endforeach
                    @else
                        <em>No vessels</em>
                    @endif
                </td>
                <td>
                    {{-- Hanya tampilkan tombol Edit kalau data ini dibuat oleh user yang login --}}
                    @if ($customer->assigned_staff_id == Auth::id())
                        <a href="{{ route('customers_vessels.edit', $customer->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    @endif

                    <a href="{{ route('customers_vessels.show', $customer->id) }}" class="btn btn-info btn-sm">Detail</a>
                    <a href="{{ route('customers_vessels.create', ['customer_id' => $customer->id]) }}" class="btn btn-primary btn-sm">+ Vessel</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection