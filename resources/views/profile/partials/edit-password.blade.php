<!-- resources/views/profile/edit-password.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Update Password</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('password.update') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Current Password</label>
            <input type="password" name="current_password" class="form-control">
            @error('current_password') <small class="text-danger">{{ $message }}</small> @enderror
        </div>
        <div class="mb-3">
            <label>New Password</label>
            <input type="password" name="password" class="form-control">
            @error('password') <small class="text-danger">{{ $message }}</small> @enderror
        </div>
        <div class="mb-3">
            <label>Confirm New Password</label>
            <input type="password" name="password_confirmation" class="form-control">
        </div>
        <button class="btn btn-primary">Update Password</button>
    </form>
</div>
@endsection
