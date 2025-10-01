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
        <h2 class="mb-0" style="font-size:18px;">Marketing Dashboard</h2>
        <div class="d-flex gap-2">
            <a href="{{ route('customers.print') }}" class="btn btn-success btn-sm" target="_blank">
                <i class="fa fa-print"></i> Print Report
            </a>
            <a href="{{ route('customers.create') }}" class="btn btn-primary btn-sm">
                + Add Activity
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
                    <th>Description</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Staff</th>
                    <th>Last Contact</th>
                    <th>Next FU</th>
                    <th>Status</th>
                    <th>Revenue</th>
                    <th>Vessels</th>
                    <th>Remark</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($customers as $c)
                <tr>
                    {{-- Description paling depan --}}
                    <td class="desc-col" title="{{ $c->description }}">
                        @if($c->description)
                            <span class="truncate">{{ $c->description }}</span>
                            <span class="more-link" onclick="toggleDesc(this)">More</span>
                            <span class="full-text d-none">{{ $c->description }}</span>
                        @else
                            -
                        @endif
                    </td>

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
                    <td>
                        @forelse($c->vessels as $v)
                            <span class="badge bg-info text-dark">{{ $v->vessel_name }}</span>
                        @empty
                            -
                        @endforelse
                    </td>
                    <td class="remark-col" title="{{ $c->remark }}">
                        {{ \Illuminate\Support\Str::limit($c->remark, 20) }}
                    </td>
                    <td>
                        <div class="table-actions">
                            @can('update', $c)
                                <a href="{{ route('marketing.edit', $c->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            @endcan

                            @can('view', $c)
                                <a href="{{ route('marketing.show', $c->id) }}" class="btn btn-info btn-sm">Detail</a>
                            @endcan

                            @can('delete', $c)
                                <form action="{{ route('marketing.destroy', $c->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm">Del</button>
                                </form>
                            @endcan
                            <a href="{{ route('customers.print_single', $c->id) }}" target="_blank" class="btn btn-secondary btn-sm">🖨</a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-center mt-3">
        {{ $customers->links() }}
    </div>

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
