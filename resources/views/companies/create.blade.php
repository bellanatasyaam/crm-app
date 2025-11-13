@extends('layouts.app')

@section('content')
<div class="container py-4">

    <style>
        .container { max-width: 100% !important; }

        /* === HEADER === */
        .dashboard-header {
            background: linear-gradient(90deg, #007bff 0%, #00b4d8 100%);
            color: #fff;
            padding: 15px 20px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .dashboard-header h2 {
            font-size: 18px;
            font-weight: 600;
        }

        /* === FORM STYLING === */
        .form-section {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.08);
            padding: 25px;
            margin-bottom: 25px;
        }

        label.form-label {
            font-weight: 500;
            color: #333;
        }

        input.form-control, select.form-select, textarea.form-control {
            border-radius: 8px;
            border: 1px solid #ced4da;
            transition: 0.2s;
        }
        input.form-control:focus, select.form-select:focus, textarea.form-control:focus {
            border-color: #00b4d8;
            box-shadow: 0 0 0 0.2rem rgba(0,180,216,0.25);
        }

        h4.section-title {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 15px;
            border-bottom: 2px solid #e9ecef;
            padding-bottom: 5px;
            color: #007bff;
        }

        /* === BUTTONS === */
        .btn {
            border-radius: 8px;
            transition: 0.2s ease-in-out;
        }
        .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 6px rgba(0,0,0,0.15);
        }
    </style>

    <!-- === HEADER === -->
    <div class="dashboard-header d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Create New Customer</h2>
        <a href="{{ route('companies.index') }}" class="btn btn-outline-light btn-sm">
            ← Back to List
        </a>
    </div>

    {{-- === Error Handling === --}}
    @if ($errors->any())
        <div class="alert alert-danger mb-4">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- === FORM === --}}
    <form action="{{ route('companies.store') }}" method="POST">
        @csrf

        {{-- SECTION 1 --}}
        <div class="form-section">
            <h4 class="section-title">🧾 Basic Information</h4>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Company Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Website</label>
                    <input type="url" name="website" class="form-control" placeholder="https://" value="{{ old('website') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tax ID / NPWP</label>
                    <input type="text" name="tax_id" class="form-control" value="{{ old('tax_id') }}">
                </div>
            </div>
        </div>

        {{-- SECTION 2 --}}
        <div class="form-section">
            <h4 class="section-title">🏷️ Classification & Tier</h4>
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Customer Type</label>
                    <select name="type" class="form-select" required>
                        <option value="prospect">Prospect</option>
                        <option value="client">Client</option>
                        <option value="vendor">Vendor</option>
                        <option value="partner">Partner</option>
                        <option value="customer">Customer</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Industry</label>
                    <input type="text" name="industry" class="form-control" value="{{ old('industry') }}" placeholder="e.g., Shipping, Mining">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Customer Tier</label>
                    <select name="customer_tier" class="form-select">
                        <option value="regular" {{ old('customer_tier', 'regular') == 'regular' ? 'selected' : '' }}>Regular</option>
                        <option value="vip" {{ old('customer_tier') == 'vip' ? 'selected' : '' }}>VIP</option>
                        <option value="premium" {{ old('customer_tier') == 'premium' ? 'selected' : '' }}>Premium</option>
                    </select>
                </div>
                <select name="status" class="form-select">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
        </div>

        {{-- SECTION 3 --}}
        <div class="form-section">
            <h4 class="section-title">📍 Location Details</h4>
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label">Address</label>
                    <textarea name="address" class="form-control" rows="3">{{ old('address') }}</textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label">City</label>
                    <input type="text" name="city" class="form-control" value="{{ old('city') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Country</label>
                    <input type="text" name="country" class="form-control" value="{{ old('country', 'Indonesia') }}">
                </div>
            </div>
        </div>

        {{-- SECTION 4 --}}
        <div class="form-section">
            <h4 class="section-title">🗒️ Additional Info</h4>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Assigned Staff</label>
                    <select name="assigned_staff_id" class="form-select">
                        <option value="">-- Select Staff --</option>
                        @foreach($staffs as $id => $name)
                            <option value="{{ $id }}" {{ old('assigned_staff_id') == $id ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Assigned Staff Email</label>
                    <input type="email" name="assigned_staff_email" class="form-control" value="{{ old('assigned_staff_email', auth()->user()->email ?? '') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Last Follow Up Date</label>
                    <input type="date" name="last_followup_date" class="form-control" value="{{ old('last_followup_date') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Next Follow Up Date</label>
                    <input type="date" name="next_followup_date" class="form-control" value="{{ old('next_followup_date') }}">
                </div>
                <div class="col-12">
                    <label class="form-label">Remark</label>
                    <textarea name="remark" class="form-control" rows="3" placeholder="Additional notes, follow-up status, etc.">{{ old('remark') }}</textarea>
                </div>
            </div>
        </div>

        {{-- BUTTONS --}}
        <div class="d-flex justify-content-end gap-2 mt-3">
            <a href="{{ route('companies.index') }}" class="btn btn-secondary px-4">
                <i class="fas fa-arrow-left me-1"></i> Cancel
            </a>
            <button type="submit" class="btn btn-primary px-4">
                <i class="fas fa-save me-1"></i> Save Customer
            </button>
        </div>
    </form>
</div>
@endsection
