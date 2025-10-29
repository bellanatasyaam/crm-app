@extends('layouts.app')

@section('title', 'Daftar Vessels')

@section('content')
<div class="container py-4">

<style>
    .container { max-width: 100% !important; }
    body { background: #f5f6fa; font-family: 'Poppins', sans-serif; }

    /* === HEADER === */
    .dashboard-header {
        background: linear-gradient(90deg, #0ea5e9 0%, #0284c7 100%);
        color: #fff;
        padding: 15px 20px;
        border-radius: 12px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        margin-bottom: 25px;
    }

    .dashboard-header h2 {
        font-size: 20px;
        font-weight: 600;
        margin: 0;
    }

    .btn {
        border-radius: 8px;
        transition: 0.2s ease-in-out;
    }

    .btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 3px 6px rgba(0,0,0,0.15);
    }

    /* === TABLE === */
    .table-container {
        background: #fff;
        border-radius: 14px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        overflow: hidden;
        padding: 0;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
    }

    thead {
        background: linear-gradient(90deg, #0284c7 0%, #0ea5e9 100%);
        color: #fff;
    }

    th, td {
        padding: 12px 16px;
        text-align: left;
        border-bottom: 1px solid #e2e8f0;
    }

    th {
        font-weight: 600;
        font-size: 14px;
    }

    tbody tr:hover {
        background: #f1f5f9;
    }

    .badge {
        font-size: 12px;
        border-radius: 6px;
        padding: 4px 8px;
    }

    .alert { border-radius: 8px; }
</style>

<!-- Header -->
<div class="dashboard-header d-flex justify-content-between align-items-center">
    <h2 class="mb-0">🚢 Daftar Vessels</h2>
    <div class="d-flex gap-2">
        <a href="{{ route('vessels.create') }}" class="btn btn-light btn-sm text-primary fw-semibold">
            + Tambah Vessel
        </a>
        <a href="{{ route('dashboard') }}" class="btn btn-outline-light btn-sm">
            Kembali
        </a>
    </div>
</div>

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show mt-2" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if($vessels->isEmpty())
    <div class="alert alert-info mt-3">Tidak ada data vessel ditemukan.</div>
@else
<div class="table-container mt-3">
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Customer</th>
                <th>Vessel Name</th>
                <th>IMO</th>
                <th>Port</th>
                <th>Staff</th>
                <th>Status</th>
                <th>Revenue</th>
                <th>Last Contact</th>
                <th>Next FU</th>
                <th>Remark</th>
                <th style="width: 120px;">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($vessels as $index => $vessel)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $vessel->customer?->name ?? '—' }}</td>
                <td>{{ $vessel->vessel_name }}</td>
                <td>{{ $vessel->imo_number ?? '—' }}</td>
                <td>{{ $vessel->port_of_call ?? '—' }}</td>
                <td>{{ $vessel->assignedStaff?->name ?? '—' }}</td>
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
                <td>{{ number_format($vessel->estimate_revenue, 0) }} {{ $vessel->currency ?? '' }}</td>
                <td>{{ $vessel->last_contact ?? '—' }}</td>
                <td>{{ $vessel->next_follow_up ?? '—' }}</td>
                <td>{{ $vessel->remark ?? '—' }}</td>
                <td>
                    @if($vessel->assigned_staff_id == auth()->id() || auth()->user()->role == 'super_admin')
                        <a href="{{ route('vessels.edit', $vessel->id) }}" class="btn btn-sm btn-warning text-white">Edit</a>
                        <a href="{{ route('vessels.show', $vessel->id) }}" class="btn btn-sm btn-info text-white">Detail</a>
                        <form action="{{ route('vessels.destroy', $vessel->id) }}" method="POST" style="display:inline;">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus?')">Del</button>
                        </form>
                    @else
                        <a href="{{ route('vessels.show', $vessel->id) }}" class="btn btn-sm btn-info text-white">Detail</a>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="d-flex justify-content-center mt-3">
    {{ $vessels->links() }}
</div>
@endif

</div>
@endsection
