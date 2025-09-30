@extends('layouts.app')

@section('content')
<div class="flex min-h-screen bg-gray-100">
    <!-- Sidebar -->
    <aside class="w-64 bg-indigo-700 text-white flex flex-col p-4">
        <h1 class="text-2xl font-bold mb-6">⚓ CRM</h1>
        <nav class="flex-1">
            <ul class="space-y-2">
                <li><a href="{{ url('/home') }}" class="block py-2 px-3 rounded hover:bg-indigo-600">🏠 Dashboard</a></li>
                <li><a href="{{ route('customers.index') }}" class="block py-2 px-3 rounded bg-indigo-900">👥 Customers</a></li>
                <li><a href="#" class="block py-2 px-3 rounded hover:bg-indigo-600">📊 Reports</a></li>
            </ul>
        </nav>
        <div class="mt-auto">
            <p class="text-sm">{{ Auth::user()->name }}</p>
            <a href="{{ route('logout') }}" class="text-sm text-red-300 hover:text-red-100">Logout</a>
        </div>
    </aside>

    <!-- Main content -->
    <main class="flex-1 p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Customer Dashboard</h2>
            <a href="{{ route('customers.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-500">+ Add Customer</a>
        </div>

        <!-- Filter Form -->
        <form action="{{ route('customers.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <input type="text" name="search" placeholder="Search customer..." value="{{ request('search') }}"
                   class="border rounded-lg p-2 w-full">
            <select name="status" class="border rounded-lg p-2">
                <option value="">-- Status --</option>
                @foreach(['Lead','Quotation Sent','Negotiation','On Going Vessel Call','Pending Payment','Closing'] as $status)
                    <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>{{ $status }}</option>
                @endforeach
            </select>
            <input type="text" name="assigned_staff" placeholder="Filter by Staff" value="{{ request('assigned_staff') }}"
                   class="border rounded-lg p-2">
            <button type="submit" class="bg-indigo-600 text-white rounded-lg p-2 hover:bg-indigo-500">Filter</button>
        </form>

        <!-- Summary Cards -->
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4 mb-6">
            <div class="bg-white shadow rounded-lg p-4 text-center">
                <h4 class="text-sm text-gray-500">Total Customers</h4>
                <p class="text-xl font-bold">{{ $summary['total_customers'] ?? 0 }}</p>
            </div>
            <div class="bg-blue-100 text-blue-800 shadow rounded-lg p-4 text-center">
                <h4 class="text-sm">Leads</h4>
                <p class="text-xl font-bold">{{ $summary['by_status']['Lead'] ?? 0 }}</p>
            </div>
            <div class="bg-indigo-100 text-indigo-800 shadow rounded-lg p-4 text-center">
                <h4 class="text-sm">Quotation Sent</h4>
                <p class="text-xl font-bold">{{ $summary['by_status']['Quotation Sent'] ?? 0 }}</p>
            </div>
            <div class="bg-yellow-100 text-yellow-800 shadow rounded-lg p-4 text-center">
                <h4 class="text-sm">Negotiation</h4>
                <p class="text-xl font-bold">{{ $summary['by_status']['Negotiation'] ?? 0 }}</p>
            </div>
            <div class="bg-red-100 text-red-800 shadow rounded-lg p-4 text-center">
                <h4 class="text-sm">Pending Payment</h4>
                <p class="text-xl font-bold">{{ $summary['by_status']['Pending Payment'] ?? 0 }}</p>
            </div>
            <div class="bg-green-100 text-green-800 shadow rounded-lg p-4 text-center">
                <h4 class="text-sm">Closing</h4>
                <p class="text-xl font-bold">{{ $summary['by_status']['Closing'] ?? 0 }}</p>
            </div>
        </div>

        <!-- Customer Table -->
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="p-3 border">Name</th>
                        <th class="p-3 border">Staff</th>
                        <th class="p-3 border">Last Contact</th>
                        <th class="p-3 border">Next Follow-Up</th>
                        <th class="p-3 border">Status</th>
                        <th class="p-3 border">Revenue</th>
                        <th class="p-3 border">Description</th>
                        <th class="p-3 border">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($customers as $c)
                        <tr class="hover:bg-gray-50">
                            <td class="p-3 border">{{ $c->name }}</td>
                            <td class="p-3 border">{{ $c->assigned_staff }}</td>
                            <td class="p-3 border">{{ $c->last_followup_date ?? '-' }}</td>
                            <td class="p-3 border">{{ $c->next_followup_date ?? '-' }}</td>
                            <td class="p-3 border">
                                <span class="px-2 py-1 rounded text-xs font-medium
                                    @switch($c->status)
                                        @case('Lead') bg-blue-100 text-blue-800 @break
                                        @case('Quotation Sent') bg-indigo-100 text-indigo-800 @break
                                        @case('Negotiation') bg-yellow-100 text-yellow-800 @break
                                        @case('On Going Vessel Call') bg-gray-200 text-gray-800 @break
                                        @case('Pending Payment') bg-red-100 text-red-800 @break
                                        @case('Closing') bg-green-100 text-green-800 @break
                                        @default bg-gray-100 text-gray-800
                                    @endswitch">
                                    {{ $c->status }}
                                </span>
                            </td>
                            <td class="p-3 border">{{ $c->currency }} {{ number_format($c->potential_revenue, 0) }}</td>
                            <td class="p-3 border">{{ $c->description ?? '-' }}</td>
                            <td class="p-3 border flex gap-2">
                                <a href="{{ route('customers.edit', $c->id) }}" class="bg-yellow-400 px-2 py-1 rounded text-xs">Edit</a>
                                <form action="{{ route('customers.destroy', $c->id) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button class="bg-red-500 text-white px-2 py-1 rounded text-xs">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="p-3 border-t">
                {{ $customers->links() }}
            </div>
        </div>
    </main>
</div>
@endsection
