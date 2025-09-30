<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Vessel;
use Illuminate\Http\Request;

class CustomerVesselController extends Controller
{
    public function index()
    {
        $customers = \App\Models\Customer::with('vessels')->get();
        return view('customers_vessels.index', compact('customers'));
    }

    // Tampilkan detail customer + vessels
    public function show($customerId)
    {
        $customer = Customer::with('vessels')->findOrFail($customerId);

        return view('customers.show', compact('customer'));
    }

    // Form tambah vessel khusus customer ini
    public function create($customerId)
    {
        $customer = Customer::findOrFail($customerId);
        return view('customers.vessels.create', compact('customer'));
    }

    // Simpan vessel baru ke customer
    public function store(Request $request, $customerId)
    {
        $customer = Customer::findOrFail($customerId);

        $data = $request->validate([
            'vessel_name'      => 'required|string|max:255',
            'port_of_call'     => 'nullable|string|max:255',
            'estimate_revenue' => 'nullable|numeric',
            'currency'         => 'nullable|string|max:10',
            'description'      => 'nullable|string',
            'remark'           => 'nullable|string',
            'status'           => 'nullable|string|max:50',
            'last_contact'     => 'nullable|date',
            'next_follow_up'   => 'nullable|date',
        ]);

        $customer->vessels()->create($data);

        return redirect()->route('customers.show', $customerId)
                         ->with('success', 'Vessel added successfully.');
    }

    public function edit($id)
    {
        $customer = Customer::with('vessels')->findOrFail($id);
        return view('customers_vessels.edit', compact('customer'));
    }

    public function update(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);

        $customer->update($request->only(['name', 'email', 'phone', 'address']));

        return redirect()->route('customers_vessels.index')
            ->with('success', 'Customer updated successfully.');
    }

    public function profile($id)
    {
        $customer = Customer::with('vessels')->findOrFail($id);
        return view('customers.profile', compact('customer'));
    }

}
