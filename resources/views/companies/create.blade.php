@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-5 text-2xl font-semibold text-gray-800">Create New Customer (Company Data)</h2>

    {{-- Tampilkan error validasi jika ada --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('companies.store') }}" method="POST" class="bg-white p-6 shadow-lg rounded-lg">
        @csrf

        {{-- Section 1: Basic Company Information --}}
        <h4 class="mb-3 text-lg font-medium border-b pb-1">Basic Information</h4>
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
                <input type="url" name="website" class="form-control" value="{{ old('website') }}" placeholder="https://">
            </div>
            <div class="col-md-4">
                <label class="form-label">Tax ID / NPWP</label>
                <input type="text" name="tax_id" class="form-control" value="{{ old('tax_id') }}">
            </div>
        </div>

        {{-- Section 2: Classification and Status --}}
        <h4 class="mb-3 text-lg font-medium border-b pb-1 mt-5">Classification & Tier</h4>
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Customer Type</label>
                <select name="type" class="form-select">
                    <option value="">-- Select Type --</option>
                    @foreach(['Customer', 'Principal', 'Vendor', 'Agent', 'Local Company'] as $type)
                        <option value="{{ $type }}" {{ old('type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Industry</label>
                <input type="text" name="industry" class="form-control" value="{{ old('industry') }}" placeholder="e.g., Shipping, Mining, Oil & Gas">
            </div>
            <div class="col-md-4">
                <label class="form-label">Customer Tier</label>
                <select name="customer_tier" class="form-select">
                    <option value="regular" {{ old('customer_tier', 'regular') == 'regular' ? 'selected' : '' }}>Regular</option>
                    <option value="vip" {{ old('customer_tier') == 'vip' ? 'selected' : '' }}>VIP</option>
                    <option value="premium" {{ old('customer_tier') == 'premium' ? 'selected' : '' }}>Premium</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
        </div>

        {{-- Section 3: Location Details --}}
        <h4 class="mb-3 text-lg font-medium border-b pb-1 mt-5">Location Details</h4>
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
                {{-- Country default to Indonesia based on model attribute --}}
                <input type="text" name="country" class="form-control" value="{{ old('country', 'Indonesia') }}">
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="mt-5 d-flex gap-2">
            <button type="submit" class="btn btn-primary px-4">
                <i class="fas fa-save me-1"></i> Save Customer
            </button>
            <a href="{{ route('companies.index') }}" class="btn btn-secondary px-4">
                <i class="fas fa-arrow-left me-1"></i> Back to List
            </a>
        </div>
    </form>
</div>
@endsection