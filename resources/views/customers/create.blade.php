@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Add New Customer</h2>
    <form action="{{ route('customers.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label">Customer Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Assigned Staff</label>
            <input type="text" name="assigned_staff" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Last Contact Date</label>
            <input type="date" name="last_followup_date" class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label">Next Follow-Up</label>
            <input type="date" name="next_followup_date" class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label">Status</label>
            <input type="text" name="status" class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label">Potential Revenue</label>
            <div class="input-group">
                <input type="number" name="potential_revenue" class="form-control" required>
                <select name="currency" class="form-select" style="max-width:120px;">
                    <option value="USD">USD</option>
                    <option value="IDR">IDR</option>
                    <option value="SGD">SGD</option>
                    <option value="EUR">EUR</option>
                </select>
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label">Notes</label>
            <textarea name="notes" class="form-control"></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
                <option value="Lead">Lead</option>
                <option value="Quotation Sent">Quotation Sent</option>
                <option value="Negotiation">Negotiation</option>
                <option value="On Going Vessel Call">On Going Vessel Call</option>
                <option value="Pending Payment">Pending Payment</option>
                <option value="Closing">Closing</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">Save</button>
        <a href="{{ route('customers.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection
