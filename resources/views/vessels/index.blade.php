@extends('layouts.app')

@section('content')
<div class="container py-4">

    <style>
        .container { max-width: 100% !important; }

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
        table.custom-table td { background: #fff; }

        /* Kolom panjang */
        table.custom-table td.desc-col,
        table.custom-table td.remark-col {
            white-space: normal !important;
            word-break: break-word;
            max-width: 250px;
            text-align: left;
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

        /* Text ellipsis */
        .truncate {
            display: inline-block;
            max-width: 200px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            vertical-align: bottom;
        }

        .more-link {
            cursor: pointer;
            color: #0d6efd;
            font-size: 11px;
            margin-left: 5px;
        }
    </style>

    <!-- Header -->
    <div class="d-flex justify-content-between mb-3">
        <h2 class="mb-0" style="font-size:18px;">
            @if($customer)
                Vessels for {{ $customer->name }}
            @else
                Vessel List
            @endif
        </h2>

        <div class="d-flex gap-2">
            @if($customer)
                <a href="{{ route('customers.vessels.create', $customer->id) }}" class="btn btn-primary btn-sm">
                    + Add Vessel
                </a>
                <a href="{{ route('customers.index') }}" class="btn btn-secondary btn-sm">
                    Back to Customers
                </a>
            @else
                <a href="{{ route('vessels.create') }}" class="btn btn-primary btn-sm">
                    + Add Vessel
                </a>
                <a href="{{ route('dashboard') }}" class="btn btn-secondary btn-sm">
                    Back to Master Menu
                </a>
            @endif
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($vessels->isEmpty())
        <div class="alert alert-info">No vessels found.</div>
    @else
        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>Description</th>
                        <th>Vessel Name</th>
                        <th>Port of Call</th>
                        <th>Status</th>
                        <th>Revenue</th>
                        <th>Currency</th>
                        <th>Remark</th>
                        <th>Last Contact</th>
                        <th>Next FU</th>
                        <th>Staff</th>
                        @unless($customer)
                            <th>Customer</th>
                        @endunless
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($vessels as $vessel)
                    <tr>
                        {{-- Description kolom panjang --}}
                        <td class="desc-col" title="{{ $vessel->description }}">
                            @if($vessel->description)
                                <span class="truncate">{{ $vessel->description }}</span>
                                <span class="more-link" onclick="toggleDesc(this)">More</span>
                                <span class="full-text d-none">{{ $vessel->description }}</span>
                            @else
                                -
                            @endif
                        </td>

                        <td>{{ $vessel->vessel_name }}</td>
                        <td>{{ $vessel->port_of_call ?? '-' }}</td>
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
                        <td>{{ number_format($vessel->estimate_revenue, 0) }}</td>
                        <td>{{ $vessel->currency ?? '-' }}</td>
                        <td class="remark-col" title="{{ $vessel->remark }}">
                            {{ \Illuminate\Support\Str::limit($vessel->remark, 30) }}
                        </td>
                        <td>{{ $vessel->last_contact ?? '-' }}</td>
                        <td>{{ $vessel->next_follow_up ?? '-' }}</td>
                        <td>{{ $vessel->assignedStaff?->name ?? '-' }}</td>
                        @unless($customer)
                            <td>{{ $vessel->customer?->name ?? '-' }}</td>
                        @endunless
                        <td>
                            <div class="table-actions">
                                @if($customer)
                                    <a href="{{ route('customers.vessels.edit', [$customer->id, $vessel->id]) }}" class="btn btn-warning btn-sm">Edit</a>
                                    <a href="{{ route('customers.vessels.show', [$customer->id, $vessel->id]) }}" class="btn btn-info btn-sm">Detail</a>
                                    <form action="{{ route('customers.vessels.destroy', [$customer->id, $vessel->id]) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Del</button>
                                    </form>
                                @else
                                    <a href="{{ route('vessels.edit', $vessel->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                    <a href="{{ route('vessels.show', $vessel->id) }}" class="btn btn-info btn-sm">Detail</a>
                                    <form action="{{ route('vessels.destroy', $vessel->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Del</button>
                                    </form>
                                @endif
                            </div>
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

{{-- JS Expandable --}}
<script>
function toggleDesc(el) {
    let row = el.closest('td');
    let trunc = row.querySelector('.truncate');
    let full = row.querySelector('.full-text');
    if (full.classList.contains('d-none')) {
        trunc.style.display = 'none';
        full.classList.remove('d-none');
        el.innerText = "Less";
    } else {
        trunc.style.display = 'inline-block';
        full.classList.add('d-none');
        el.innerText = "More";
    }
}
</script>
@endsection
