<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Vessel;
use App\Models\User;
use Illuminate\Http\Request;

class VesselController extends Controller
{
    /**
     * Display a listing of the resource.
     * Optionally filtered by a specific company.
     */
    public function index(Company $company = null)
    {
        $this->authorize('viewAny', Vessel::class);

        $perPage = 10; 

        if ($company) {
            $vessels = $company->vessels()
                ->orderBy('id', 'desc')
                ->paginate($perPage);

            return view('vessels.index', [
                'vessels'  => $vessels,
                'company' => $company,
            ]);
        } else {
            $vessels = Vessel::with('company')
                ->orderBy('id', 'desc')
                ->paginate($perPage);

            return view('vessels.index', [
                'vessels'  => $vessels,
                'company' => null,
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Company $company = null)
    {
        $this->authorize('create', Vessel::class);

        $companies = Company::all();

        return view('vessels.create', [
            'companies' => $companies,
            'company'  => $company,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Company $company = null)
    {
        $this->authorize('create', Vessel::class);

        $data = $request->validate([
            'name'             => 'required|string|max:255',
            'imo_number'       => 'nullable|string|max:255',
            'call_sign'        => 'nullable|string|max:255',
            'port_of_call'     => 'nullable|string|max:255',
            'flag'             => 'nullable|string|max:255', 
            'vessel_type'      => 'required|string|max:255',
            'gross_tonnage'    => 'nullable|numeric|max:99999999.99',
            'net_tonnage'      => 'nullable|numeric|max:99999999.99',
            'year_built'       => 'nullable|integer|digits:4',
            'status'           => 'required|in:active,maintenance,retired', 
            'company_id'       => 'nullable|exists:companies,id',
        ]);

        if ($company) {
            $data['company_id'] = $company->id;
        }

        $data['created_by'] = auth()->id();

        $vessel = new Vessel($data);
        $vessel->save();

        // 🔹 lanjut seperti biasa
        $redirectRoute = $company 
            ? route('companies.vessels.index', $company->id) 
            : route('vessels.index');

        return redirect($redirectRoute)->with('success', 'Vessel added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Vessel $vessel)
    {
        $this->authorize('view', $vessel);
        $company = Company::with('vessels.assignedStaff')
            ->findOrFail($vessel->company_id);

        $vessel->load('company');

        return view('vessels.show', [
            'vessel' => $vessel,
            'company' => $company,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vessel $vessel)
    {
        $this->authorize('update', $vessel);

        $companies = Company::all();
        $staffs    = User::where('role', '!=', 'super_admin')->get();

        return view('vessels.edit', [
            'vessel'    => $vessel,
            'companies' => $companies,
            'staffs'    => $staffs,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Vessel $vessel)
    {
        $this->authorize('update', $vessel);

        $data = $request->validate([
            'name'             => 'required|string|max:255',
            'imo_number'       => 'nullable|string|max:255',
            'call_sign'        => 'nullable|string|max:255',
            'port_of_call'     => 'nullable|string|max:255',
            'flag'             => 'nullable|string|max:255',
            'vessel_type'      => 'required|string|max:255',
            'gross_tonnage'    => 'nullable|numeric|max:99999999.99',
            'net_tonnage'      => 'nullable|numeric|max:99999999.99',
            'year_built'       => 'nullable|integer|digits:4',
            'status'           => 'required|in:active,maintenance,retired',
            'company_id'       => 'nullable|exists:companies,id', // <-- diubah di sini
        ]);

        $vessel->update($data);

        return redirect()->route('vessels.index')->with('success', 'Vessel updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vessel $vessel)
    {
        $this->authorize('delete', $vessel);

        $vessel->delete();

        return redirect()
            ->route('vessels.index')
            ->with('success', 'Vessel deleted successfully.');
    }
}
