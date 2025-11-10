@extends('layouts.app')

@section('title', 'Detail Customer')

@section('content')
<div class="container py-4">
    <div class="dashboard-header d-flex justify-content-between align-items-center mb-4">
        <h2>🧾 Detail Customer</h2>
        <a href="{{ route('marketing.index') }}" class="btn btn-light btn-sm">← Back</a>
    </div>

    <div class="card shadow-sm p-4">
        <h4 class="fw-bold mb-3">{{ $marketing->client_name }}</h4>

        <p><strong>Email:</strong> {{ $marketing->email ?? '-' }}</p>
        <p><strong>Phone:</strong> {{ $marketing->phone ?? '-' }}</p>
        <p><strong>Staff:</strong> {{ $marketing->staff?->name ?? '-' }}</p>
        <p><strong>Last Contact:</strong> {{ $marketing->last_contact ?? '-' }}</p>
        <p><strong>Next Follow Up:</strong> {{ $marketing->next_follow_up ?? '-' }}</p>
        <p><strong>Status:</strong> <span class="badge bg-primary">{{ $marketing->status ?? '-' }}</span></p>
        <p><strong>Revenue:</strong> {{ $marketing->revenue ?? '-' }}</p>
        <p><strong>Vessel:</strong> {{ $marketing->vessel_name ?? '-' }}</p>

        <hr>
        <p><strong>Description:</strong><br>{{ $marketing->description ?? '-' }}</p>
        <p><strong>Remark:</strong><br>{{ $marketing->remark ?? '-' }}</p>
    </div>
</div>
@endsection
