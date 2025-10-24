@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="dashboard-header d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">{{ $company->name }}</h2>
        <a href="{{ route('marketing.index') }}" class="btn btn-outline-light btn-sm">← Back</a>
    </div>

    <p><strong>Email:</strong> {{ $company->email }}</p>
    <p><strong>Phone:</strong> {{ $company->phone }}</p>
    <p><strong>Last Follow Up:</strong> {{ $company->last_followup_date }}</p>
    <p><strong>Next Follow Up:</strong> {{ $company->next_followup_date }}</p>
</div>
@endsection
