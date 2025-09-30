@extends('layouts.app')

@section('content')
<div class="container py-4">

    <style>
        /* Container full width biar tabel ga kepotong */
        .container {
            max-width: 100% !important;
        }

        /* Style tabel */
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

        /* Kolom panjang boleh turun */
        table.custom-table td:nth-child(9),   /* Description */
        table.custom-table td:nth-child(11) { /* Remark */
            white-space: normal !important;
            word-break: break-word;
        }

        /* Tombol aksi */
        .table-actions {
            display: flex;
            gap: 3px;
            justify-content: center;
        }
        .table-actions .btn {
            font-size: 11px;
            padding: 2px 5px;
        }

        /* Batas teks kolom */
        .table td {
            max-width: 200px;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    </style>

    <!-- Header -->
    <div class="d-flex justify-content-between mb-3">
        <h2 class="mb-0" style="font-size:18px;">Customer Dashboard</h2>
        <div class="d-flex gap-2">
            <a href="{{ route('customers.print') }}" class="btn btn-success btn-sm" target="_blank">
                <i class="fa fa-print"></i> Print Report
            </a>
            <a href="{{ route('customers.create') }}" class="btn btn-primary btn-sm">
                + Add Customer
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
                    <th>Staff</th>
                    <th>Last Contact</th>
                    <th>Next FU</th>
                    <th>Status</th>
                    <th>Revenue</th>
                    <th>Description</th>
                    <th>Vessels</th>
                    <th>Remark</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($customers as $c)
                <tr>
                    <td>{{ $c->name }}</td>
                    <td>{{ $c->email ?? '-' }}</td>
                    <td>{{ $c->phone ?? '-' }}</td>
                    <td>{{ $c->assigned_staff }}</td>
                    <td>{{ $c->last_followup_date ?? '-' }}</td>
                    <td>{{ $c->next_followup_date ?? '-' }}</td>
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
                                'Done / Closing'   => 'badge bg-success',
                            ];
                        @endphp
                        <span class="{{ $statusColors[$c->status] ?? 'badge bg-light text-dark' }}">
                            {{ $c->status }}
                        </span>
                    </td>
                    <td>{{ $c->currency }} {{ number_format($c->potential_revenue, 0) }}</td>
                    <td title="{{ $c->description }}">
                        {{ \Illuminate\Support\Str::limit($c->description, 20) ?? '-' }}
                    </td>
                    <td>
                        @forelse($c->vessels as $v)
                            <span class="badge bg-info text-dark">{{ $v->vessel_name }}</span>
                        @empty
                            -
                        @endforelse
                    </td>
                    <td title="{{ $c->remark }}">
                        {{ \Illuminate\Support\Str::limit($c->remark, 20) }}
                    </td>
                    <td>
                        <div class="table-actions">
                            @can('update', $c)
                                @if(Route::has('customers.edit'))
                                    <a href="{{ route('customers.edit', $c->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                @endif

                                @if(Route::has('customers.show'))
                                    <a href="{{ route('customers.show', $c->id) }}" class="btn btn-info btn-sm">Detail</a>
                                @endif
                            @endcan

                            @can('delete', $c)
                                @if(Route::has('customers.destroy'))
                                    <form action="{{ route('customers.destroy', $c->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm">Del</button>
                                    </form>
                                @endif
                            @endcan

                            <a href="{{ route('customers.print_single', $c->id) }}" target="_blank" class="btn btn-secondary btn-sm">
                                🖨
                            </a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-3">
        {{ $customers->links() }}
    </div>

</div>
@endsection
