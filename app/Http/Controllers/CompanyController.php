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
            'name'          => 'required|string|max:255',
            'type'          => 'nullable|string|max:100',
            'industry'      => 'nullable|string|max:100',
            'phone'         => 'nullable|string|max:20',
            'email'         => 'nullable|email|max:255',
            'website'       => 'nullable|url|max:255',
            'address'       => 'nullable|string',
            'city'          => 'nullable|string|max:100',
            'country'       => 'nullable|string|max:100',
            'tax_id'        => 'nullable|string|max:50',
            'customer_tier' => 'nullable|string|in:regular,vip,premium',
            'status'        => 'nullable|string|in:active,inactive',
            
        ]);

        $company = Company::create($validated);

        Log::create([
            'company_id'    => $company->id,
            'user_id'        => auth()->id(),
            'activity'       => 'Created company: ' . $company->name,
            'activity_type'  => 'create',
            'activity_detail'=> 'New company created with code ' . $company->code,
        ]);

        

        return redirect()->route('companies.index')->with('success', 'Comapny created!');
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
            'name'          => 'required|string|max:255',
            'type'          => 'nullable|string|max:100',
            'industry'      => 'nullable|string|max:100',
            'phone'         => 'nullable|string|max:20',
            'email'         => 'nullable|email|max:255',
            'website'       => 'nullable|url|max:255',
            'address'       => 'nullable|string',
            'city'          => 'nullable|string|max:100',
            'country'       => 'nullable|string|max:100',
            'tax_id'        => 'nullable|string|max:50',
            'customer_tier' => 'nullable|string|in:regular,vip,premium',
            'status'        => 'nullable|string|in:active,inactive',
        ]);
        
        $changes = [];
        $fillableKeys = array_keys($validated); 
        
        foreach ($validated as $field => $newValue) {
            $oldValue = $company->getOriginal($field);

            
            if ($oldValue != $newValue) {
                $changes[] = ucfirst($field)." from '".($oldValue ?? '-')."' to '".($newValue ?? '-')."'";
            }
        }
        
        $company->update($validated);

        if (!empty($changes)) {
            Log::create([
                'company_id'     => $company->id,
                'user_id'         => auth()->id(),
                'activity'        => 'Updated Company details: '.$company->name,
                'activity_type'   => 'update',
                'activity_detail' => implode(', ', $changes),
            ]);
        }
        
        

        return redirect()->route('companies.index')->with('success', 'Company updated successfully.');
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

    /**
     * Display the specified resource.
     */
    public function show(Company $company)
    {
        $this->authorize('view', $company);

        
        $company->load('vessels');

        
        

        return view('companies.show', compact('company'));
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
