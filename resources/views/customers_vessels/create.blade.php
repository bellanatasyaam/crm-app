@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2>Add Vessel for {{ $customer->name }}</h2>

    <form action="{{ route('customers.vessels.store', $customer->id) }}" method="POST">
        @csrf

        {{-- Hidden customer_id --}}
        <input type="hidden" name="customer_id" value="{{ $customer->id }}">

        {{-- Assigned Staff --}}
        <div class="mb-3">
            <label class="form-label">Assigned Staff</label>
            <select name="assigned_staff_id" class="form-select" required>
                <option value="">-- Select Staff --</option>
                @foreach($staffs as $staff)
                    <option value="{{ $staff->id }}">{{ $staff->name }}</option>
                @endforeach
            </select>
        </div>

        {{-- Vessel Name --}}
        <div class="mb-3">
            <label class="form-label">Vessel Name</label>
            <input type="text" name="vessel_name" class="form-control" required>
        </div>

        {{-- Port of Call --}}
        <div class="mb-3">
            <label class="form-label">Port of Call</label>
            <select name="port_of_call" class="form-select">
                <option value="">-- Select Port --</option>
                @foreach($ports as $port)
                    <option value="{{ $port }}">{{ $port }}</option>
                @endforeach
            </select>
        </div>

        {{-- Potential Revenue + Currency --}}
        <div class="mb-3">
            <label class="form-label">Potential Revenue</label>
            <div class="input-group">
                <input type="number" name="potential_revenue" class="form-control" required>
                <select name="currency" class="form-select" style="max-width:120px;">
                    @foreach(['USD','IDR','SGD','EUR','MYR'] as $currency)
                        <option value="{{ $currency }}">{{ $currency }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- Description --}}
        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control"></textarea>
        </div>

        {{-- Remark --}}
        <div class="mb-3">
            <label class="form-label">Remark</label>
            <textarea name="remark" class="form-control"></textarea>
        </div>

        {{-- Status --}}
        <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
                @php
                    $statuses = [
                        'Follow up', 'On progress', 'Request', 'Waiting approval',
                        'Approve', 'On going', 'Quotation send', 'Done / Closing'
                    ];
                @endphp
                @foreach($statuses as $status)
                    <option value="{{ $status }}">{{ $status }}</option>
                @endforeach
            </select>
        </div>

        {{-- Last Contact --}}
        <div class="mb-3">
            <label class="form-label">Last Contact</label>
            <input type="date" name="last_contact" class="form-control">
        </div>

        {{-- Next Follow Up --}}
        <div class="mb-3">
            <label class="form-label">Next Follow Up</label>
            <input type="date" name="next_follow_up" class="form-control">
        </div>

        <button type="submit" class="btn btn-success">Save</button>
        <a href="{{ route('customers.show', $customer->id) }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
