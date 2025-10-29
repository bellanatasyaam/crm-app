@extends('layouts.app')

@section('title', 'Detail Marketing')

@section('content')
<div class="container py-4">

    <style>
        .container { max-width: 100% !important; }

        .dashboard-header {
            background: linear-gradient(90deg, #0ea5e9 0%, #0284c7 100%);
            color: #fff;
            padding: 15px 20px;
            border-radius: 12px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
            margin-bottom: 25px;
        }

        .dashboard-header h2 {
            font-size: 22px;
            font-weight: 600;
            margin: 0;
        }

        .card {
            border-radius: 12px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.08);
            padding: 25px;
            background: #fff;
        }

        .info-item {
            margin-bottom: 15px;
        }

        .info-item strong {
            width: 180px;
            display: inline-block;
        }

        .btn {
            border-radius: 8px;
            transition: 0.2s ease-in-out;
        }

        .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 3px 6px rgba(0,0,0,0.15);
        }

        .profile-photo {
            border-radius: 50%;
            width: 120px;
            height: 120px;
            object-fit: cover;
            margin-bottom: 15px;
        }
    </style>

    {{-- HEADER --}}
    <div class="dashboard-header d-flex justify-content-between align-items-center">
        <h2>📋 Marketing Detail</h2>
        <div class="d-flex gap-2">
            <a href="{{ route('marketing.index') }}" class="btn btn-light btn-sm">← Back to List</a>
            <a href="{{ route('marketing.edit', $marketing->id) }}" class="btn btn-warning btn-sm">✏️ Edit</a>
        </div>
    </div>

    {{-- DETAIL CARD --}}
    <div class="card mx-auto" style="max-width: 700px;">
        <div class="text-center mb-4">
            @if($marketing->staff && $marketing->staff->photo_url)
                <img src="{{ asset($marketing->staff->photo_url) }}" class="profile-photo" alt="Staff Photo">
            @else
                <img src="{{ asset('uploads/photos/default.jpg') }}" class="profile-photo" alt="No Photo">
            @endif
            <h4 class="mt-2">{{ $marketing->staff->name ?? 'No Staff Assigned' }}</h4>
        </div>

        <div class="info-item">
            <strong>Client Name:</strong> {{ $marketing->client_name ?? '-' }}
        </div>
        <div class="info-item">
            <strong>Email:</strong> {{ $marketing->email ?? '-' }}
        </div>
        <div class="info-item">
            <strong>Phone:</strong> {{ $marketing->phone ?? '-' }}
        </div>
        <div class="info-item">
            <strong>Staff:</strong> {{ $marketing->staff->name ?? '-' }}
        </div>
        <div class="info-item">
            <strong>Last Contact:</strong> {{ $marketing->last_contact ?? '-' }}
        </div>
        <div class="info-item">
            <strong>Next Follow Up:</strong> {{ $marketing->next_follow_up ?? '-' }}
        </div>
        <div class="info-item">
            <strong>Status:</strong>
            @php
                $statusColors = [
                    'Follow up' => 'primary',
                    'On going' => 'dark',
                    'On progress' => 'info',
                    'Quotation send' => 'secondary',
                ];
                $color = $statusColors[$marketing->status ?? ''] ?? 'light';
            @endphp
            <span class="badge bg-{{ $color }}">{{ $marketing->status ?? '-' }}</span>
        </div>
        <div class="info-item">
            <strong>Revenue:</strong> {{ $marketing->revenue ?? '-' }}
        </div>
        <div class="info-item">
            <strong>Vessel:</strong> {{ $marketing->vessel_name ?? '-' }}
        </div>
        <div class="info-item">
            <strong>Description:</strong> {{ $marketing->description ?? '-' }}
        </div>
        <div class="info-item">
            <strong>Remark:</strong> {{ $marketing->remark ?? '-' }}
        </div>
    </div>

</div>
@endsection
