<?php

namespace App\Http\Controllers;

use App\Models\CustomerVessel;
use App\Models\User;
use App\Models\Customer;
use App\Models\Vessel;
use Illuminate\Http\Request;

class CustomerVesselController extends Controller
{

    public function index()
    {
        $customers = Customer::with('customerVessels.vessel')->paginate(10);
        return view('customers_vessels.index', compact('customers'));
    }

    public function show($id)
    {
        $vessel = Vessel::with('customer', 'assignedStaff')->findOrFail($id);
        return view('customers_vessels.show', compact('vessel'));
    }

    public function create(Customer $customer = null)
    {
        $staffs    = User::where('role', 'staff')->get();
        $ports     = Vessel::whereNotNull('port_of_call')->distinct()->pluck('port_of_call');
        $customers = Customer::all(); 

        return view('customers_vessels.create', compact('customer', 'customers', 'staffs', 'ports'));
    }

    public function store(Request $request, Customer $customer = null)
    {
        $data = $request->validate([
            'customer_id'       => 'nullable|exists:customers,id',
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

        if ($customer && $customer->exists) {
            $data['customer_id'] = $customer->id;
        } else {
            $data['customer_id'] = $request->input('customer_id');
        }

        Vessel::create($data);

        if ($customer && $customer->exists) {
            return redirect()->route('customers.detail', $customer->id)
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

        $customers = Customer::all();
        $vessels = Vessel::all();

        return view('customers_vessels.edit', compact('customerVessel', 'customers', 'vessels'));
    }

    public function update(Request $request, $id)
    {
        $customerVessel = CustomerVessel::findOrFail($id);

        $data = $request->validate([
            'customer_id'        => 'required|exists:customers,id',
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

    public function showProfile(Customer $customer)
    {
        $customer->load('vessels.assignedStaff');
        return view('customers.profile', compact('customer'));
    }

    public function destroy(Customer $customer, Vessel $vessel)
    {
        if ($vessel->customer_id !== $customer->id) {
            abort(404);
        }

        $vessel->delete();

        return redirect()->route('customers.detail', $customer->id)
                         ->with('success', 'Vessel berhasil dihapus.');
    }

    public function profile($id)
    {
        $customer = Customer::with('logs')->findOrFail($id);
        return view('customers.profile', compact('customer'));
    }

    
}
