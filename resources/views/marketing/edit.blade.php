@extends('layouts.app')

@section('title', 'Edit Marketing Lead')

@section('content')
<div class="container py-4">

    <style>
        .container { max-width: 100% !important; }

        .dashboard-header {
            background: linear-gradient(90deg, #0ea5e9 0%, #0284c7 100%);
            color: #fff;
            padding: 15px 20px;
            border-radius: 12px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 25px;
        }

        .dashboard-header h2 {
            font-size: 22px;
            font-weight: 600;
            margin: 0;
        }

        .form-label { font-weight: 600; }
        .form-control, .form-select { border-radius: 8px; }

        .btn {
            border-radius: 8px;
            transition: 0.2s ease-in-out;
        }

        .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.15);
        }
    </style>

    {{-- HEADER --}}
    <div class="dashboard-header d-flex justify-content-between align-items-center">
        <h2>✏️ Edit Marketing Lead</h2>
        <div class="d-flex gap-2">
            <a href="{{ route('marketing.index') }}" class="btn btn-light btn-sm">← Back to List</a>
        </div>
    </div>

    {{-- FORM --}}
    <form action="{{ route('marketing.update', $marketing->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="client_name" class="form-label">Client Name</label>
                <input type="text" name="client_name" id="client_name"
                    value="{{ old('client_name', $marketing->client_name) }}" class="form-control" required>
            </div>

            <div class="col-md-6 mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email"
                    value="{{ old('email', $marketing->email) }}" class="form-control">
            </div>

            <div class="col-md-6 mb-3">
                <label for="phone" class="form-label">Phone</label>
                <input type="text" name="phone" id="phone"
                    value="{{ old('phone', $marketing->phone) }}" class="form-control">
            </div>

            <div class="col-md-6 mb-3">
                <label for="staff_id" class="form-label">Assigned Staff</label>
                <select name="staff_id" id="staff_id" class="form-select">
                    <option value="">-- Select Staff --</option>
                    @foreach($staffOptions as $id => $name)
                        <option value="{{ $id }}"
                            {{ old('staff_id', $marketing->staff_id) == $id ? 'selected' : '' }}>
                            {{ $name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label for="last_contact" class="form-label">Last Contact</label>
                <input type="date" name="last_contact" id="last_contact"
                    value="{{ old('last_contact', $marketing->last_contact) }}" class="form-control">
            </div>

            <div class="col-md-6 mb-3">
                <label for="next_follow_up" class="form-label">Next Follow Up</label>
                <input type="date" name="next_follow_up" id="next_follow_up"
                    value="{{ old('next_follow_up', $marketing->next_follow_up) }}" class="form-control">
            </div>

            <div class="col-md-6 mb-3">
                <label for="status" class="form-label">Status</label>
                <select name="status" class="form-control">
                    @foreach($statusOptions as $status)
                        <option value="{{ $status }}" {{ old('status', $marketing->status ?? '') == $status ? 'selected' : '' }}>
                            {{ $status }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label for="revenue" class="form-label">Revenue</label>
                <div class="input-group">
                    <select name="currency" id="currency" class="form-select" style="max-width: 100px;">
                        <option value="IDR" {{ old('currency', $marketing->currency ?? '') == 'IDR' ? 'selected' : '' }}>IDR</option>
                        <option value="USD" {{ old('currency', $marketing->currency ?? '') == 'USD' ? 'selected' : '' }}>USD</option>
                        <option value="SGD" {{ old('currency', $marketing->currency ?? '') == 'SGD' ? 'selected' : '' }}>SGD</option>
                        <option value="MYR" {{ old('currency', $marketing->currency ?? '') == 'MYR' ? 'selected' : '' }}>MYR</option>
                    </select>
                    <input type="text" name="revenue" id="revenue"
                        value="{{ old('revenue', $marketing->revenue) }}" class="form-control" placeholder="e.g. 20,000">
                </div>
            </div>

            <div class="col-md-6 mb-3">
                <label for="vessel_name" class="form-label">Vessel Name</label>
                <input type="text" name="vessel_name" id="vessel_name"
                    value="{{ old('vessel_name', $marketing->vessel_name) }}" class="form-control">
            </div>

            <div class="col-md-12 mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" id="description" class="form-control" rows="3">{{ old('description', $marketing->description) }}</textarea>
            </div>

            <div class="col-md-12 mb-3">
                <label for="remark" class="form-label">Remark</label>
                <textarea name="remark" id="remark" class="form-control" rows="3">{{ old('remark', $marketing->remark) }}</textarea>
            </div>
        </div>

        <div class="text-end">
            <button type="submit" class="btn btn-primary px-4">Update</button>
            <a href="{{ route('marketing.index') }}" class="btn btn-secondary px-4">Cancel</a>
        </div>
    </form>
</div>
@endsection
