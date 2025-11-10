@extends('layouts.app')

@section('title', 'Marketing Profile')

@section('content')
<div class="container py-4">

    <div class="dashboard-header d-flex justify-content-between align-items-center">
        <h2>👤 Marketing Profile</h2>
        <a href="{{ route('marketing.index') }}" class="btn btn-light btn-sm">← Back to List</a>
    </div>

    <div class="card mt-4 p-4 shadow-sm text-center">
        <img src="{{ $profile->photo_url ?? '/uploads/photos/default.jpg' }}" 
             alt="{{ $profile->name }}" 
             class="rounded-circle mb-3" 
             width="150" height="150">

        <h4 class="mb-1">{{ $profile->name }}</h4>
        <p class="text-muted mb-1">{{ $profile->email }}</p>
        <p class="text-muted">{{ $profile->phone ?? '-' }}</p>

        <p class="mt-3"><strong>Role:</strong> {{ ucfirst($profile->role) }}</p>
    </div>

</div>
@endsection
