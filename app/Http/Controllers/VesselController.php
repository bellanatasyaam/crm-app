<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Vessel;
use App\Models\User;
use Illuminate\Http\Request;

class VesselController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Customer $customer = null)
    {
        $this->authorize('viewAny', Vessel::class);

        if ($customer) {
            // Nested: vessel untuk 1 customer
            $vessels = $customer->vessels()->with('assignedStaff')->get();

            return view('vessels.index', [
                'vessels'  => $vessels,
                'customer' => $customer,
            ]);
        } else {
            // Global: semua vessel
            $vessels = Vessel::with(['customer', 'assignedStaff'])->get();

            return view('vessels.index', [
                'vessels'  => $vessels,
                'customer' => null,
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Vessel::class);

        $customers = Customer::all();
        $staffs    = User::where('role', '!=', 'super_admin')->get();

        return view('vessels.create', compact('customers', 'staffs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Customer $customer = null)
    {
        $this->authorize('create', Vessel::class);

        $data = $request->validate([
            'vessel_name' => 'required|string',
            'customer_id' => 'nullable|exists:customers,id',
        ]);

        if ($customer) {
            $data['customer_id'] = $customer->id;
        }

        Vessel::create($data);

        return redirect()->route('vessels.index')
                         ->with('success', 'Vessel added successfully.');
    }

    /**
     * Show the specified resource.
     */
    /**
 * Show the specified resource.
 */
    public function show(Vessel $vessel)
    {
        $this->authorize('view', $vessel);

        // Ambil customer lengkap dengan semua vessels (relasi assignedStaff)
        $customer = Customer::with('vessels.assignedStaff')->findOrFail($vessel->customer_id);

        return view('vessels.show', compact('customer'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vessel $vessel)
    {
        $this->authorize('update', $vessel);

        $staff = User::all();
        $customers = Customer::all();

        return view('vessels.edit', compact('vessel', 'staff', 'customers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Vessel $vessel)
    {
        $this->authorize('update', $vessel);

        $data = $request->validate([
            'vessel_name' => 'required|string',
            'port_of_call' => 'nullable|string',
            'estimate_revenue' => 'nullable|numeric',
            'currency' => 'nullable|string|max:10',
            'description' => 'nullable|string',
            'remark' => 'nullable|string',
            'status' => 'nullable|string|max:50',
            'last_contact' => 'nullable|date',
            'next_follow_up' => 'nullable|date',
            'assigned_staff_id' => 'nullable|exists:users,id',
            'customer_id' => 'nullable|exists:customers,id',
        ]);

        $vessel->update($data);

        return redirect()->route('vessels.index')
                         ->with('success', 'Vessel updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vessel $vessel)
    {
        $this->authorize('delete', $vessel);

        $vessel->delete();

        return redirect()->route('vessels.index')
                         ->with('success', 'Vessel deleted successfully.');
    }
}
