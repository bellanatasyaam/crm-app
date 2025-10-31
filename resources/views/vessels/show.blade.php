@extends('layouts.app')

@section('content')
<div class="container py-4">

    <h2 class="mb-4"><i class="fas fa-ship"></i> Vessel Detail</h2>

    {{-- Company & Basic Info --}}
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-primary text-white">
            Company & Basic Info
        </div>
        <div class="card-body">
            <p><strong>Customer / Company:</strong> {{ $vessel->company?->name ?? '—' }}</p>
            <p><strong>Vessel Name:</strong> {{ $vessel->name ?? '—' }}</p>
        </div>
    </div>

    {{-- Technical Specifications --}}
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-info text-white">
            Technical Specifications
        </div>
        <div class="card-body">
            <p><strong>IMO Number:</strong> {{ $vessel->imo_number ?? '—' }}</p>
            <p><strong>Call Sign:</strong> {{ $vessel->call_sign ?? '—' }}</p>
            <p><strong>Port of Call:</strong> {{ $vessel->port_of_call ?? '—' }}</p>
            <p><strong>Vessel Type:</strong> {{ $vessel->vessel_type ?? '—' }}</p>
            <p><strong>Flag:</strong> {{ $vessel->flag ?? '—' }}</p>
            <p><strong>Gross Tonnage:</strong> {{ $vessel->gross_tonnage ?? '—' }}</p>
            <p><strong>Net Tonnage:</strong> {{ $vessel->net_tonnage ?? '—' }}</p>
            <p><strong>Year Built:</strong> {{ $vessel->year_built ?? '—' }}</p>
        </div>
    </div>

    {{-- Operational Status --}}
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-warning text-dark">
            Operational Status
        </div>
        <div class="card-body">
            <p><strong>Status:</strong> {{ ucfirst($vessel->status ?? '—') }}</p>
        </div>
    </div>

    <div class="mt-4">
        <a href="{{ route('vessels.edit', $vessel->id) }}" class="btn btn-warning">
            <i class="fas fa-edit"></i> Edit
        </a>
        <a href="{{ route('vessels.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>

</div>
@endsection
