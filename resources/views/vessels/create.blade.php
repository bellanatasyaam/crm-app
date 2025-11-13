@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4"><i class="fas fa-ship"></i> Add New Vessel Detail</h2>

    <form action="{{ $company 
        ? route('companies.vessels.store', $company->id) 
        : route('vessels.store') }}" method="POST">
    @csrf
    
        {{-- Company ID (Replaced Customer ID) --}}
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-primary text-white">
                Company & Basic Info
            </div>
            <div class="card-body">
                {{-- Customer / Company (Mapped to company_id in DB) --}}
                <div class="mb-3">
                    <label class="form-label font-weight-bold">Customer/Company (Optional)</label>
                    <select name="company_id" class="form-select form-control @error('company_id') is-invalid @enderror" 
                        {{ $company ? 'disabled' : '' }}>
                        <option value="">-- No Company --</option>
                        @foreach($companies as $cust)
                            <option value="{{ $cust->id }}" 
                                {{ (old('company_id') == $cust->id || ($company && $company->id == $cust->id)) ? 'selected' : '' }}>
                                {{ $cust->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('company_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    @if ($company)
                        <input type="hidden" name="company_id" value="{{ $company->id }}">
                    @endif
                </div>

                {{-- Vessel Name (Mapped to 'name') --}}
                <div class="mb-3">
                    <label class="form-label font-weight-bold">Vessel Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                           value="{{ old('name') }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Technical Specifications Card --}}
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-info text-white">
                Technical Specifications
            </div>
            <div class="card-body">
                <div class="row">
                    {{-- IMO Number --}}
                    <div class="col-md-4 mb-3">
                        <label class="form-label">IMO Number</label>
                        <input type="text" name="imo_number" class="form-control @error('imo_number') is-invalid @enderror" 
                               value="{{ old('imo_number') }}">
                        @error('imo_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Call Sign --}}
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Call Sign</label>
                        <input type="text" name="call_sign" class="form-control @error('call_sign') is-invalid @enderror" 
                               value="{{ old('call_sign') }}">
                        @error('call_sign')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Port of Call --}}
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Port of Call</label>
                        <input type="text" name="port_of_call" class="form-control @error('port_of_call') is-invalid @enderror" 
                               value="{{ old('port_of_call') }}">
                        @error('port_of_call')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    {{-- Vessel Type --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label font-weight-bold">Vessel Type <span class="text-danger">*</span></label>
                        {{-- Contoh input sederhana, bisa diganti dengan select jika ada daftar tipe baku --}}
                        <input type="text" name="vessel_type" class="form-control @error('vessel_type') is-invalid @enderror" 
                               value="{{ old('vessel_type') }}" required>
                        @error('vessel_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    {{-- Flag --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Flag (Default: Indonesia)</label>
                        <input type="text" name="flag" class="form-control @error('flag') is-invalid @enderror" 
                               value="{{ old('flag', 'Indonesia') }}">
                        @error('flag')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    {{-- Gross Tonnage --}}
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Gross Tonnage</label>
                        <input type="number" step="0.01" name="gross_tonnage" class="form-control @error('gross_tonnage') is-invalid @enderror" 
                               value="{{ old('gross_tonnage') }}">
                        @error('gross_tonnage')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Net Tonnage --}}
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Net Tonnage</label>
                        <input type="number" step="0.01" name="net_tonnage" class="form-control @error('net_tonnage') is-invalid @enderror" 
                               value="{{ old('net_tonnage') }}">
                        @error('net_tonnage')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Year Built --}}
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Year Built</label>
                        <input type="number" name="year_built" class="form-control @error('year_built') is-invalid @enderror" 
                               value="{{ old('year_built') }}" min="1800" max="{{ date('Y') }}">
                        @error('year_built')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- Status Card --}}
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-warning text-dark">
                Operational Status
            </div>
            <div class="card-body">
                {{-- Status (ENUM values) --}}
                <div class="mb-3">
                    <label class="form-label font-weight-bold">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-select form-control @error('status') is-invalid @enderror" required>
                        <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active (In service)</option>
                        <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>Maintenance (Under repair)</option>
                        <option value="retired" {{ old('status') == 'retired' ? 'selected' : '' }}>Retired (Out of service)</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Buttons --}}
        <div class="d-flex justify-content-start">
            <button type="submit" class="btn btn-success btn-lg">Save Vessel</button>
            <a href="{{ route('vessels.index') }}" class="btn btn-secondary btn-lg ms-2">Cancel</a>
        </div>
    </form>
</div>
@endsection
