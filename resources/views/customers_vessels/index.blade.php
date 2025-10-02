@extends('layouts.app')

@section('content')
<div class="container py-4">

    <style>
        /* Container full width */
        .container {
            max-width: 100% !important;
        }

        /* Tabel custom */
        table.custom-table {
            font-size: 13px;
            border-collapse: collapse;
            width: 100%;
        }

        table.custom-table th,
        table.custom-table td {
            padding: 5px 6px;
            text-align: center;
            vertical-align: middle;
            border: 1px solid #dee2e6;
            white-space: nowrap;
        }

        table.custom-table th {
            background: #f8f9fa;
            font-weight: 600;
        }

        table.custom-table td {
            background: #fff;
        }

        table.custom-table td:nth-child(5) {
            white-space: normal !important;
            word-break: break-word;
        }

        .table-actions {
            display: flex;
            gap: 3px;
            justify-content: center;
        }

        .table-actions .btn {
            font-size: 11px;
            padding: 2px 5px;
        }

        table td {
            max-width: 200px;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    </style>

    <!-- Header -->
    <div class="d-flex justify-content-between mb-3">
        <h2 class="mb-0" style="font-size:18px;">Customer + Vessels Dashboard</h2>
        <div class="d-flex gap-2">
            <a href="{{ route('customers_vessels.create') }}" class="btn btn-primary btn-sm">
                + New Customer Vessel
            </a>
            <a href="{{ route('dashboard') }}" class="btn btn-secondary btn-sm">
                Back to Master Menu
            </a>
        </div>
    </div>

    <!-- Customer Table -->
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
                @foreach($customers as $c)
                <tr>
                    <td>{{ $c->name }}</td>
                    <td>{{ $c->email ?? '-' }}</td>
                    <td>{{ $c->phone ?? '-' }}</td>
                    <td>{{ $c->address ?? '-' }}</td>
                    <td>
                        @forelse($c->vessels as $v)
                            <span class="badge bg-info text-dark">{{ $v->vessel_name }}</span>
                        @empty
                            <em>No vessels</em>
                        @endforelse
                    </td>
                    <td>
                        <div class="table-actions">
                            <a href="{{ route('customers.edit', $c->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            <a href="{{ route('customers.profile', $c->id) }}" class="btn btn-info btn-sm">Detail</a>
                            <a href="{{ route('customers.vessels.create', $c->id) }}" class="btn btn-primary btn-sm">+ Vessel</a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>
@endsection
