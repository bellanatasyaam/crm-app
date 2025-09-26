@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Add New Customer</h2>

    <form action="{{ route('customers.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="customer_id" class="form-label">Filter by Customer</label>
            <select id="customer_id" name="customer_id" class="form-control">
                <option value="">-- Pilih Customer --</option>
                @foreach($customers as $c)
                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="vessels" class="form-label">Selected Vessels</label>
            <select id="vessels" name="vessels[]" class="form-control" multiple>
                {{-- Kapal muncul otomatis lewat JS --}}
            </select>
            <small class="text-muted">*Hold CTRL (Windows) / CMD (Mac) untuk pilih lebih dari satu vessel</small>
        </div>

        <script>
            const customers = @json($customers);

            document.getElementById('customer_id').addEventListener('change', function() {
                let customerId = this.value;
                let vesselsSelect = document.getElementById('vessels');

                vesselsSelect.innerHTML = "";

                if (customerId) {
                    let customer = customers.find(c => c.id == customerId);
                    if (customer && customer.vessels && customer.vessels.length > 0) {
                        customer.vessels.forEach(v => {
                            let opt = document.createElement('option');
                            opt.value = v.id;
                            opt.textContent = v.name;
                            vesselsSelect.appendChild(opt);
                        });
                    }
                }
            });
        </script>

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Customer Name</label>
                <input type="text" name="name" class="form-control" required>  
            </div>
            <div class="col-md-6">
                <label class="form-label">Assigned Staff</label>
                <input type="text" name="assigned_staff" class="form-control" required>
            </div>
        </div>

        <div class="row g-3 mt-2">
            <div class="col-md-6">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Phone</label>
                <input type="text" name="phone" class="form-control" required>
            </div>
        </div>

        <div class="row g-3 mt-2">
            <div class="col-md-6">
                <label class="form-label">Last Contact Date</label>
                <input type="date" name="last_followup_date" class="form-control">
            </div>
            <div class="col-md-6">
                <label class="form-label">Next Follow-Up</label>
                <input type="date" name="next_followup_date" class="form-control">
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
                        <option value="{{ $status }}">{{ $status }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Potential Revenue</label>
                <div class="input-group">
                    <input type="number" name="potential_revenue" class="form-control" required>
                    <select name="currency" class="form-select" style="max-width:120px;">
                        @foreach(['USD','IDR','SGD','EUR'] as $currency)
                            <option value="{{ $currency }}">{{ $currency }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="mt-3">
            <label class="form-label">Notes</label>
            <textarea name="notes" class="form-control" rows="3"></textarea>
        </div>

        <div class="mt-4 d-flex gap-2">
            <button type="submit" class="btn btn-success">Save</button>
            <a href="{{ route('customers.index') }}" class="btn btn-secondary">Back</a>
        </div>
    </form>
</div>
@endsection
