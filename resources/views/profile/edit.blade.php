@extends('layouts.app')

@section('title', 'Edit Profile Marketing')

@section('content')
<h1>Edit Profile Marketing</h1>

<form action="{{ route('profile.update', $profile->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <label>Nama:</label>
    <input type="text" name="name" value="{{ old('name', $profile->name) }}" required>
    
    <label>Email:</label>
    <input type="email" name="email" value="{{ old('email', $profile->email) }}" required>

    <label>Nomor Telepon:</label>
    <input type="text" name="phone" value="{{ old('phone', $profile->phone) }}" required>

    <label>Foto Profil:</label>
    <input type="file" name="photo">

    <button type="submit">Simpan</button>
</form>
@endsection
