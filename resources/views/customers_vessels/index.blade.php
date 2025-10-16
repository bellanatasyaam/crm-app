@extends('layouts.app')

@section('content')
<div class="container py-4">

    <style>
        /* --- Global --- */
        .container {
            max-width: 100% !important;
        }

        body {
            background: #f5f6fa;
        }

        h2 {
            font-weight: 600;
            color: #2c3e50;
        }

        /* --- Header --- */
        .dashboard-header {
            background: linear-gradient(90deg, #007bff 0%, #00b4d8 100%);
            color: #fff;
            padding: 15px 20px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .dashboard-header h2 {
            font-size: 18px;
            font-weight: 600;
        }

        /* --- Button group --- */
        .btn {
            border-radius: 8px;
            transition: 0.2s ease-in-out;
        }
        .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 6px rgba(0,0,0,0.15);
        }

        /* --- Table container --- */
        .table-wrapper {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            padding: 15px;
            margin-top: 20px;
        }

        /* --- Custom Table --- */
        table.custom-table {
            font-size: 13px;
            border-collapse: collapse;
            width: 100%;
        }

        table.custom-table th {
            background: #f1f3f5;
            font-weight: 600;
            color: #495057;
            padding: 10px;
            text-align: center;
            border-bottom: 2px solid #dee2e6;
        }

        table.custom-table td {
            padding: 8px;
            text-align: center;
            vertical-align: middle;
            border-bottom: 1px solid #eee;
            background: #fff;
        }

        table.custom-table tr:hover {
            background-color: #f8f9fa;
        }

        /* --- Vessels badge --- */
        .badge {
            font-size: 11px;
            border-radius: 6px;
            padding: 4px 7px;
        }

        /* --- Table actions --- */
        .table-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 4px;
            justify-content: center;
        }

        .table-actions .btn {
            font-size: 11px;
            padding: 3px 6px;
        }

        /* --- Assigned text --- */
        .assigned-text {
            font-size: 11px;
            color: #6c757d;
        }
    </style>

    <!-- Header -->
    <div class="dashboard-header d-flex justify-content-between align-items-center mb-3">
        <h2 class="mb-0">🚢 Customer + Vessels Dashboard</h2>
        <div class="d-flex gap-2">
            <a href="{{ route('customers_vessels.create') }}" class="btn btn-light btn-sm text-primary fw-semibold">
                + New Customer Vessel
            </a>
            <a href="{{ route('dashboard') }}" class="btn btn-outline-light btn-sm">
                Back to Master Menu
            </a>
        </div>
    </div>

    <!-- Customer Table -->
    <div class="table-wrapper table-responsive">
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
                    <td><strong>{{ $c->name }}</strong></td>
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
                            @can('update', $c)
                                <a href="{{ route('customers.edit', $c->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            @else
                                <small class="text-muted" style="font-size:11px;">
                                    Assigned to: {{ $c->assignedStaff->name ?? 'Unassigned' }}
                                </small>
                            @endcan
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
