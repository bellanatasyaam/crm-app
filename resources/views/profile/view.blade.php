@extends('layouts.app')

@section('title', 'Detail Profile Marketing')

@section('content')
<div class="profile">
    <h1>Profil Marketing: {{ $profile->name }}</h1>
    <img src="{{ $profile->photoUrl }}" alt="Foto Profil" style="width:150px; border-radius:50%;">
    <p><strong>Email:</strong> {{ $profile->email }}</p>
    <p><strong>Nomor Telepon:</strong> {{ $profile->phone }}</p>
    <a href="{{ route('profile.edit', $profile->id) }}">Edit Profile</a>
</div>
@endsection
