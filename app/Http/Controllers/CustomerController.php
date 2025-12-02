<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Vessel;
use App\Models\Log;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\CustomerVessel;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = Customer::query();

        if (!auth()->user()->isAdmin()) {
            $query->where('assigned_staff_id', auth()->id());
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('remark', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('assigned_staff') && auth()->user()->isAdmin()) {
            $query->where('assigned_staff', 'like', '%' . $request->assigned_staff . '%');
        }

        // Paginate
        $customers = $query->orderBy('created_at', 'desc')
                           ->orderBy('last_followup_date', 'asc')
                           ->paginate(10);

        // Hitung overall_status tiap customer
        $customers->each(function($customer) {
            $customer->overall_status = $customer->customerVessels->contains(fn($v) => $v->status == 'active') ? 'Active' : 'Inactive';
        });

        // Summary & stats
        $summary = [
            'total_customers' => Customer::count(),
            'by_status' => Customer::select('status', DB::raw('COUNT(*) as total'))
                ->groupBy('status')
                ->pluck('total','status'),
            'by_staff' => Customer::select('assigned_staff', DB::raw('COUNT(*) as total'))
                ->groupBy('assigned_staff')
                ->pluck('total','assigned_staff'),
        ];

        $statusOptions = ['Lead','Follow up', 'On progress','Request','Waiting approval','Approve','On going','Quotation send','Done / Closing'];
        $staffOptions = User::where('role','staff')->pluck('name')->toArray();

        return view('customers.index', compact(
            'customers',
            'summary',
            'statusOptions',
            'staffOptions'
        ));
    }

    public function create()
    {
        $this->authorize('create', Customer::class);
        $vessels = Vessel::all();
        return view('customers.create', compact('vessels'));
    }

    public function store(Request $request)
    {
        $data = $request->only([
            'name','email','phone','address','last_followup_date','next_followup_date','description','remark'
        ]);
        $data['assigned_staff_id'] = auth()->id();
        $data['assigned_staff'] = auth()->user()->name;
        $data['assigned_staff_email'] = auth()->user()->email;

        $customer = Customer::create($data);

        Log::create([
            'customer_id' => $customer->id,
            'user_id' => auth()->id(),
            'activity' => 'Created customer: ' . $customer->name,
            'activity_type' => 'create',
            'activity_detail' => 'Customer created'
        ]);

        if ($request->filled('vessels')) {
            foreach ($request->vessels as $v) {
                CustomerVessel::create([
                    'customer_id' => $customer->id,
                    'vessel_name' => $v['vessel_name'] ?? 'Unknown',
                    'status' => $v['status'] ?? 'Follow up',
                    'currency' => $v['currency'] ?? 'IDR',
                    'potential_revenue' => $v['potential_revenue'] ?? 0,
                    'next_followup_date' => $v['next_followup_date'] ?? null,
                ]);
            }
        }

        return redirect()->route('customers.index')->with('success', 'Customer created!');
    }

    public function edit(Customer $customer)
    {
        $this->authorize('update', $customer);
        $vessels = Vessel::all();
        return view('customers.edit', compact('customer','vessels'));
    }

    public function update(Request $request, Customer $customer)
    {
        $this->authorize('update', $customer);

        $fillable = (new Customer)->getFillable();
        $data = array_intersect_key($request->all(), array_flip($fillable));

        $changes = [];
        foreach ($data as $field => $value) {
            if ($customer->$field != $value) {
                $changes[] = "$field: '{$customer->$field}' → '$value'";
            }
        }

        $customer->update($data);

        if (!empty($changes)) {
            Log::create([
                'customer_id' => $customer->id,
                'user_id' => auth()->id(),
                'activity' => 'Updated customer: ' . $customer->name,
                'activity_type' => 'update',
                'activity_detail' => implode(', ', $changes)
            ]);
        }

        return redirect()->route('customers.index')->with('success', 'Customer updated!');
    }

    public function destroy(Customer $customer)
    {
        $this->authorize('delete', $customer);

        Log::create([
            'customer_id' => $customer->id,
            'user_id' => auth()->id(),
            'activity' => 'Deleted customer: ' . $customer->name,
            'activity_type' => 'delete',
            'activity_detail' => 'Customer deleted'
        ]);

        $customer->delete();

        return redirect()->route('customers.index')->with('success','Customer deleted!');
    }

    public function show(Customer $customer)
    {
        $this->authorize('view', $customer);
        $customer->load('customerVessels');
        return view('customers.show', compact('customer'));
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

    public function getCustomer($id)
    {
        $customer = Customer::with('customerVessels')->findOrFail($id);

        return response()->json($customer);
    }

}
