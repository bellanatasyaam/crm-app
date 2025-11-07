<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Vessel;
use App\Models\Log; 

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; 
use PDF; 

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        
        $query = Company::query();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('code', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        

        $companies = $query->with('vessels')
            ->orderBy('created_at', 'desc') 
            ->paginate(10);

        
        $summary = [
            'total_customers' => Company::count(),
            'by_status' => Company::select('status', DB::raw('COUNT(*) as total'))
                ->groupBy('status')
                ->pluck('total','status'),
            
            
        ];

        

        $stats = [
            'active'    => Company::where('status', 'active')->count(),
            'inactive'  => Company::where('status', 'inactive')->count(),
            'tier_reg'  => Company::where('customer_tier', 'regular')->count(),
            'tier_vip'  => Company::where('customer_tier', 'vip')->count(),
            
        ];

        return view('companies.index', compact('companies', 'summary', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Company::class);

        $companies = Company::all();
        
        $unassignedVessels = Vessel::whereNull('company_id')->get(); 
        
        return view('companies.create', [
            'companies' => $companies,
            'vessels' => $unassignedVessels, 
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
            'assigned_staff_id' => 'nullable|integer',
            'assigned_staff' => 'nullable|string|max:255',
            'assigned_staff_email' => 'nullable|email|max:255',
            'last_followup_date' => 'nullable|date',
            'next_followup_date' => 'nullable|date',
            'remark' => 'nullable|string',
        ]);

        $company = Company::create($validated);

        return redirect()->route('companies.show', $company->id)
            ->with('success', 'Customer created successfully!');
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Company $company)
    {
        $this->authorize('update', $company);

        
        $vessels = Vessel::where('company_id', $company->id)->get();
        
        $unassignedVessels = Vessel::whereNull('company_id')->get();
        
        return view('companies.edit', compact('company', 'vessels', 'unassignedVessels'));
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
            'assigned_staff_id' => 'nullable|integer',
            'assigned_staff' => 'nullable|string|max:255',
            'assigned_staff_email' => 'nullable|email|max:255',
            'last_followup_date' => 'nullable|date',
            'next_followup_date' => 'nullable|date',
            'remark' => 'nullable|string',
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

        $companyName = $company->name;
        $companyId   = $company->id;
        $companyCode = $company->code;

        
        

        $company->delete(); 

        Log::create([
            'company_id'    => $companyId,
            'user_id'        => auth()->id(),
            'activity'       => 'Soft deleted company: ' . $companyName,
            'activity_type'  => 'delete',
            'activity_detail'=> 'Customer ('.$companyCode.') was soft deleted.',
        ]);

        return redirect()->route('companies.index')->with('success', 'Company deleted successfully.');
    }

    public function show($id)
    {
        $company = \App\Models\Company::with(['assignedStaff', 'vessels', 'logs'])->findOrFail($id);

        // === Assigned Staff Info ===
        $company->assigned_staff = $company->assignedStaff->name ?? '-';
        $company->assigned_staff_email = $company->assignedStaff->email ?? '-';

        // === Follow-Up Info (langsung dari table companies) ===
        $company->last_follow_up = $company->last_follow_up ?? '-';
        $company->next_follow_up = $company->next_follow_up ?? '-';

        // === Remark ===
        $company->remark = $company->remark ?? '-';

        // === Revenue Summary ===
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
     * Print all companies list.
     */
    public function print()
    {
        $user = auth()->user();

        
        
        $companies = Company::all(); 

        Log::create([
            'company_id'    => null,
            'user_id'        => auth()->id(),
            'activity'       => 'Printed all companies list',
            'activity_type'  => 'print',
            'activity_detail'=> 'Exported companies list to PDF',
        ]);

        $pdf = PDF::loadView('companies.print', compact('companies'))
            ->setPaper('A4', 'landscape');

        return $pdf->stream('companies.pdf');
    }

    /**
     * Print single company profile.
     */
    public function printSingle(Company $company)
    {
        $this->authorize('view', $company);

        Log::create([
            'company_id'    => $company->id,
            'user_id'        => auth()->id(),
            'activity'       => 'Printed profile for company: ' . $company->name,
            'activity_type'  => 'print',
            'activity_detail'=> 'Exported company profile to PDF',
        ]);

        $pdf = PDF::loadView('companies.print_single', compact('company'))
            ->setPaper('A4', 'portrait');

        return $pdf->stream('company-'.$company->name.'.pdf');
    }

    /**
     * Get vessels associated with a company via API.
     */
    public function getVessels($id)
    {
        $company = Company::findOrFail($id);
        $this->authorize('view', $company);

        
        $vessels = Vessel::where('company_id', $id)->get();
        return response()->json($vessels);
    }
}
