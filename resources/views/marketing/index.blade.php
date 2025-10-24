@extends('layouts.app')

@section('title', 'Daftar Marketing')

@section('content')
<div class="container py-4">

    <style>
        .container { max-width: 100% !important; }
        .dashboard-header {
            background: linear-gradient(90deg, #0ea5e9 0%, #0284c7 100%);
            color: #fff; padding: 15px 20px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .dashboard-header h2 { font-size: 20px; font-weight: 600; margin: 0; }
        .btn { border-radius: 8px; transition: 0.2s ease-in-out; }
        .btn:hover { transform: translateY(-1px); box-shadow: 0 2px 6px rgba(0,0,0,0.15); }
        table.custom-table { width: 100%; border-collapse: collapse; font-size: 14px; }
        table.custom-table th, table.custom-table td { border: 1px solid #dee2e6; padding: 8px 10px; vertical-align: middle; }
        table.custom-table th { background: #f8f9fa; font-weight: 600; }
        table.custom-table td { background: #fff; text-align: center; }
        .table-actions { display: flex; gap: 3px; justify-content: center; }
        .truncate-text { display: inline-block; max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; vertical-align: bottom; }
        .more-link { cursor: pointer; color: #0d6efd; font-size: 11px; margin-left: 5px; user-select: none; }
    </style>

    {{-- Header --}}
    <div class="dashboard-header d-flex justify-content-between align-items-center">
        <h2>📈 Daftar Marketing</h2>
        <div class="d-flex gap-2">
            <a href="{{ route('marketing.create') }}" class="btn btn-primary btn-sm">+ Add Marketing</a>
            <button id="showProfilesBtn" class="btn btn-info btn-sm">Show Marketing Profiles</button>
            <a href="{{ route('dashboard') }}" class="btn btn-light btn-sm">Back to Master Menu</a>
        </div>
    </div>

    {{-- Marketing Profiles (hidden awalnya) --}}
    <div id="marketingProfiles" style="display: none !important;">
        <h2 class="mb-4">Marketing Profile</h2>

        @forelse ($marketingData as $marketing)
            <div class="card mb-3" style="max-width: 540px;">
                <div class="row g-0">
                    <div class="col-md-4">
                        <img src="{{ $marketing->photo_url ?? '/uploads/photos/default.jpg' }}" class="img-fluid rounded-start" alt="Photo">
                    </div>
                    <div class="col-md-8">
                        <div class="card-body">
                            <h5 class="card-title">{{ $marketing->name }}</h5>
                            <p class="card-text"><strong>Email:</strong> {{ $marketing->email }}</p>
                            <p class="card-text"><strong>Phone:</strong> {{ $marketing->phone ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <p>No marketing profile found.</p>
        @endforelse
    </div>

    {{-- Table --}}
    <div class="table-responsive mt-4">
        <table class="custom-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($marketingData as $index => $marketing)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $marketing->name }}</td>
                        <td class="email-col" title="{{ $marketing->email }}">
                            @if($marketing->email)
                                <span class="truncate-text">{{ \Illuminate\Support\Str::limit($marketing->email, 30) }}</span>
                                <span class="more-link" onclick="toggleText(this)">More</span>
                                <span class="full-text d-none">{{ $marketing->email }}</span>
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            <div class="table-actions">
                                <a href="{{ route('marketing.profile', $marketing->id) }}" class="btn btn-info btn-sm">Profile</a>
                                <a href="{{ route('users.edit', $marketing->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('users.destroy', $marketing->id) }}" method="POST" onsubmit="return confirm('Yakin hapus user ini?')" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">No marketing data found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="d-flex justify-content-center mt-3">
        {{ $marketingData->links() }}
    </div>
</div>

<script>
    function toggleText(el) {
        let row = el.closest('td');
        let trunc = row.querySelector('.truncate-text');
        let full = row.querySelector('.full-text');
        if (!trunc || !full) return;
        if (full.classList.contains('d-none')) {
            trunc.classList.add('d-none');
            full.classList.remove('d-none');
            el.innerText = "Less";
        } else {
            trunc.classList.remove('d-none');
            full.classList.add('d-none');
            el.innerText = "More";
        }
    }
    window.toggleText = toggleText;

    // Tombol Show / Hide Marketing Profiles
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('showProfilesBtn').addEventListener('click', function() {
            var profiles = document.getElementById('marketingProfiles');
            if (profiles.style.display === 'none') {
                profiles.style.display = 'block';
                this.innerText = 'Cancel';
            } else {
                profiles.style.display = 'none';
                this.innerText = 'Show Marketing Profiles';
            }
        });
    });
</script>
@endsection
