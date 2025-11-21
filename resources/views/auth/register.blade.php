@extends('layouts.app')

@section('content')
<div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="card shadow-lg p-4" style="max-width: 450px; width: 100%;">
        
        <div class="text-center mb-4">
            <img src="{{ asset('logo.png') }}" alt="Logo" style="width: 80px;">
            <h4 class="mt-2 fw-bold">Register</h4>
        </div>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            {{-- Name --}}
            <div class="mb-3">
                <label class="form-label">Name</label>
                <input id="name" 
                       type="text" 
                       name="name"
                       class="form-control @error('name') is-invalid @enderror"
                       value="{{ old('name') }}" required autofocus>
                @error('name')
                    <span class="text-danger small">{{ $message }}</span>
                @enderror
            </div>

            {{-- Email --}}
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input id="email" 
                       type="email" 
                       name="email"
                       class="form-control @error('email') is-invalid @enderror"
                       value="{{ old('email') }}" required>
                @error('email')
                    <span class="text-danger small">{{ $message }}</span>
                @enderror
            </div>

            {{-- Password --}}
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input id="password" 
                       type="password" 
                       name="password"
                       class="form-control @error('password') is-invalid @enderror"
                       required>
                @error('password')
                    <span class="text-danger small">{{ $message }}</span>
                @enderror
            </div>

            {{-- Confirm --}}
            <div class="mb-3">
                <label class="form-label">Confirm Password</label>
                <input id="password_confirmation" 
                       type="password" 
                       name="password_confirmation"
                       class="form-control @error('password_confirmation') is-invalid @enderror"
                       required>
                @error('password_confirmation')
                    <span class="text-danger small">{{ $message }}</span>
                @enderror
            </div>

            {{-- Submit --}}
            <button type="submit" class="btn btn-primary w-100">Register</button>

            <div class="d-flex justify-content-between mt-3">
                <a href="{{ route('login') }}">Already registered?</a>
            </div>
        </form>

    </div>
</div>
@endsection
