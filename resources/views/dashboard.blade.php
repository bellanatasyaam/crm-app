@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h2 class="mb-4 text-center fw-bold">Dashboard</h2>

    {{-- ================= Menu Cards ================= --}}
    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="card shadow-sm border-0 rounded-4 p-3 text-center h-100 hover-scale" style="background: #e6f4ea;">
                <div class="card-body">
                    <i class="bi bi-graph-up fs-1 text-success mb-3"></i>
                    <h5 class="fw-bold">Marketing</h5>
                    <p class="text-muted small">Manage marketing leads & campaigns</p>
                    <a href="{{ route('marketing.index') }}" class="btn btn-success btn-sm mt-2">Go to Marketing</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0 rounded-4 p-3 text-center h-100 hover-scale" style="background: #e8f0fe;">
                <div class="card-body">
                    <i class="bi bi-people fs-1 text-primary mb-3"></i>
                    <h5 class="fw-bold">Customers</h5>
                    <p class="text-muted small">View and manage customers & vessels</p>
                    <a href="{{ route('companies.index') }}" class="btn btn-primary btn-sm mt-2">Go to Customers</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0 rounded-4 p-3 text-center h-100 hover-scale" style="background: #fff4e6;">
                <div class="card-body">
                    {{-- Icon kapal --}}
                    <i class="bi bi-laptop fs-1 text-warning mb-3"></i>
                    <h5 class="fw-bold">Vessels</h5>
                    <p class="text-muted small">Track vessel data & activities</p>
                    <a href="{{ route('vessels.index') }}" class="btn btn-warning btn-sm mt-2">Go to Vessels</a>
                </div>
            </div>
        </div>
    </div>
</div>


<style>
.hover-scale {
    transition: all 0.3s ease;
}
.hover-scale:hover {
    transform: scale(1.03);
    box-shadow: 0 10px 20px rgba(0,0,0,0.12);
}
</style>

{{-- Bootstrap Icons CDN --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
@endsection
