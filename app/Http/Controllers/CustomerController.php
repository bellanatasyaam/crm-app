<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Vessel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = Customer::query();

        // Search by name
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by assigned staff (khusus admin)
        if ($request->filled('assigned_staff') && auth()->user()->isAdmin()) {
            $query->where('assigned_staff', 'like', '%' . $request->assigned_staff . '%');
        }

        // ⚡ Semua staff bisa lihat semua progress
        $customers = $query->with('vessels')
            ->orderBy('last_followup_date', 'asc')
            ->paginate(10);

        // Ringkasan data (summary)
        $summary = [
            'total_customers' => Customer::count(),
            'by_status' => Customer::select('status', DB::raw('COUNT(*) as total'))
                ->groupBy('status')
                ->pluck('total','status'),
            'revenue_by_status' => Customer::select('status', DB::raw('SUM(potential_revenue) as revenue'))
                ->groupBy('status')
                ->pluck('revenue','status'),
            'by_staff' => Customer::select('assigned_staff', DB::raw('COUNT(*) as total'))
                ->groupBy('assigned_staff')
                ->pluck('total','assigned_staff'),
            'upcoming_followups' => Customer::whereDate('next_followup_date', '>=', now())
                ->whereDate('next_followup_date', '<=', now()->addDays(7))
                ->count(),
            'overdue_followups' => Customer::whereDate('next_followup_date', '<', now())->count(),
        ];

        // Reminder follow-up
        $reminders = [
            'overdue' => Customer::whereDate('next_followup_date', '<', now())->count(),
            'today' => Customer::whereDate('next_followup_date', now())->count(),
            'upcoming' => Customer::whereBetween('next_followup_date', [now(), now()->addDays(7)])->count(),
        ];

        return view('customers.index', compact('customers', 'summary', 'reminders'));
    }

    public function create()
    {
        $this->authorize('create', Customer::class);

        $customers = Customer::with('vessels')->get();
        $vessels   = Vessel::all();   

        return view('customers.create', compact('customers', 'vessels'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Customer::class);

        $request->validate([
            'name'              => 'required|string|max:255',
            'assigned_staff'    => 'required|string|max:255',
            'potential_revenue' => 'nullable|numeric',
            'currency'          => 'nullable|string|max:10',
            'remark'            => 'nullable|string|max:1000',
        ]);

        Customer::create($request->all());

        return redirect()->route('customers.index')->with('success', 'Customer created successfully.');
    }

    public function edit(Customer $customer)
    {
        $this->authorize('update', $customer);

        $vessels = Vessel::all(); 
        return view('customers.edit', compact('customer', 'vessels'));
    }

    public function update(Request $request, Customer $customer)
    {
        $this->authorize('update', $customer);

        $request->validate([
            'name'              => 'required|string|max:255',
            'email'             => 'nullable|email',
            'phone'             => 'nullable|string|max:20',
            'assigned_staff'    => 'nullable|string|max:255',
            'last_followup_date'=> 'nullable|date',
            'next_followup_date'=> 'nullable|date',
            'status'            => 'nullable|string',
            'potential_revenue' => 'nullable|numeric',
            'currency'          => 'nullable|string|max:10',
            'description'       => 'nullable|string', 
            'remark'            => 'nullable|string'
        ]);

        $data = $request->except('vessels');
        $customer->update($data);

        if ($request->has('vessels')) {
            $newVessels = $request->vessels;

            Vessel::where('customer_id', $customer->id)
                ->whereNotIn('id', $newVessels)
                ->update(['customer_id' => 0]);

            Vessel::whereIn('id', $newVessels)
                ->update(['customer_id' => $customer->id]);
        }

        return redirect()->route('customers.index')->with('success', 'Customer updated successfully.');
    }

    public function destroy(Customer $customer)
    {
        $this->authorize('delete', $customer);

        $customer->delete();
        return redirect()->route('customers.index')->with('success', 'Customer deleted successfully.');
    }

    public function show(Customer $customer)
    {
        $this->authorize('view', $customer);

        $customer->load('vessels');
        return view('customers.show', compact('customer'));
    }

    public function print()
    {
        $user = auth()->user();

        if ($user->role == 'super_admin') {
            // Super admin bisa lihat semua
            $customers = Customer::with('assignedStaff')->get();
        } else {
            // Staff cuma bisa lihat customer yg assigned ke dia
            $customers = Customer::with('assignedStaff')
                ->where('assigned_staff_id', $user->id)
                ->get();
        }

        $pdf = Pdf::loadView('customers.print', compact('customers'))
            ->setPaper('A4', 'landscape');

        return $pdf->stream('customers.pdf');
    }

    public function printSingle(Customer $customer)
    {
        $this->authorize('view', $customer);

        $pdf = Pdf::loadView('customers.print_single', compact('customer'))
            ->setPaper('A4', 'portrait');

        return $pdf->stream('customer-'.$customer->name.'.pdf');
    }

    public function getVessels($id)
    {
        $customer = Customer::findOrFail($id);
        $this->authorize('view', $customer);

        $vessels = Vessel::where('customer_id', $id)->get();
        return response()->json($vessels);
    }
}
