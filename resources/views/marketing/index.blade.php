@extends('layouts.app')

@section('title', 'Daftar Marketing')

@section('content')
<div class="container py-4">

    <style>
        .dashboard-header {
            background: linear-gradient(90deg, #0ea5e9 0%, #0284c7 100%);
            color: #fff;
            padding: 15px 20px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .dashboard-header h2 { font-size: 20px; font-weight: 600; margin: 0; }

        table.custom-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }
        table.custom-table th, table.custom-table td {
            border: 1px solid #dee2e6;
            padding: 8px 10px;
            vertical-align: middle;
        }
        table.custom-table th { background: #f8f9fa; font-weight: 600; }
        table.custom-table td { background: #fff; }

        .btn { border-radius: 8px; transition: 0.2s ease-in-out; }
        .btn:hover { transform: translateY(-1px); box-shadow: 0 2px 6px rgba(0,0,0,0.15); }
    </style>

    {{-- Header --}}
    <div class="dashboard-header d-flex justify-content-between align-items-center">
        <h2>📈 Daftar Marketing</h2>
        <a href="{{ route('marketing.create') }}" class="btn btn-primary btn-sm">+ Add Marketing</a>
    </div>

    {{-- Table --}}
    <div class="table-responsive">
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
                @forelse($marketingList as $index => $marketing)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $marketing->name }}</td>
                    <td>{{ $marketing->email }}</td>
                    <td>
                        <a href="{{ route('profile.view', $marketing->id) }}" class="btn btn-info btn-sm">View</a>
                        <a href="{{ route('users.edit', $marketing->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('users.destroy', $marketing->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Yakin hapus user ini?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="text-center text-muted">Belum ada data marketing.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection
