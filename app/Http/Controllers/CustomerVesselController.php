<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Customer;
use App\Models\Vessel;
use Illuminate\Http\Request;

class CustomerVesselController extends Controller
{
    // Tampilkan semua customer + vessels (GLOBAL LIST)
    public function index()
    {
        $customers = Customer::with(['vessels.assignedStaff'])->get();
        return view('customers_vessels.index', compact('customers'));
    }

    // Detail 1 customer + vessels (NESTED)
    public function show(Customer $customer)
    {
        $customer->load('vessels.assignedStaff');
        return view('customers.show', compact('customer'));
    }

    // Form tambah vessel baru
    // Bisa dipanggil global (/customers-vessels/create) atau nested (/customers/{id}/vessels/create)
    public function create(Customer $customer = null)
    {
        $staffs    = User::where('role', 'staff')->get();
        $ports     = Vessel::whereNotNull('port_of_call')->distinct()->pluck('port_of_call');
        $customers = Customer::all(); // untuk dropdown kalau global

        return view('customers_vessels.create', compact('customer', 'customers', 'staffs', 'ports'));
    }

    // Simpan vessel baru
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

        // Tentukan customer_id
        if ($customer && $customer->exists) {
            // Nested route
            $data['customer_id'] = $customer->id;
        } else {
            // Global route → ambil dari input
            $data['customer_id'] = $request->input('customer_id');
        }

        Vessel::create($data);

        // Redirect sesuai asal
        if ($customer && $customer->exists) {
            return redirect()->route('customers.detail', $customer->id)
                             ->with('success', 'Vessel berhasil ditambahkan.');
        } else {
            return redirect()->route('customers_vessels.index')
                             ->with('success', 'Vessel berhasil ditambahkan.');
        }
    }

    // Form edit vessel
    public function edit(Customer $customer, Vessel $vessel)
    {
        if ($vessel->customer_id !== $customer->id) {
            abort(404);
        }

        $users = User::where('role', 'staff')->get();
        return view('customers_vessels.edit', compact('customer', 'vessel', 'users'));
    }

    // Update vessel
    public function update(Request $request, Customer $customer, Vessel $vessel)
    {
        if ($vessel->customer_id !== $customer->id) {
            abort(404);
        }

        $data = $request->validate([
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

        $vessel->update($data);

        return redirect()->route('customers.detail', $customer->id)
                         ->with('success', 'Vessel berhasil diupdate.');
    }

    // Profile customer
    public function profile(Customer $customer)
    {
        $customer->load('vessels.assignedStaff');
        return view('customers.profile', compact('customer'));
    }

    // Hapus vessel
    public function destroy(Customer $customer, Vessel $vessel)
    {
        if ($vessel->customer_id !== $customer->id) {
            abort(404);
        }

        $vessel->delete();

        return redirect()->route('customers.detail', $customer->id)
                         ->with('success', 'Vessel berhasil dihapus.');
    }
}
