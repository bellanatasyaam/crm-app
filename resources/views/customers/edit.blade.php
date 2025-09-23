@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Customer</h2>
    <form action="{{ route('customers.update', $customer->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label class="form-label">Customer Name</label>
            <input type="text" name="name" class="form-control" value="{{ $customer->name }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Assigned Staff</label>
            <input type="text" name="assigned_staff" class="form-control" value="{{ $customer->assigned_staff }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Last Contact Date</label>
            <input type="date" name="last_followup_date" class="form-control" value="{{ $customer->last_followup_date }}">
        </div>
        <div class="mb-3">
            <label class="form-label">Next Follow-Up</label>
            <input type="date" name="next_followup_date" class="form-control" value="{{ $customer->next_followup_date }}">
        </div>
        <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
                <option value="Lead" {{ $customer->status == 'Lead' ? 'selected' : '' }}>Lead</option>
                <option value="Quotation Sent" {{ $customer->status == 'Quotation Sent' ? 'selected' : '' }}>Quotation Sent</option>
                <option value="Negotiation" {{ $customer->status == 'Negotiation' ? 'selected' : '' }}>Negotiation</option>
                <option value="On Going Vessel Call" {{ $customer->status == 'On Going Vessel Call' ? 'selected' : '' }}>On Going Vessel Call</option>
                <option value="Pending Payment" {{ $customer->status == 'Pending Payment' ? 'selected' : '' }}>Pending Payment</option>
                <option value="Closing" {{ $customer->status == 'Closing' ? 'selected' : '' }}>Closing</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Potential Revenue</label>
            <div class="input-group">
                <input type="number" name="potential_revenue" class="form-control" 
                    value="{{ $customer->potential_revenue }}" required>
                <select name="currency" class="form-select" style="max-width:120px;">
                    <option value="USD" {{ $customer->currency == 'USD' ? 'selected' : '' }}>USD</option>
                    <option value="IDR" {{ $customer->currency == 'IDR' ? 'selected' : '' }}>IDR</option>
                    <option value="SGD" {{ $customer->currency == 'SGD' ? 'selected' : '' }}>SGD</option>
                    <option value="EUR" {{ $customer->currency == 'EUR' ? 'selected' : '' }}>EUR</option>
                </select>
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label">Notes</label>
            <textarea name="notes" class="form-control">{{ $customer->notes }}</textarea>
        </div>
        <button type="submit" class="btn btn-success">Update</button>
        <a href="{{ route('customers.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection
