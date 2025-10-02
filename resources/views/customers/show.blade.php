@extends('layouts.app')
@section('content')
<div class="container py-4">
    <h2 class="mb-4">Customer Detail</h2>

    <div class="card mb-4">
        <div class="card-body">
            <p><strong>Name:</strong> {{ $customer->name }}</p>
            <p><strong>Email:</strong> {{ $customer->email }}</p>
            <p><strong>Address:</strong><br>
                @php
                    $uniqueAddresses = $customer->vessels
                        ->map(fn($v) => $v->port_of_call ?? $v->address)
                        ->filter()
                        ->unique();
                @endphp

                @if($uniqueAddresses->count() > 0)
                    @foreach($uniqueAddresses as $addr)
                        {{ $addr }} <br>
                    @endforeach
                @else
                    -
                @endif
            </p>
            <p><strong>Assigned Staff:</strong> {{ $customer->assigned_staff }}</p>
            <p><strong>Last Contact Date:</strong> {{ $customer->last_followup_date ?? '-' }}</p>
            <p><strong>Next Follow-Up:</strong> {{ $customer->next_followup_date ?? '-' }}</p>
            <p><strong>Description:</strong> 
                @if($customer->description)
                    <span class="truncate">{{ $customer->description }}</span>
                    <span class="more-link" onclick="toggleDesc(this)">More</span>
                    <span class="full-text d-none">{{ $customer->description }}</span>
                @else
                    -
                @endif
            </p>
            <p><strong>Remark:</strong> 
                @if($customer->remark)
                    <span class="truncate">{{ $customer->remark }}</span>
                    <span class="more-link" onclick="toggleDesc(this)">More</span>
                    <span class="full-text d-none">{{ $customer->remark }}</span>
                @else
                    -
                @endif
            <p><strong>Revenue:</strong> 
                @php
                    $revenues = [];
                    $totalRevenueIDR = 0;

                    // Kurs konversi ke IDR
                    $exchangeRates = [
                        'USD' => 15000,
                        'EUR' => 16000,
                        'IDR' => 1,
                        // Tambah mata uang lain jika perlu
                    ];

                    foreach($customer->vessels as $vessel) {
                        $curr = $vessel->currency ?? 'IDR';
                        $amount = is_numeric($vessel->estimate_revenue ?? $vessel->potential_revenue) 
                                    ? ($vessel->estimate_revenue ?? $vessel->potential_revenue) 
                                    : 0;

                        // Total per mata uang
                        if(!isset($revenues[$curr])) $revenues[$curr] = 0;
                        $revenues[$curr] += $amount;

                        // Total konversi ke IDR
                        $rate = $exchangeRates[$curr] ?? 1; // default 1 kalau mata uang belum ada
                        $totalRevenueIDR += $amount * $rate;
                    }
                @endphp

                @if(count($revenues) > 0)
                    @foreach($revenues as $curr => $total)
                        {{ $curr }} {{ number_format($total, 0) }}@if(!$loop->last), @endif
                    @endforeach
                    <br>
                    <strong>Total in IDR:</strong> {{ number_format($totalRevenueIDR, 0) }}
                @else
                    -
                @endif
                </p>
        </div>
    </div>

    <h4 class="mb-3">Vessels</h4>
    <a href="{{ route('customers_vessels.create', $customer->id) }}" class="btn btn-primary mb-3">+ Add Vessel</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Vessel Name</th>
                <th>Status</th>
                <th>Potential Revenue</th>
                <th>Next Follow-Up</th>
            </tr>
        </thead>
        <tbody>
            @php
                $statusColors = [
                    'Follow up'        => 'badge bg-primary',
                    'On progress'      => 'badge bg-info text-dark',
                    'Request'          => 'badge bg-warning text-dark',
                    'Waiting approval' => 'badge bg-secondary',
                    'Approve'          => 'badge bg-success',
                    'On going'         => 'badge bg-dark',
                    'Quotation send'   => 'badge bg-primary',
                    'Done / Closing'   => 'badge bg-success',
                ];
            @endphp
            @forelse($customer->vessels as $vessel)
                <tr>
                    <td>{{ $vessel->vessel_name }}</td>
                    <td>
                        <span class="{{ $statusColors[$vessel->status] ?? 'badge bg-light text-dark' }}">
                            {{ $vessel->status }}
                        </span>
                    </td>
                    <td>
                        {{ $vessel->currency ?? 'IDR' }} 
                        {{ number_format($vessel->estimate_revenue ?? $vessel->potential_revenue ?? 0, 0) }}
                    </td>
                    <td>{{ $vessel->next_followup_date ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">No vessels found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <a href="{{ route('customers_vessels.index') }}" class="btn btn-secondary">Back to Customer List</a>
    <a href="{{ route('customers.index') }}" class="btn btn-secondary">Back to Marketing</a>
    <a href="{{ route('vessels.index') }}" class="btn btn-secondary">Back to Vessels List</a>
</div>

{{-- JS Expandable --}}
<script>
function toggleDesc(el) {
    let parent = el.closest('p');
    let trunc = parent.querySelector('.truncate');
    let full = parent.querySelector('.full-text');
    if (full.classList.contains('d-none')) {
        trunc.style.display = 'none';
        full.classList.remove('d-none');
        el.innerText = "Less";
    } else {
        trunc.style.display = 'inline-block';
        full.classList.add('d-none');
        el.innerText = "More";
    }
}
</script>
@endsection
