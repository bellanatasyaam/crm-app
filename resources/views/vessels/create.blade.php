@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Add Vessel for {{ $customer->name }}</h2>

    <form action="{{ route('customers.vessels.store', $customer->id) }}" method="POST">
        @csrf
        <input type="hidden" name="customer_id" value="{{ $customer->id }}">

        <div class="mb-3">
            <label class="form-label">Vessel Name</label>
            <input type="text" name="vessel_name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Port of Call (optional)</label>
            <input type="text" name="port_of_call" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select" required>
                <option value="Follow up">Follow up</option>
                <option value="On progress">On progress</option>
                <option value="Request">Request</option>
                <option value="Waiting approval">Waiting approval</option>
                <option value="Approve">Approve</option>
                <option value="On going">On going</option>
                <option value="Quotation send">Quotation send</option>
                <option value="Done / Closing">Done / Closing</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Estimate Revenue</label>
            <input type="number" step="0.01" name="estimate_revenue" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Currency</label>
            <select name="currency" class="form-control">
                @foreach(['USD','IDR','SGD','EUR','MYR'] as $currency)
                    <option value="{{ $currency }}" 
                        {{ (isset($customer) && $customer->currency == $currency) ? 'selected' : '' }}>
                        {{ $currency }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="3"></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Remark (optional)</label>
            <input type="text" name="remark" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Last Contact</label>
            <input type="date" name="last_contact" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Next Follow Up</label>
            <input type="date" name="next_follow_up" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Assigned Staff</label>
            <select name="assigned_staff" class="form-select" required>
                <option value="">-- Select Staff --</option>
                @foreach($staffs as $staff)
                    @if($staff->name !== 'Super Admin')
                        <option value="{{ $staff->name }}">{{ $staff->name }}</option>
                    @endif
            @endforeach
        </select>
        </div>

        <button type="submit" class="btn btn-success">Save Vessel</button>
        <a href="{{ route('customers.vessels.index', $customer->id) }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
