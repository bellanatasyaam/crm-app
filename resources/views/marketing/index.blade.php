@extends('layouts.app')

@section('title', 'Daftar Marketing')

@section('content')
<div class="container py-4">

    <style>
        .container {
            max-width: 100% !important;
        }

        /* === HEADER === */
        .dashboard-header {
            background: linear-gradient(90deg, #0ea5e9 0%, #0284c7 100%);
            color: #fff;
            padding: 15px 20px;
            border-radius: 12px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 25px;
        }

        .dashboard-header h2 {
            font-size: 22px;
            font-weight: 600;
            margin: 0;
        }

        .btn {
            border-radius: 8px;
            transition: 0.2s ease-in-out;
        }

        .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.15);
        }

        /* === TABEL === */
        .custom-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
            background: #fff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
            border-radius: 10px;
            overflow: hidden;
        }

        .custom-table thead {
            background: #f1f5f9;
        }

        .custom-table th,
        .custom-table td {
            border: 1px solid #dee2e6;
            padding: 10px 12px;
            text-align: center;
            vertical-align: middle;
        }

        .custom-table th {
            font-weight: 600;
            text-transform: capitalize;
        }

        .custom-table tbody tr:hover {
            background-color: #f8fafc;
        }

        .table-actions {
            display: flex;
            justify-content: center;
            gap: 5px;
        }

        /* === TEXT TRUNCATE === */
        .truncate-text {
            display: inline-block;
            max-width: 200px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            vertical-align: bottom;
        }

        .more-link {
            cursor: pointer;
            color: #0d6efd;
            font-size: 11px;
            margin-left: 5px;
            user-select: none;
        }

        /* === MARKETING PROFILES MODERN === */
        #marketingProfiles {
            display: none;
            padding: 20px;
        }

        #marketingProfiles h2 {
            text-align: center;
            font-weight: 700;
            margin-bottom: 30px;
            color: #0f172a;
            font-family: 'Poppins', sans-serif;
        }

        /* Grid layout */
        .profile-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            justify-content: center; /* ✅ Biar ketengah */
            justify-items: center;    /* ✅ Biar setiap card-nya juga rata tengah */
        }

        /* Card style */
        .profile-card {
            background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
            width: 100%;
            max-width: 340px;
        }

        .profile-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(14,165,233,0.2);
        }

        /* Image */
        .profile-card img {
            width: 100%;
            height: 220px;
            object-fit: cover;
        }

        /* Text section */
        .profile-info {
            padding: 15px 20px;
            text-align: left;
        }

        .profile-info h5 {
            font-weight: 600;
            color: #0f172a;
            margin-bottom: 5px;
        }

        .profile-info p {
            margin: 3px 0;
            color: #475569;
            font-size: 14px;
        }
    </style>

    {{-- HEADER --}}
    <div class="dashboard-header d-flex justify-content-between align-items-center">
        <h2>📈 Daftar Marketing</h2>
        <div class="d-flex gap-2">
            <a href="{{ route('marketing.create') }}" class="btn btn-primary btn-sm">+ Add Marketing</a>
            <button id="showProfilesBtn" class="btn btn-info btn-sm">Show Marketing Profiles</button>
            <a href="{{ route('dashboard') }}" class="btn btn-light btn-sm">Back to Master Menu</a>
        </div>
    </div>

    <!-- === MARKETING PROFILES GRID === -->
<div id="marketingProfiles">
    <h4 class="mb-3">Marketing Profiles</h4>

    <div class="profile-grid">
        @forelse ($marketingProfiles as $profile)
            <div class="profile-card">
                <img src="{{ $profile->photo_url ?? '/uploads/photos/default.jpg' }}" 
                     alt="Photo of {{ $profile->name }}">
                <div class="profile-info">
                    <h5>{{ $profile->name }}</h5>
                    <p><strong>Email:</strong> {{ $profile->email }}</p>
                    <p><strong>Phone:</strong> {{ $profile->phone ?? '-' }}</p>
                </div>
            </div>
        @empty
            <p class="text-center w-100 text-muted py-4">
                No marketing profile found.
            </p>
        @endforelse
    </div>
</div>

