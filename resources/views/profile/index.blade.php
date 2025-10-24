@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
<div class="container py-5">
    <h2 class="mb-4 text-center fw-bold">My Profile</h2>

    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm border-0 rounded-4 p-4 text-center hover-scale" style="background: #e8f0fe;">
                <div class="card-body">

                    <img src="{{ $profile->photo_url ?? asset('images/default-avatar.png') }}"
                         alt="Profile Photo"
                         class="rounded-circle mb-3"
                         style="width:120px; height:120px; object-fit:cover; border:3px solid #0d6efd;">

                    <h5 class="fw-bold">{{ $profile->name }}</h5>
                    <p class="text-muted mb-1">{{ $profile->email }}</p>
                    <p class="text-secondary">{{ $profile->phone ?? '-' }}</p>

                    <div class="d-flex justify-content-center gap-3 mt-3">
                        <a href="{{ route('profile.edit') }}" class="btn btn-primary btn-sm px-3">
                            <i class="bi bi-pencil-square me-1"></i> Edit
                        </a>
                        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-sm px-3">
                            <i class="bi bi-arrow-left-circle me-1"></i> Back
                        </a>
                    </div>
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

{{-- Bootstrap Icons --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
@endsection
