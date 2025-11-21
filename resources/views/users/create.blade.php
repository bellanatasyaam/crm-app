@extends('layouts.app')

@section('content')
<style>
    .auth-card {
        max-width: 450px;
        margin: 40px auto;
        padding: 35px;
        border-radius: 15px;
        background: #ffffff;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
        border: 1px solid #e9ecef;
    }

    .auth-title {
        font-weight: 700;
        font-size: 26px;
        text-align: center;
        margin-bottom: 25px;
        color: #2d2d2d;
    }

    .form-label {
        font-weight: 600;
        color: #444;
    }

    .btn-primary-custom {
        background: #4b7bec;
        border: none;
        border-radius: 8px;
        padding: 10px 15px;
    }

    .btn-primary-custom:hover {
        background: #3867d6;
    }
</style>

<div class="auth-card">

    <h2 class="auth-title">Create New User</h2>

    <form action="{{ route('users.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label">Full Name</label>
            <input type="text" name="name" class="form-control" placeholder="Enter full name" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Email Address</label>
            <input type="email" name="email" class="form-control" placeholder="email@example.com" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" placeholder="••••••••" required>
        </div>

        <div class="mb-4">
            <label class="form-label">Confirm Password</label>
            <input type="password" name="password_confirmation" class="form-control" placeholder="••••••••" required>
        </div>

        <div class="d-grid gap-2">
            <button class="btn btn-primary-custom">Save User</button>
            <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">Back</a>
        </div>
    </form>
</div>
@endsection
