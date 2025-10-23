@extends('layouts.app')
@section('content')
<div class="container py-4">
    <h2 class="mb-4">Customer Detail</h2>

    <div class="card mb-4">
        <div class="card-body">
            <p><strong>Name:</strong> {{ $company->name }}</p>
            <p><strong>Email:</strong> {{ $company->email }}</p>
            <p><strong>Address:</strong><br>
                @php
                    $uniqueAddresses = $company->vessels
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
            <p><strong>Assigned Staff:</strong> {{ $company->assigned_staff }}</p>
            <p><strong>Last Contact Date:</strong> {{ $company->last_followup_date ?? '-' }}</p>
            <p><strong>Next Follow-Up:</strong> {{ $company->next_followup_date ?? '-' }}</p>
            <p><strong>Description:</strong> 
                @if($company->description)
                    <span class="truncate">{{ $company->description }}</span>
                    <span class="more-link" onclick="toggleDesc(this)">More</span>
                    <span class="full-text d-none">{{ $company->description }}</span>
                @else
                    -
                @endif
            </p>
            <p><strong>Remark:</strong> 
                @if($company->remark)
                    <span class="truncate">{{ $company->remark }}</span>
                    <span class="more-link" onclick="toggleDesc(this)">More</span>
                    <span class="full-text d-none">{{ $company->remark }}</span>
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

                    foreach($company->vessels as $vessel) {
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
    <a href="{{ route('customers_vessels.create', $company->id) }}" class="btn btn-primary mb-3">+ Add Vessel</a>

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
            @forelse($company->vessels as $vessel)
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
    <a href="{{ route('companies.index') }}" class="btn btn-secondary">Back to Marketing</a>
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
