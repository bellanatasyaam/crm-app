<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Vessel;
use App\Models\Log;
use App\Helpers\LogHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\CustomerVessel;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = Customer::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('assigned_staff') && auth()->user()->isAdmin()) {
            $query->where('assigned_staff', 'like', '%' . $request->assigned_staff . '%');
        }

        $customers = $query->with('vessels')
            ->orderBy('last_followup_date', 'asc')
            ->paginate(10);

        $summary = [
            'total_customers' => Customer::count(),
            'by_status' => Customer::select('status', DB::raw('COUNT(*) as total'))
                ->groupBy('status')
                ->pluck('total','status'),
            'revenue_by_status' => Customer::select('status', DB::raw('SUM(potential_revenue) as revenue'))
                ->groupBy('status')
                ->pluck('revenue','status'),
            'by_staff' => Customer::select('assigned_staff', DB::raw('COUNT(DISTINCT id) as total'))
                ->groupBy('assigned_staff')
                ->pluck('total','assigned_staff'),
            'upcoming_followups' => Customer::whereDate('next_followup_date', '>=', now())
                ->whereDate('next_followup_date', '<=', now()->addDays(7))
                ->count(),
            'overdue_followups' => Customer::whereDate('next_followup_date', '<', now())->count(),
        ];

        $reminders = [
            'overdue' => Customer::whereDate('next_followup_date', '<', now())->count(),
            'today' => Customer::whereDate('next_followup_date', now())->count(),
            'upcoming' => Customer::whereBetween('next_followup_date', [now(), now()->addDays(7)])->count(),
        ];

        $stats = [
            'follow_up'        => Customer::where('status', 'Follow up')->count(),
            'on_progress'      => Customer::where('status', 'On progress')->count(),
            'request'          => Customer::where('status', 'Request')->count(),
            'waiting_approval' => Customer::where('status', 'Waiting approval')->count(),
            'approve'          => Customer::where('status', 'Approve')->count(),
            'on_going'         => Customer::where('status', 'On going')->count(),
            'quotation_sent'   => Customer::where('status', 'Quotation send')->count(),
            'done'             => Customer::where('status', 'Done / Closing')->count(),
        ];

        return view('customers.index', compact('customers', 'summary', 'reminders', 'stats'));
    }

    public function create()
    {
        $this->authorize('create', Customer::class);

        $customers = Customer::all();
        $vessels = \App\Models\Vessel::all();
        return view('customers.create', compact('customers', 'vessels'));
    }

    public function store(Request $request)
    {
        $data = $request->only([
            'name',
            'email',
            'phone',
            'address',
            'assigned_staff',
            'last_followup_date',
            'next_followup_date',
            'description',
            'remark'
        ]);
        
        $data['assigned_staff_id'] = auth()->id();
        $data['assigned_staff'] = auth()->user()->name;
        $data['assigned_staff_email'] = auth()->user()->email;

        $customer = Customer::create($data);

        Log::create([
            'customer_id'    => $customer->id,
            'user_id'        => auth()->id(),
            'activity'       => 'Created customer: ' . $customer->name,
            'activity_type'  => 'create',
            'activity_detail'=> 'Customer created with email ' . ($customer->email ?? '-'),
        ]);

        if ($request->filled('vessels')) {
            foreach ($request->vessels as $v) {
                CustomerVessel::create([
                    'customer_id' => $customer->id,
                    'vessel_name' => $request->vessel_name ?? 'Unknown',
                    'status' => $request->status ?? 'Follow up',
                    'currency' => $request->currency ?? 'IDR',
                    'potential_revenue' => $request->potential_revenue ?? 0,
                    'next_followup_date' => $request->next_followup_date,
                ]);
            }
        }

        return redirect()->route('customers.index')->with('success', 'Customer created!');
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

        $validated = $request->validate([
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
            'remark'            => 'nullable|string',
            'address'           => 'nullable|string',
        ]);

        $fillable = (new Customer)->getFillable();
        $data = $request->except(['_token', '_method']);

        $data = array_intersect_key($data, array_flip($fillable));

        $changes = [];
        foreach ($data as $field => $newValue) {
            $oldValue = $customer->getOriginal($field);

            if ($oldValue != $newValue) {
                $changes[] = ucfirst($field)." from '".($oldValue ?? '-')."' to '".($newValue ?? '-')."'";
            }
        }

        $customer->update($request->all());

        if (!empty($changes)) {
            Log::create([
                'customer_id'     => $customer->id,
                'user_id'         => auth()->id(),
                'activity'        => 'Updated customer: '.$customer->name,
                'activity_type'   => 'update',
                'activity_detail' => implode(', ', $changes),
            ]);
        }

        if ($request->has('vessels')) {
            $newVessels = $request->vessels;

            Vessel::where('customer_id', $customer->id)
                ->whereNotIn('id', $newVessels)
                ->update(['customer_id' => null]);

            Vessel::whereIn('id', $newVessels)
                ->update(['customer_id' => $customer->id]);
        }

        return redirect()->route('customers.index')->with('success', 'Customer updated successfully.');
    }

    public function destroy(Customer $customer)
    {
        $this->authorize('delete', $customer);

        $customerName = $customer->name;
        $customerId   = $customer->id;

        Log::create([
            'customer_id'    => $customerId,
            'user_id'        => auth()->id(),
            'activity'       => 'Deleted customer: ' . $customerName,
            'activity_type'  => 'delete',
            'activity_detail'=> 'Customer was deleted permanently',
        ]);

        $customer->delete();

        return redirect()->route('customers.index')->with('success', 'Customer deleted successfully.');
    }

    public function show(Customer $customer)
    {
        $this->authorize('view', $customer);

        $customer->load('vessels');

        $totalRevenue = $customer->vessels->sum('estimate_revenue');

        return view('customers.show', compact('customer', 'totalRevenue'));
    }

    public function print()
    {
        $user = auth()->user();

        if ($user->role == 'super_admin') {
            $customers = Customer::with('assignedStaff')->get();
        } else {
            $customers = Customer::with('assignedStaff')
                ->where('assigned_staff_id', $user->id)
                ->get();
        }

        Log::create([
            'customer_id'    => null,
            'user_id'        => auth()->id(),
            'activity'       => 'Printed all customers list',
            'activity_type'  => 'print',
            'activity_detail'=> 'Exported customers list to PDF',
        ]);

        $pdf = Pdf::loadView('customers.print', compact('customers'))
            ->setPaper('A4', 'landscape');

        return $pdf->stream('customers.pdf');
    }

    public function printSingle(Customer $customer)
    {
        $this->authorize('view', $customer);

        Log::create([
            'customer_id'    => $customer->id,
            'user_id'        => auth()->id(),
            'activity'       => 'Printed profile for customer: ' . $customer->name,
            'activity_type'  => 'print',
            'activity_detail'=> 'Exported customer profile to PDF',
        ]);

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
