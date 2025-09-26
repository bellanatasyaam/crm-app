@extends('layouts.app')

@section('content')
<div class="container py-4">

    <div class="d-flex justify-content-between mb-4">
        <h2 class="mb-0">Vessels for {{ $customer->name }}</h2>
        <a href="{{ route('vessels.create', ['customer_id' => $customer->id]) }}" class="btn btn-primary">
            + Add Vessel
        </a>
    </div>

    @if($vessels->isEmpty())
        <div class="alert alert-info">
            No vessels found for this customer.
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-hover table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Vessel Name</th>
                        <th>Port of Call</th>
                        <th>Status</th>
                        <th>Estimate Revenue</th>
                        <th>Currency</th>
                        <th>Description</th>
                        <th>Remark</th>
                        <th>Last Contact</th>
                        <th>Next Follow-Up</th>
                        <th>Assigned Staff</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($vessels as $vessel)
                        <tr>
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
                            <td>{{ $vessel->currency }}</td>
                            <td>{{ $vessel->description }}</td>
                            <td>{{ $vessel->remark ?? '-' }}</td>
                            <td>{{ $vessel->last_contact ?? '-' }}</td>
                            <td>{{ $vessel->next_follow_up ?? '-' }}</td>
                            <td>{{ $vessel->assignedStaff?->name ?? '-' }}</td>
                            <td class="d-flex gap-1">
                                <a href="{{ route('vessels.index', $c->id) }}" class="btn btn-info btn-sm">
                                    View Vessels
                                </a>
                                <a href="{{ route('vessels.edit', $vessel->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                
                                <form action="{{ route('vessels.destroy', $vessel->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm">Delete</button>
                                </form>

                                <a href="{{ route('vessels.show', $vessel->id) }}" class="btn btn-secondary btn-sm">View Logs</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

</div>
@endsection
