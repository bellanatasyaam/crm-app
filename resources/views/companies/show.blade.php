@extends('layouts.app')
@section('content')
<div class="container py-4">

    <style>
        .container { max-width: 100% !important; }

        /* HEADER */
        .dashboard-header {
            background: linear-gradient(90deg, #007bff 0%, #00b4d8 100%);
            color: #fff;
            padding: 15px 20px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .dashboard-header h2 {
            font-size: 18px;
            font-weight: 600;
        }

        /* CARD SECTIONS */
        .info-section {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.08);
            padding: 25px;
            margin-bottom: 25px;
        }

        h4.section-title {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 15px;
            border-bottom: 2px solid #e9ecef;
            padding-bottom: 5px;
            color: #007bff;
        }

        p {
            margin-bottom: 8px;
        }

        .badge {
            font-size: 12px;
            padding: 5px 8px;
        }

        /* BUTTONS */
        .btn {
            border-radius: 8px;
            transition: 0.2s ease-in-out;
        }
        .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 6px rgba(0,0,0,0.15);
        }

        .truncate {
            display: inline-block;
            max-width: 80%;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .more-link {
            color: #007bff;
            cursor: pointer;
            font-weight: 500;
            margin-left: 5px;
        }
    </style>

    <!-- HEADER -->
    <div class="dashboard-header d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Customer Detail: {{ $company->name }}</h2>
        <a href="{{ route('companies.index') }}" class="btn btn-outline-light btn-sm">
            ← Back to List
        </a>
    </div>

    {{-- === COMPANY INFO === --}}
    <div class="info-section">
        <h4 class="section-title">🧾 Company Information</h4>

        <div class="row">
            <div class="col-md-6">
                <p><strong>Name:</strong> {{ $company->name }}</p>
                <p><strong>Email:</strong> {{ $company->email ?? '-' }}</p>
                <p><strong>Phone:</strong> {{ $company->phone ?? '-' }}</p>
                <p><strong>Website:</strong> 
                    @if($company->website)
                        <a href="{{ $company->website }}" target="_blank">{{ $company->website }}</a>
                    @else
                        -
                    @endif
                </p>
                <p><strong>Tax ID / NPWP:</strong> {{ $company->tax_id ?? '-' }}</p>
            </div>

            <div class="col-md-6">
                <p><strong>Type:</strong> {{ ucfirst($company->type) }}</p>
                <p><strong>Industry:</strong> {{ $company->industry ?? '-' }}</p>
                <p><strong>Tier:</strong> {{ ucfirst($company->customer_tier) ?? '-' }}</p>
                <p><strong>Status:</strong> 
                    <span class="badge {{ $company->status == 'active' ? 'bg-success' : 'bg-secondary' }}">
                        {{ ucfirst($company->status) }}
                    </span>
                </p>
            </div>
        </div>
    </div>

    {{-- === LOCATION & STAFF === --}}
    <div class="info-section">
        <h4 class="section-title">📍 Location & Staff</h4>
        <div class="row">
            <div class="col-md-6">
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
                        {{ $company->address ?? '-' }}
                    @endif
                </p>
                <p><strong>City:</strong> {{ $company->city ?? '-' }}</p>
                <p><strong>Country:</strong> {{ $company->country ?? '-' }}</p>
            </div>
            <div class="col-md-6">
                <p><strong>Assigned Staff:</strong> {{ $company->assigned_staff ?? '-' }}</p>
                <p><strong>Staff Email:</strong> {{ $company->assigned_staff_email ?? '-' }}</p>
                <p><strong>Last Follow-Up:</strong>
                    @if(!empty($company->last_followup_date) && $company->last_followup_date != '-')
                        {{ \Carbon\Carbon::parse($company->last_followup_date)->format('d M Y') }}
                    @else
                        -
                    @endif
                </p>

                <p><strong>Next Follow-Up:</strong>
                    @if(!empty($company->next_followup_date) && $company->next_followup_date != '-')
                        {{ \Carbon\Carbon::parse($company->next_followup_date)->format('d M Y') }}
                    @else
                        -
                    @endif
                </p>
            </div>
        </div>
    </div>

    {{-- === REMARKS & DESCRIPTION === --}}
    <div class="info-section">
        <h4 class="section-title">🗒️ Remarks</h4>
        <p><strong>Remark:</strong>
            @if($company->remark)
                <span class="truncate">{{ $company->remark }}</span>
                <span class="more-link" onclick="toggleDesc(this)">More</span>
                <span class="full-text d-none">{{ $company->remark }}</span>
            @else
                -
            @endif
        </p>
    </div>

    {{-- === VESSEL LIST === --}}
    <div class="info-section">
        <h4 class="section-title">🚢 Related Vessels</h4>
        <a href="{{ route('customers_vessels.create', $company->id) }}" class="btn btn-primary mb-3">+ Add Vessel</a>

        <table class="table table-bordered align-middle">
            <thead class="table-light">
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
                        'Follow up' => 'badge bg-primary',
                        'On progress' => 'badge bg-info text-dark',
                        'Request' => 'badge bg-warning text-dark',
                        'Waiting approval' => 'badge bg-secondary',
                        'Approve' => 'badge bg-success',
                        'On going' => 'badge bg-dark',
                        'Quotation send' => 'badge bg-primary',
                        'Done / Closing' => 'badge bg-success',
                    ];
                @endphp
                @forelse($company->vessels as $vessel)
                    <tr>
                        <td>{{ $vessel->vessel_name }}</td>
                        <td><span class="{{ $statusColors[$vessel->status] ?? 'badge bg-light text-dark' }}">{{ $vessel->status }}</span></td>
                        <td>{{ $vessel->currency ?? 'IDR' }} {{ number_format($vessel->estimate_revenue ?? $vessel->potential_revenue ?? 0, 0) }}</td>
                        <td>{{ $vessel->next_followup_date ? \Carbon\Carbon::parse($vessel->next_followup_date)->format('d M Y') : '-' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center">No vessels found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- BUTTONS --}}
    <div class="d-flex justify-content-end gap-2">
        <a href="{{ route('companies.edit', $company->id) }}" class="btn btn-info px-4">✏️ Edit Customer</a>
        <a href="{{ route('companies.index') }}" class="btn btn-secondary px-4">← Back to List</a>
    </div>

</div>


{{-- JS Expand Description --}}
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
