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
        <h2 class="mb-0">Vessel List</h2>
        <div class="d-flex gap-2">
            <a href="{{ route('customers_vessels.create') }}" class="btn btn-light btn-sm text-primary fw-semibold">+ New Vessel</a>
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
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($companies as $customer)
                    <tr>
                        <td>{{ $customer->name }}</td>
                        <td>{{ $customer->email ?? '-' }}</td>
                        <td>{{ $customer->phone ?? '-' }}</td>
                        <td>{{ $customer->address ?? '-' }}</td>

                        {{-- VESSEL --}}
                        <td class="vessel-col">
                            @forelse ($customer->customerVessels as $cv)
                                @if($cv->vessel)
                                    <div class="d-flex align-items-center mb-1">
                                        <span class="badge bg-info text-dark me-2">{{ $cv->vessel->name }}</span>
                                        <a href="{{ route('customers_vessels.show', $cv->vessel->id) }}" class="btn btn-sm btn-secondary ms-2">Lihat Detail</a>

                                        {{-- Edit hanya untuk super_admin atau assigned staff --}}
                                        @if(auth()->user()->role === 'super_admin' || $customer->assigned_staff_id === auth()->id())
                                            <a href="{{ route('customers_vessels.edit', $cv->id) }}" class="btn btn-sm btn-primary ms-2">Edit</a>
                                        @endif
                                    </div>
                                @endif
                            @empty
                                <em class="text-muted">No vessels</em>
                            @endforelse
                        </td>

                        {{-- STATUS --}}
                        <td class="vessel-col">
                            @forelse ($customer->customerVessels as $cv)
                                @if($cv->status)
                                    <span class="badge bg-secondary mb-1">{{ $cv->status }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            @empty
                                <em class="text-muted">No vessels</em>
                            @endforelse
                        </td>

                        {{-- ACTION --}}
                        <td class="table-actions">
                            {{-- Semua bisa lihat detail --}}
                            <a href="{{ route('customers_vessels.show', $customer->id) }}" class="btn btn-sm btn-info">Detail</a>

                            {{-- Hanya assigned staff & super_admin yang bisa edit/delete --}}
                            @if(auth()->user()->role === 'super_admin' || $customer->assigned_staff_id === auth()->id())
                                <a href="{{ route('customers_vessels.edit', $customer->id) }}" class="btn btn-sm btn-primary">Edit</a>
                                <form action="{{ route('customers_vessels.destroy', $customer->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                </form>

                                {{-- Tombol tambah vessel --}}
                                <a href="{{ route('customers_vessels.create', ['company_id' => $customer->id]) }}" class="btn btn-primary btn-sm">+ Vessel</a>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">No customer data found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- PAGINATION --}}
    <div class="d-flex justify-content-center mt-3">
        {{ $companies->links() }}
    </div>

</div>
@endsection
