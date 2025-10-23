@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Edit Vessel: {{ $vessel->vessel_name }}</h2>

    <form action="{{ $vessel->company_id 
        ? route('companies.vessels.update', [$vessel->company_id, $vessel->id]) 
        : route('vessels.update', $vessel->id) }}" 
        method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Vessel Name</label>
            <input type="text" name="vessel_name" class="form-control" value="{{ $vessel->vessel_name }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Port of Call</label>
            <input type="text" name="port_of_call" class="form-control" value="{{ $vessel->port_of_call }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select" required>
                @foreach([
                    'Follow up',
                    'On progress',
                    'Request',
                    'Waiting approval',
                    'Approve',
                    'On going',
                    'Quotation send',
                    'Done / Closing'
                ] as $status)
                    <option value="{{ $status }}" {{ $vessel->status === $status ? 'selected' : '' }}>
                        {{ $status }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Estimate Revenue</label>
            <input type="number" step="0.01" name="estimate_revenue" class="form-control" value="{{ $vessel->estimate_revenue }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Currency</label>
            <input type="text" name="currency" class="form-control" value="{{ $vessel->currency }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="3">{{ $vessel->description }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Remark</label>
            <input type="text" name="remark" class="form-control" value="{{ $vessel->remark }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Last Contact</label>
            <input type="date" name="last_contact" class="form-control" value="{{ $vessel->last_contact }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Next Follow Up</label>
            <input type="date" name="next_follow_up" class="form-control" value="{{ $vessel->next_follow_up }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Assigned Staff</label>
            <select name="assigned_staff_id" class="form-select">
                <option value="">-- Select Staff --</option>
                @foreach($staffs as $user)
                    <option value="{{ $user->id }}" {{ $vessel->assigned_staff_id == $user->id ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-success">Update Vessel</button>
        <a href="{{ route('vessels.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
