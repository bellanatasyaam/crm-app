@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
<div class="container py-4">

    @if($profile)
        <!-- Tampilkan profile user login -->
        <div class="card mx-auto" style="max-width: 600px;">
            <div class="card-body text-center">
                <img src="{{ $profile->photoUrl ?? asset('images/default-avatar.png') }}"
                     alt="Profile Photo" class="rounded-circle mb-3" style="width:120px; height:120px; object-fit:cover;">
                <h3>{{ $profile->name }}</h3>
                <p>{{ $profile->email }}</p>
                <p>{{ $profile->phone ?? '-' }}</p>
            </div>
        </div>
    @else
        <p class="text-center">Profile not available.</p>
    @endif

</div>
@endsection
