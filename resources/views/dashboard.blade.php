@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h2 class="mb-4">Master Data Menu</h2>
    <div class="row">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h4>Customer Dashboard</h4>
                    <a href="{{ route('customers.index') }}" class="btn btn-primary mt-3">Go to Customers</a>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h4>Marketing Dashboard</h4>
                    <a href="{{ route('marketing.index') }}" class="btn btn-success mt-3">Go to Marketing</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