<style>
    #marketingProfiles {
        background: #fff;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        margin-bottom: 30px;
    }

    .profile-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        justify-content: center; /* ✅ Biar ketengah */
        justify-items: center;    /* ✅ Biar setiap card-nya juga rata tengah */
    }

    .profile-card {
        background: linear-gradient(135deg, #e3f2fd, #bbdefb);
        border-radius: 15px;
        overflow: hidden;
        text-align: center;
        padding: 20px;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .profile-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    .profile-card img {
        width: 90px;
        height: 90px;
        object-fit: cover;
        border-radius: 50%;
        margin-bottom: 15px;
        border: 3px solid #fff;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }

    .profile-info h5 {
        margin-bottom: 8px;
        font-weight: 600;
        color: #0d47a1;
    }

    .profile-info p {
        margin: 2px 0;
        color: #333;
        font-size: 14px;
    }
</style>


    {{-- TABEL --}}
    <div class="table-responsive mt-4">
        <table class="custom-table">
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Staff</th>
                    <th>Last Contact</th>
                    <th>Next FU</th>
                    <th>Status</th>
                    <th>Revenue</th>
                    <th>Vessels</th>
                    <th>Remark</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($marketingData as $m)
                    <tr>
                        <td>
                            <span class="truncate-text">{{ Str::limit($m->description ?? '-', 40) }}</span>
                            @if(strlen($m->description ?? '') > 40)
                                <span class="more-link" onclick="toggleText(this)">More</span>
                                <span class="full-text d-none">{{ $m->description }}</span>
                            @endif
                        </td>
                        <td>{{ $m->name ?? '-' }}</td>
                        <td>{{ $m->email ?? '-' }}</td>
                        <td>{{ $m->phone ?? '-' }}</td>
                        <td>{{ $m->staff->name ?? '-' }}</td>

                        <td>{{ $m->last_contact ?? '-' }}</td>
                        <td>{{ $m->next_fu ?? '-' }}</td>
                        <td>
                            @php
                                $statusColors = [
                                    'Follow up' => 'primary',
                                    'On going' => 'dark',
                                    'On progress' => 'info',
                                    'Quotation send' => 'secondary',
                                ];
                                $color = $statusColors[$m->status ?? ''] ?? 'light';
                            @endphp
                            <span class="badge bg-{{ $color }}">{{ $m->status ?? '-' }}</span>
                        </td>
                        <td>{{ $m->revenue ?? '-' }}</td>
                        <td>{{ $m->vessel ?? '-' }}</td>
                        <td>
                            <span class="truncate-text">{{ Str::limit($m->remark ?? '-', 40) }}</span>
                            @if(strlen($m->remark ?? '') > 40)
                                <span class="more-link" onclick="toggleText(this)">More</span>
                                <span class="full-text d-none">{{ $m->remark }}</span>
                            @endif
                        </td>
                        <td>
                            <div class="table-actions">
                                <a href="{{ route('marketing.profile', $m->id) }}" class="btn btn-info btn-sm">Detail</a>
                                <a href="{{ route('marketing.edit', $m->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('marketing.destroy', $m->id) }}" method="POST"
                                    onsubmit="return confirm('Yakin hapus data ini?')" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm">Del</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="12" class="text-center">No marketing data found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- PAGINATION --}}
    <div class="d-flex justify-content-center mt-3">
        {{ $marketingData->links() }}
    </div>
</div>

<script>
    function toggleText(el) {
        const row = el.closest('td');
        const trunc = row.querySelector('.truncate-text');
        const full = row.querySelector('.full-text');
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

    document.addEventListener('DOMContentLoaded', function() {
        const btn = document.getElementById('showProfilesBtn');
        const profiles = document.getElementById('marketingProfiles');
        const table = document.querySelector('.table-responsive');

        btn.addEventListener('click', function() {
            if (profiles.style.display === 'none' || profiles.style.display === '') {
                profiles.style.display = 'block';
                table.style.display = 'none';
                btn.innerText = 'Cancel';
            } else {
                profiles.style.display = 'none';
                table.style.display = 'block';
                btn.innerText = 'Show Marketing Profiles';
            }
        });
    });
</script>
@endsection
