@extends('layouts.app')

@section('title', 'Daftar Marketing')

@section('content')
<h1>Daftar Marketing</h1>
<ul>
    @foreach ($marketingList as $marketing)
    <li>
        <a href="{{ route('profile.view', $marketing->id) }}">
            {{ $marketing->name }} - {{ $marketing->email }}
        </a>
    </li>
    @endforeach
</ul>
@endsection
