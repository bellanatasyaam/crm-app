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

        $perPage = 10; // jumlah vessel per halaman, bisa diubah

        if ($customer) {
            // Nested: vessel untuk 1 customer, pakai paginate()
            $vessels = $customer->vessels()
                ->with('assignedStaff')
                ->orderBy('id', 'desc')
                ->paginate($perPage);

            return view('vessels.index', [
                'vessels'  => $vessels,
                'customer' => $customer,
            ]);
        } else {
            // Global: semua vessel, pakai paginate()
            $vessels = Vessel::with(['customer', 'assignedStaff'])
                ->orderBy('id', 'desc')
                ->paginate($perPage);

            return view('vessels.index', [
                'vessels'  => $vessels,
                'customer' => null,
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Customer $customer = null)
    {
        $this->authorize('create', Vessel::class);

        $customers = Customer::all();
        $staffs    = User::where('role', '!=', 'super_admin')->get();

        return view('vessels.create', [
            'customers' => $customers,
            'staffs'    => $staffs,
            'customer'  => $customer, // biar di blade ada kalau nested
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Customer $customer = null)
    {
        $this->authorize('create', Vessel::class);

        $data = $request->validate([
            'vessel_name'       => 'required|string',
            'port_of_call'      => 'nullable|string',
            'estimate_revenue'  => 'nullable|numeric',
            'currency'          => 'nullable|string|max:10',
            'description'       => 'nullable|string',
            'remark'            => 'nullable|string',
            'status'            => 'nullable|string|max:50',
            'last_contact'      => 'nullable|date',
            'next_follow_up'    => 'nullable|date',
            'assigned_staff_id' => 'nullable|exists:users,id',
            'customer_id'       => 'nullable|exists:customers,id',
        ]);

        if ($customer) {
            // kalau nested, customer_id selalu ikut dari URL
            $data['customer_id'] = $customer->id;
        }

        Vessel::create($data);

        if ($customer) {
            return redirect()
                ->route('customers.vessels.index', $customer->id)
                ->with('success', 'Vessel added successfully.');
        }

        return redirect()
            ->route('vessels.index')
            ->with('success', 'Vessel added successfully.');
    }

    /**
     * Show the specified resource.
     */
    public function show(Vessel $vessel)
    {
        $this->authorize('view', $vessel);

        // Ambil customer lengkap dengan semua vessels (relasi assignedStaff)
        $customer = Customer::with('vessels.assignedStaff')
            ->findOrFail($vessel->customer_id);

        return view('vessels.show', compact('customer'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vessel $vessel)
    {
        $this->authorize('update', $vessel);

        $staffs    = User::where('role', '!=', 'super_admin')->get();
        $customers = Customer::all();

        return view('vessels.edit', [
            'vessel'    => $vessel,
            'staffs'    => $staffs,
            'customers' => $customers,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Vessel $vessel)
    {
        $this->authorize('update', $vessel);

        $data = $request->validate([
            'vessel_name'       => 'required|string',
            'port_of_call'      => 'nullable|string',
            'estimate_revenue'  => 'nullable|numeric',
            'currency'          => 'nullable|string|max:10',
            'description'       => 'nullable|string',
            'remark'            => 'nullable|string',
            'status'            => 'nullable|string|max:50',
            'last_contact'      => 'nullable|date',
            'next_follow_up'    => 'nullable|date',
            'assigned_staff_id' => 'nullable|exists:users,id',
            'customer_id'       => 'nullable|exists:customers,id',
        ]);

        $vessel->update($data);

        return redirect()
            ->route('vessels.index')
            ->with('success', 'Vessel updated successfully.');
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
