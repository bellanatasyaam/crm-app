@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h2 class="mb-4">Master Data Menu</h2>
    <div class="row">

        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h4>Marketing Dashboard</h4>
                    <a href="{{ route('marketing.index') }}" class="btn btn-success mt-3">Go to Marketing</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h4>Customer Dashboard</h4>
                    <a href="{{ route('customers_vessels.index') }}" class="btn btn-primary mt-3">Go to Customers</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h4>Vessel Dashboard</h4>
                    <a href="{{ route('vessels.index') }}" class="btn btn-warning mt-3">Go to Vessels</a>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
