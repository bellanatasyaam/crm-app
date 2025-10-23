@extends('layouts.app')

@section('title', 'Edit Profile')

@section('content')
<div class="container py-4">
    <h1>Edit Profile</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PATCH')

        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $profile->name) }}">
        </div>

        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email', $profile->email) }}">
        </div>

        <div class="mb-3">
            <label>Phone</label>
            <input type="text" name="phone" class="form-control" value="{{ old('phone', $profile->phone) }}">
        </div>

        <div class="mb-3">
            <label>Photo</label>
            <input type="file" name="photo" class="form-control">
            @if($profile->photoUrl)
                <img src="{{ $profile->photoUrl }}" alt="Current Photo" style="width:80px; height:80px; object-fit:cover;">
            @endif
        </div>

        <button type="submit" class="btn btn-primary">Update Profile</button>
        <a href="{{ route('profile.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
