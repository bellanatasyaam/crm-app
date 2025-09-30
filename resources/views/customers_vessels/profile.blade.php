@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2>Customer Profile</h2>

    <div class="card">
        <div class="card-body">
            <h4>{{ $customer->name }}</h4>
            <p><strong>Email:</strong> {{ $customer->email }}</p>
            <p><strong>Phone:</strong> {{ $customer->phone }}</p>
            <p><strong>Address:</strong> {{ $customer->address }}</p>
            <a href="{{ route('customers.show', $customer->id) }}" class="btn btn-primary">View Vessels</a>
        </div>
    </div>
</div>
@endsection
