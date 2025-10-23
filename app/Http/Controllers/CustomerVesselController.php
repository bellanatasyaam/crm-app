<?php

namespace App\Http\Controllers;

use App\Models\CustomerVessel;
use App\Models\User;
use App\Models\Company;
use App\Models\Vessel;
use Illuminate\Http\Request;

class CustomerVesselController extends Controller
{

    public function index()
    {
        $companies = Company::with('customerVessels.vessel')->paginate(10);
        return view('customers_vessels.index', compact('companies'));
    }

    public function show($id)
    {
        $vessel = Vessel::with('company', 'assignedStaff')->findOrFail($id);
        return view('customers_vessels.show', compact('vessel'));
    }

    public function create(Company $company = null)
    {
        $staffs    = User::where('role', 'staff')->get();
        $ports     = Vessel::whereNotNull('port_of_call')->distinct()->pluck('port_of_call');
        $companies = Company::all();

        return view('customers_vessels.create', compact('company', 'companies', 'staffs', 'ports'));
    }

    public function store(Request $request, Company $company = null)
    {
        $data = $request->validate([
            'company_id'       => 'nullable|exists:companies,id',
            'vessel_name'       => 'required|string|max:255',
            'imo_number'        => 'nullable|string|max:255',
            'flag'              => 'nullable|string|max:255',
            'type'              => 'nullable|string|max:255',
            'port_of_call'      => 'nullable|string|max:255',
            'estimate_revenue'  => 'nullable|numeric',
            'currency'          => 'nullable|string|max:10',
            'description'       => 'nullable|string',
            'remark'            => 'nullable|string',
            'status'            => 'nullable|string|max:50',
            'last_contact'      => 'nullable|date',
            'next_follow_up'    => 'nullable|date',
            'assigned_staff_id' => 'nullable|exists:users,id',
        ]);

        if ($company && $company->exists) {
            $data['company_id'] = $company->id;
        } else {
            $data['company_id'] = $request->input('company_id');
        }

        Vessel::create($data);

        if ($company && $company->exists) {
            return redirect()->route('companies.detail', $company->id)
                             ->with('success', 'Vessel berhasil ditambahkan.');
        } else {
            return redirect()->route('customers_vessels.index')
                             ->with('success', 'Vessel berhasil ditambahkan.');
        }
    }

    public function edit($id)
    {
        $customerVessel = CustomerVessel::find($id);

        if (!$customerVessel) {
            return redirect()->route('customers_vessels.index')
                            ->with('error', 'Customer Vessel not found!');
        }

        $companies = Company::all();
        $vessels = Vessel::all();

        return view('customers_vessels.edit', compact('customerVessel', 'companies', 'vessels'));
    }

    public function update(Request $request, $id)
    {
        $customerVessel = CustomerVessel::findOrFail($id);

        $data = $request->validate([
            'company_id'        => 'required|exists:customers,id',
            'vessel_id'          => 'required|exists:vessels,id',
            'status'             => 'nullable|string|max:50',
            'potential_revenue'  => 'nullable|numeric',
            'currency'           => 'nullable|string|max:10',
            'last_followup_date' => 'nullable|date',
            'next_followup_date' => 'nullable|date',
            'description'        => 'nullable|string',
            'remark'             => 'nullable|string',
        ]);

        $customerVessel->update($data);

        return redirect()->route('customers_vessels.index')
            ->with('success', 'Customer Vessel updated successfully!');
    }

    public function showProfile(Company $company)
    {
        $company->load('vessels.assignedStaff');
        return view('companies.profile', compact('company'));
    }

    public function destroy(Company $company, Vessel $vessel)
    {
        if ($vessel->company_id !== $company->id) {
            abort(404);
        }

        $vessel->delete();

        return redirect()->route('companies.detail', $company->id)
                         ->with('success', 'Vessel berhasil dihapus.');
    }

    public function profile($id)
    {
        $company = Company::with('logs')->findOrFail($id);
        return view('companies.profile', compact('company'));
    }

    
}
