@extends('layouts.app')

@section('title', 'Add New Marketing Lead')

@section('content')
<div class="container py-4">

    <style>
        .container {
            max-width: 100% !important;
        }

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

        .form-label {
            font-weight: 600;
        }

        .form-control,
        .form-select {
            border-radius: 8px;
        }

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
        <h2>📝 Add New Marketing Lead</h2>
        <div class="d-flex gap-2">
            <a href="{{ route('marketing.index') }}" class="btn btn-light btn-sm">← Back to List</a>
        </div>
    </div>

    {{-- FORM --}}
    <form action="{{ route('marketing.store') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Client Name</label>
                    <select name="client_id" id="client_id" class="form-select" required>
                        <option value="">-- Select Client --</option>
                        @foreach($companies as $c)
                            <option 
                                value="{{ $c->id }}"
                                data-vessel="{{ $c->vessel_name ?? '' }}"
                            >
                                {{ $c->name }}
                            </option>
                        @endforeach
                    </select>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" id="email" class="form-control">
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Phone</label>
                <input type="text" name="phone" id="phone" class="form-control">
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Assigned Staff</label>
                <select name="staff_id" class="form-select">
                    <option value="">-- Select Staff --</option>
                    @foreach($staffOptions as $id => $name)
                        <option value="{{ $id }}" {{ old('staff_id') == $id ? 'selected' : '' }}>
                            {{ $name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- FIXED DATE FIELDS --}}
            <div class="col-md-6 mb-3">
                <label class="form-label">Last Contact</label>
                <input 
                    type="text"
                    name="last_contact"
                    id="last_contact"
                    class="form-control"
                    placeholder="dd/mm/yyyy"
                    value="{{ old('last_contact') }}"
                    autocomplete="off"
                >
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Next Follow Up</label>
                <input 
                    type="text"
                    name="next_follow_up"
                    id="next_follow_up"
                    class="form-control"
                    placeholder="dd/mm/yyyy"
                    value="{{ old('next_follow_up') }}"
                    autocomplete="off"
                >
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-control">
                    @foreach($statusOptions as $status)
                        <option value="{{ $status }}" {{ old('status') == $status ? 'selected' : '' }}>
                            {{ $status }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Revenue</label>
                <div class="input-group">
                    <select name="currency" class="form-select" style="max-width: 100px;">
                        <option value="IDR">IDR</option>
                        <option value="USD" selected>USD</option>
                        <option value="SGD">SGD</option>
                        <option value="MYR">MYR</option>
                    </select>
                    <input type="text" name="revenue" class="form-control" placeholder="e.g. 20,000">
                </div>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Vessel Name</label>
                <input type="text" name="vessel_name" id="vessel_name" class="form-control" readonly>
            </div>

            <div class="col-md-12 mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="3"></textarea>
            </div>

            <div class="col-md-12 mb-3">
                <label class="form-label">Remark</label>
                <textarea name="remark" class="form-control" rows="3"></textarea>
            </div>
        </div>

        <div class="text-end">
            <button class="btn btn-primary px-4">Save</button>
            <a href="{{ route('marketing.index') }}" class="btn btn-secondary px-4">Cancel</a>
        </div>
    </form>
</div>

{{-- DATEPICKER FIX --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
document.getElementById('client_id').addEventListener('change', function () {
    let id = this.value;
    if (!id) return;

    fetch(`/marketing/get-customer/${id}`)
        .then(response => response.json())
        .then(data => {
            
            document.getElementById('email').value = data.customer.email ?? '';
            document.getElementById('phone').value = data.customer.phone ?? '';

            if (data.vessels.length > 0) {
                document.getElementById('vessel_name').value = data.vessels[0].name ?? '';
            } else {
                document.getElementById('vessel_name').value = '-';
            }
        })
        .catch(err => console.error(err));
});
</script>

<script>
flatpickr("#last_contact", {
    dateFormat: "d/m/Y",
    allowInput: true
});

flatpickr("#next_follow_up", {
    dateFormat: "d/m/Y",
    allowInput: true
});
</script>

@endsection
