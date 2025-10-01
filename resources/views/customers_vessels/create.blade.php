@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2>Add Vessel for {{ $customer->name }}</h2>

    <form action="{{ route('customers.vessels.store', $customer->id) }}" method="POST">
        @csrf

        {{-- Hidden input untuk customer_id --}}
        <input type="hidden" name="customer_id" value="{{ $customer->id }}">

        <div class="mb-3">
            <label class="form-label">Vessel Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">IMO Number</label>
            <input type="text" name="imo_number" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Flag</label>
            <input type="text" name="flag" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Type</label>
            <input type="text" name="type" class="form-control">
        </div>

        <button type="submit" class="btn btn-success">Save</button>
        <a href="{{ route('customers_vessels.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
