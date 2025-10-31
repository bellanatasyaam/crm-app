@extends('layouts.app')

@section('title', 'Daftar Vessels')

@section('content')
<div class="container py-4">

<style>
    .container { max-width: 100% !important; }
    body { background: #f5f6fa; font-family: 'Poppins', sans-serif; }

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

    .btn { border-radius: 8px; transition: 0.2s ease-in-out; }
    .btn:hover { transform: translateY(-1px); box-shadow: 0 3px 6px rgba(0,0,0,0.15); }

    .table-container {
        background: #fff;
        border-radius: 14px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        overflow: hidden;
        padding: 0;
    }

    table { width: 100%; border-collapse: collapse; font-size: 14px; }

    thead {
        background: linear-gradient(90deg, #0284c7 0%, #0ea5e9 100%);
        color: #fff;
    }

    th, td {
        padding: 12px 16px;
        text-align: left;
        border-bottom: 1px solid #e2e8f0;
        vertical-align: middle;
    }

    th { font-weight: 600; font-size: 14px; }
    tbody tr:hover { background: #f1f5f9; }
    .badge { font-size: 12px; border-radius: 6px; padding: 4px 8px; }
    .alert { border-radius: 8px; }

    /* === ACTION BUTTONS === */
    td:last-child {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 6px;
        white-space: nowrap;
    }

    td:last-child .btn {
        padding: 5px 10px;
        font-size: 13px;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        height: 32px;
    }

    td:last-child form {
        margin: 0;
    }
</style>

<div class="dashboard-header d-flex justify-content-between align-items-center">
    <h2 class="mb-0">🚢 Daftar Vessels</h2>
    <div class="d-flex gap-2">
        <a href="{{ route('vessels.create') }}" class="btn btn-light btn-sm text-primary fw-semibold">+ Tambah Vessel</a>
        <a href="{{ route('dashboard') }}" class="btn btn-outline-light btn-sm">Kembali</a>
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
                <th>Company</th>
                <th>Vessel Name</th>
                <th>IMO</th>
                <th>Call Sign</th>
                <th>Port of Call</th>
                <th>Vessel Type</th>
                <th>Flag</th>
                <th>Gross (GT)</th>
                <th>Net (NT)</th>
                <th>Year Built</th>
                <th>Status</th>
                <th style="width: 150px;">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($vessels as $index => $vessel)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $vessel->company?->name ?? '—' }}</td>
                <td>{{ $vessel->name ?? '—' }}</td>
                <td>{{ $vessel->imo_number ?? '—' }}</td>
                <td>{{ $vessel->call_sign ?? '—' }}</td>
                <td>{{ $vessel->port_of_call ?? '—' }}</td>
                <td>{{ $vessel->vessel_type ?? '—' }}</td>
                <td>{{ $vessel->flag ?? '—' }}</td>
                <td>{{ $vessel->gross_tonnage ?? '—' }}</td>
                <td>{{ $vessel->net_tonnage ?? '—' }}</td>
                <td>{{ $vessel->year_built ?? '—' }}</td>
                <td>
                    @php
                        $statusColors = [
                            'active' => 'badge bg-success',
                            'maintenance' => 'badge bg-warning text-dark',
                            'retired' => 'badge bg-secondary text-white',
                        ];
                    @endphp
                    <span class="{{ $statusColors[$vessel->status] ?? 'badge bg-light text-dark' }}">
                        {{ ucfirst($vessel->status ?? '-') }}
                    </span>
                </td>
                <td>
                    <a href="{{ route('vessels.show', $vessel->id) }}" class="btn btn-sm btn-info text-white">Detail</a>
                    @if(auth()->user()->role == 'super_admin' || $vessel->created_by == auth()->id())
                        <a href="{{ route('vessels.edit', $vessel->id) }}" class="btn btn-sm btn-warning text-white">Edit</a>
                        <form action="{{ route('vessels.destroy', $vessel->id) }}" method="POST">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus?')">Del</button>
                        </form>
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
