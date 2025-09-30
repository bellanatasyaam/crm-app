<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Vessel;
use App\Models\User; // tambahin ini
use Illuminate\Http\Request;

class VesselController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Customer $customer)
    {
        $vessels = $customer->vessels()->with('assignedStaff')->get();
        return view('vessels.index', compact('customer','vessels'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Customer $customer)
    {
        $staffs = User::where('role', '!=', 'super_admin')->get();
        return view('vessels.create', compact('customer', 'staffs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Customer $customer)
    {
        $request->validate([
            'vessel_name' => 'required|string',
        ]);

        $data = $request->all();
        $data['customer_id'] = $customer->id;

        Vessel::create($data);

        return redirect()->route('customers.vessels.index', $customer->id)
                         ->with('success', 'Vessel added successfully.');
    }

    /**
     * Show the specified resource.
     */
    public function show(Customer $customer, Vessel $vessel)
    {
        return view('vessels.show', compact('customer', 'vessel'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer, Vessel $vessel)
    {
        $staff = User::all();
        return view('vessels.edit', compact('customer', 'vessel', 'staff'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Customer $customer, Vessel $vessel)
    {
        $request->validate([
            'vessel_name' => 'required|string',
        ]);

        // log perubahan deskripsi kalau ada
        if ($vessel->description !== $request->description) {
            DescriptionLog::create([
                'vessel_id' => $vessel->id,
                'changed_by' => auth()->id(),
                'old_description' => $vessel->description,
                'new_description' => $request->description,
            ]);
        }

        $vessel->update($request->all());

        return redirect()->route('customers.vessels.index', $customer->id)
                         ->with('success', 'Vessel updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer, Vessel $vessel)
    {
        $vessel->delete();
        return redirect()->route('customers.vessels.index', $customer->id)
                         ->with('success', 'Vessel deleted successfully.');
    }
}
