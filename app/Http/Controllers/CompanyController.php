<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Vessel;
use App\Models\Log;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Company::query();

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                ->orWhere('email', 'like', "%$search%")
                ->orWhere('phone', 'like', "%$search%")
                ->orWhere('website', 'like', "%$search%")
                ->orWhere('tax_id', 'like', "%$search%")
                ->orWhere('type', 'like', "%$search%")
                ->orWhere('industry', 'like', "%$search%")
                ->orWhere('customer_tier', 'like', "%$search%")
                ->orWhere('status', 'like', "%$search%")
                ->orWhere('address', 'like', "%$search%")
                ->orWhere('city', 'like', "%$search%")
                ->orWhere('country', 'like', "%$search%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('staff_id') && auth()->user()->role === 'admin') {
            $query->where('assigned_staff_id', $request->staff_id);
        }

        if ($request->filled('search_date')) {
            try {
                $date = \Carbon\Carbon::createFromFormat('d/m/Y', $request->search_date)
                    ->format('Y-m-d');

                $query->whereDate('created_at', $date);

            } catch (\Exception $e) {
            }
        }

        $companies = $query->with('vessels')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $summary = [
            'total_customers' => Company::count(),
            'by_status' => Company::select('status', DB::raw('COUNT(*) as total'))
                ->groupBy('status')
                ->pluck('total', 'status'),
        ];

        $stats = [
            'active'   => Company::where('status', 'active')->count(),
            'inactive' => Company::where('status', 'inactive')->count(),
        ];

        $staff_stats = Company::whereNotNull('assigned_staff_id')
            ->with('assignedStaff')
            ->select('assigned_staff_id', DB::raw('COUNT(*) as total'))
            ->groupBy('assigned_staff_id')
            ->get()
            ->mapWithKeys(fn($c) => [$c->assignedStaff->name ?? 'Unassigned' => $c->total])
            ->toArray();
        
            $staffs = User::where('role', 'staff')->get();

        return view('companies.index', compact(
            'companies',
            'summary',
            'stats',
            'staff_stats',
            'staffs'
        ));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Company::class);

        $companies = Company::all();
        $unassignedVessels = Vessel::whereNull('company_id')->get();
        $staffs = User::where('role', 'staff')->pluck('name', 'id');

        return view('companies.create', [
            'companies' => $companies,
            'vessels' => $unassignedVessels,
            'staffs' => $staffs,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Company::class);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'website' => 'nullable|string|max:255',
            'tax_id' => 'nullable|string|max:100',
            'type' => 'nullable|string|max:100',
            'industry' => 'nullable|string|max:100',
            'customer_tier' => 'nullable|string|max:50',
            'status' => 'nullable|string|max:50',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'last_followup_date' => 'nullable|date',
            'next_followup_date' => 'nullable|date',
            'remark' => 'nullable|string',
            'assigned_staff_id' => 'nullable|exists:users,id',
        ]);

        if (auth()->check() && !$request->filled('assigned_staff_id')) {
            $validated['assigned_staff_id'] = auth()->id();
        }

        $company = Company::create($validated);

        return redirect()->route('companies.show', $company->id)
            ->with('success', 'Customer created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Company $company)
    {
        $company->load(['vessels', 'logs', 'assignedStaff']);

        // Revenue summary
        $revenues = [];
        $totalRevenueIDR = 0;
        $exchangeRates = ['USD' => 15000, 'EUR' => 16000, 'IDR' => 1];

        foreach ($company->vessels as $vessel) {
            $curr = $vessel->currency ?? 'IDR';
            $amount = is_numeric($vessel->potential_revenue ?? 0)
                ? $vessel->potential_revenue
                : 0;
            $revenues[$curr] = ($revenues[$curr] ?? 0) + $amount;
            $totalRevenueIDR += $amount * ($exchangeRates[$curr] ?? 1);
        }

        return view('companies.show', compact('company', 'revenues', 'totalRevenueIDR'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Company $company)
    {
        $this->authorize('update', $company);

        $staffs = User::where('role', 'staff')->pluck('name', 'id');

        return view('companies.edit', compact('company', 'staffs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Company $company)
    {
        $this->authorize('update', $company);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'website' => 'nullable|string|max:255',
            'tax_id' => 'nullable|string|max:100',
            'type' => 'nullable|string|max:100',
            'industry' => 'nullable|string|max:100',
            'customer_tier' => 'nullable|string|max:50',
            'status' => 'nullable|string|max:50',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'last_followup_date' => 'nullable|date',
            'next_followup_date' => 'nullable|date',
            'remark' => 'nullable|string',
            'assigned_staff_id' => 'nullable|exists:users,id',
        ]);

        $company->update($validated);

        return redirect()->route('companies.show', $company->id)
            ->with('success', 'Customer updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Company $company)
    {
        $this->authorize('delete', $company);

        $company->delete();

        Log::create([
            'company_id' => $company->id,
            'user_id' => auth()->id(),
            'activity' => 'Soft deleted company: ' . $company->name,
            'activity_type' => 'delete',
            'activity_detail' => 'Customer (' . $company->code . ') was soft deleted.',
        ]);

        return redirect()->route('companies.index')->with('success', 'Company deleted successfully.');
    }

    /**
     * Print all companies to PDF.
     */
    public function print(Request $request)
{
    $query = Company::with('assignedStaff');

    // Filter staff (only admin sees all)
    if ($request->staff_id && auth()->user()->role === 'admin') {
        $query->where('assigned_staff_id', $request->staff_id);
    }

    // Staff only sees their own data
    if (auth()->user()->role !== 'admin') {
        $query->where('assigned_staff_id', auth()->user()->id);
    }

    $companies = $query->get();

    $staffs = User::where('role', 'staff')->get();

    // 🔥 Generate PDF dari blade
    $pdf = Pdf::loadView('companies.print', [
        'companies' => $companies,
        'staffs' => $staffs
    ])->setPaper('A4', 'portrait');

    // 🔥 TAMPILKAN SEPERTI VIEWER (bukan download)
    return $pdf->stream('customer-report.pdf');
}
 
    /**
     * Print single company profile to PDF.
     */
    public function printSingle(Company $company)
    {
        $this->authorize('view', $company);

        Log::create([
            'company_id' => $company->id,
            'user_id' => auth()->id(),
            'activity' => 'Printed profile for company: ' . $company->name,
            'activity_type' => 'print',
            'activity_detail' => 'Exported company profile to PDF',
        ]);

        $pdf = PDF::loadView('companies.print_single', compact('company'))
            ->setPaper('A4', 'portrait');

        return $pdf->stream('company-' . $company->name . '.pdf');
    }

    /**
     * Get vessels associated with a company via API.
     */
    public function getVessels(Company $company)
    {
        $this->authorize('view', $company);

        return response()->json($company->vessels);
    }
}
