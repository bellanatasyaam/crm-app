@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Edit Customer Vessel</h2>

    <form action="{{ route('customers_vessels.update', $customerVessel->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row g-3">
            <!-- Pilih Customer -->
            <div class="col-md-6">
                <label class="form-label">Customer</label>
                <select name="company_id" class="form-select" required>
                    @foreach($companies as $customer)
                        <option value="{{ $customer->id }}" {{ $customerVessel->company_id == $customer->id ? 'selected' : '' }}>
                            {{ $customer->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Pilih Vessel -->
            <div class="col-md-6">
                <label class="form-label">Vessel</label>
                <select name="vessel_id" class="form-select" required>
                    @foreach($vessels as $vessel)
                        <option value="{{ $vessel->id }}" {{ $customerVessel->vessel_id == $vessel->id ? 'selected' : '' }}>
                            {{ $vessel->vessel_name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="row g-3 mt-3">
            <!-- Status -->
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
                        <option value="{{ $status }}" {{ old('status', $customerVessel->status) == $status ? 'selected' : '' }}>
                            {{ $status }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Potential Revenue -->
            <div class="col-md-6">
                <label class="form-label">Potential Revenue</label>
                <div class="input-group">
                    <input type="number" name="potential_revenue" class="form-control"
                        value="{{ old('potential_revenue', $customerVessel->potential_revenue) }}" required>
                    <select name="currency" class="form-select" style="max-width:120px;">
                        @foreach(['USD','IDR','SGD','EUR','MYR'] as $currency)
                            <option value="{{ $currency }}" {{ old('currency', $customerVessel->currency) == $currency ? 'selected' : '' }}>
                                {{ $currency }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="row g-3 mt-3">
            <!-- Tanggal Kontak -->
            <div class="col-md-6">
                <label class="form-label">Last Contact Date</label>
                <input type="date" name="last_followup_date" class="form-control"
                    value="{{ old('last_followup_date', $customerVessel->last_followup_date) }}">
            </div>
            <div class="col-md-6">
                <label class="form-label">Next Follow-Up</label>
                <input type="date" name="next_followup_date" class="form-control"
                    value="{{ old('next_followup_date', $customerVessel->next_followup_date) }}">
            </div>
        </div>

        <!-- Description -->
        <div class="form-group mt-3">
            <label>Description</label>
            <textarea name="description" class="form-control" rows="3">{{ old('description', $customerVessel->description) }}</textarea>
        </div>

        <!-- Remark -->
        <div class="form-group mt-3">
            <label>Remark</label>
            <textarea name="remark" class="form-control" rows="3" placeholder="Tambahkan catatan opsional (boleh kosong)">{{ old('remark', $customerVessel->remark) }}</textarea>
        </div>

        <div class="mt-4 d-flex gap-2">
            <button type="submit" class="btn btn-success">Update</button>
            <a href="{{ route('customers_vessels.index') }}" class="btn btn-secondary">Back</a>
        </div>
    </form>
</div>
@endsection
