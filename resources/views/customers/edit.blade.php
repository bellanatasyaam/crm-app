@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Edit Customer</h2>

    <form action="{{ route('customers.update', $customer->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Customer Name</label>
                <input type="text" name="name" class="form-control" value="{{ $customer->name }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Assigned Staff</label>
                <input type="text" name="assigned_staff" class="form-control" value="{{ $customer->assigned_staff }}" required>
            </div>
        </div>

        <div class="row g-3 mt-2">
            <div class="col-md-6">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="{{ $customer->email }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Phone</label>
                <input type="text" name="phone" class="form-control" value="{{ $customer->phone }}" required>
            </div>
        </div>

        <div class="row g-3 mt-2">
            <div class="col-md-6">
                <label class="form-label">Last Contact Date</label>
                <input type="date" name="last_followup_date" class="form-control" value="{{ $customer->last_followup_date }}">
            </div>
            <div class="col-md-6">
                <label class="form-label">Next Follow-Up</label>
                <input type="date" name="next_followup_date" class="form-control" value="{{ $customer->next_followup_date }}">
            </div>
        </div>

        <div class="row g-3 mt-2">
            <div class="col-md-6">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
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
                        <option value="{{ $status }}" {{ $customer->status == $status ? 'selected' : '' }}>
                            {{ $status }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Potential Revenue</label>
                <div class="input-group">
                    <input type="number" name="potential_revenue" class="form-control" value="{{ $customer->potential_revenue }}" required>
                    <select name="currency" class="form-select" style="max-width:120px;">
                        @foreach(['USD','IDR','SGD','EUR', 'MYR'] as $currency)
                            <option value="{{ $currency }}" {{ $customer->currency == $currency ? 'selected' : '' }}>
                                {{ $currency }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- Vessels (multi select) -->
        <div class="mt-3">
            <label class="form-label">Assign Vessels</label>
            <select name="vessels[]" multiple class="form-control">
                @foreach($vessels as $vessel)
                    <option value="{{ $vessel->id }}" {{ $vessel->customer_id == $customer->id ? 'selected' : '' }}>
                        {{ $vessel->vessel_name }}
                    </option>
                @endforeach
            </select>
            <small class="text-muted">*Hold CTRL (Windows) / CMD (Mac) untuk pilih lebih dari satu vessel</small>
        </div>

        <!-- Description -->
       <div class="form-group">
            <label>Description</label>
            <textarea name="description" class="form-control">{{ old('description', $customer->description) }}</textarea>
        </div>

        <!-- Remark -->
        <div class="mb-3">
            <label for="remark" class="form-label">Remark</label>
            <textarea name="remark" id="remark" class="form-control" rows="3" placeholder="Tambahkan catatan opsional (boleh kosong)">{{ old('remark', $customer->remark ?? '') }}</textarea>
        </div>

        <div class="mt-4 d-flex gap-2">
            <button type="submit" class="btn btn-success">Update</button>
            <a href="{{ route('customers.index') }}" class="btn btn-secondary">Back</a>
            <a href="{{ route('customers.vessels.index', $customer->id) }}" class="btn btn-info">
                View Vessels
            </a>
        </div>
        
    </form>
</div>
@endsection
