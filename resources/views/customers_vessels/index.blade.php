@extends('layouts.app')

@section('content')
<div class="container py-4">

    <style>
        .container { max-width: 100% !important; }
        .dashboard-header {
            background: linear-gradient(90deg, #007bff 0%, #00b4d8 100%);
            color: #fff;
            padding: 15px 20px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .dashboard-header h2 { font-size: 18px; font-weight: 600; }

        .btn { border-radius: 8px; transition: 0.2s ease-in-out; }
        .btn:hover { transform: translateY(-1px); box-shadow: 0 2px 6px rgba(0,0,0,0.15); }

        table.custom-table { font-size: 13px; border-collapse: collapse; width: 100%; }
        table.custom-table th, table.custom-table td { padding: 6px 8px; text-align: center; vertical-align: middle; border: 1px solid #dee2e6; }
        table.custom-table th { background: #f8f9fa; font-weight: 600; }
        table.custom-table td { background: #fff; }
        table.custom-table td.vessel-col { white-space: normal; text-align: left; }

        .badge { font-size: 11px; margin: 1px; }

        .table-actions { display: flex; gap: 4px; flex-wrap: wrap; }
        .table-actions .btn { font-size: 11px; padding: 3px 6px; }

        .alert { border-radius: 8px; }
    </style>

    {{-- HEADER --}}
    <div class="dashboard-header d-flex justify-content-between align-items-center mb-3">
        <h2 class="mb-0">🚢 Customer & Vessel Dashboard</h2>
        <div class="d-flex gap-2">
            <a href="{{ route('customers_vessels.create') }}" class="btn btn-light btn-sm text-primary fw-semibold">+ New Customer Vessel</a>
            <a href="{{ route('dashboard') }}" class="btn btn-outline-light btn-sm">Back to Master Menu</a>
        </div>
    </div>

    {{-- ALERT MESSAGE --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- TABLE --}}
    <div class="table-responsive">
        <table class="custom-table">
            <thead>
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
                @forelse ($customers as $customer)
                    <tr>
                        <td>{{ $customer->name }}</td>
                        <td>{{ $customer->email ?? '-' }}</td>
                        <td>{{ $customer->phone ?? '-' }}</td>
                        <td>{{ $customer->address ?? '-' }}</td>
                        {{-- VESSEL + EDIT --}}
                        <td class="vessel-col">
                            @forelse ($customer->customerVessels as $cv)
                                @if($cv->vessel)
                                    <div class="d-flex align-items-center mb-1">
                                        <span class="badge bg-info text-dark me-2">{{ $cv->vessel->name }}</span>
                                        <a href="{{ route('customers_vessels.edit', $cv->id) }}" class="btn btn-sm btn-primary">Edit</a>
                                    </div>
                                @endif
                            @empty
                                <em class="text-muted">No vessels</em>
                            @endforelse
                        </td>
                        {{-- ACTION --}}
                        <td class="table-actions">
                            <a href="{{ route('customers_vessels.show', $customer->id) }}" class="btn btn-info btn-sm">Detail</a>
                            <a href="{{ route('customers_vessels.create', ['customer_id' => $customer->id]) }}" class="btn btn-primary btn-sm">+ Vessel</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">No customer data found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- PAGINATION --}}
    <div class="d-flex justify-content-center mt-3">
        {{ $customers->links() }}
    </div>

</div>
@endsection
