@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Add Vessel</h2>

    <form action="{{ $customer 
        ? route('customers.vessels.store', $customer->id) 
        : route('vessels.store') }}" method="POST">
    @csrf
    
        {{-- Assigned Staff --}}
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

        {{-- Customer (optional) --}}
        <div class="mb-3">
            <label class="form-label">Customer (optional)</label>
            <select name="customer_id" class="form-select">
                <option value="">-- No Customer --</option>
                @foreach($customers as $cust)
                    <option value="{{ $cust->id }}">{{ $cust->name }}</option>
                @endforeach
            </select>
        </div>

        {{-- Vessel Name --}}
        <div class="mb-3">
            <label class="form-label">Vessel Name</label>
            <input type="text" name="vessel_name" class="form-control" required>
        </div>

        {{-- Port of Call (optional) --}}
        <div class="mb-3">
            <label class="form-label">Port of Call (optional)</label>
            <input type="text" name="port_of_call" class="form-control">
        </div>

        {{-- Status --}}
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
                    <option value="{{ $status }}">{{ $status }}</option>
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
            <textarea name="description" class="form-control" rows="3"></textarea>
        </div>

        {{-- Remark (optional) --}}
        <div class="mb-3">
            <label class="form-label">Remark (optional)</label>
            <input type="text" name="remark" class="form-control">
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

        {{-- Buttons --}}
        <button type="submit" class="btn btn-success">Save Vessel</button>
        <a href="{{ route('vessels.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
