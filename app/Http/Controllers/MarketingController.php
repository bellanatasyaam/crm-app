<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Marketing;
use App\Models\Company;
use App\Models\CustomerVessel;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class MarketingController extends Controller
{
    public function index(Request $request)
    {
        $statusOptions = [
            'Follow up',
            'On progress',
            'Request',
            'Waiting approval',
            'Approve',
            'On going',
            'Quotation send',
            'Done / Closing',
        ];

        $staffOptions = User::where('role', 'staff')
                            ->where('is_marketing', 1)
                            ->pluck('name', 'id');

        $marketingProfiles = User::where('role', 'staff')
                            ->where('is_marketing', 1)
                            ->get();

        $search = $request->search;

        $marketingData = Marketing::with('staff')
            ->when($search, function ($q) use ($search) {
                $q->where('description', 'like', "%$search%")
                ->orWhere('client_name', 'like', "%$search%")
                ->orWhere('email', 'like', "%$search%")
                ->orWhere('phone', 'like', "%$search%")
                ->orWhereHas('staff', function ($s) use ($search) {
                    $s->where('name', 'like', "%$search%");
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $staffList = User::where('role', 'staff')->get();
        $companies = Company::all();

        return view('marketing.index', compact(
            'statusOptions',
            'staffOptions',
            'marketingProfiles',
            'marketingData',
            'staffList',
            'companies',
        ));
    }

    public function create()
    {
        $staffOptions = User::where('role', 'staff')
            ->where('is_marketing', 1)
            ->pluck('name', 'id');

        $companies = Company::with('customerVessels')->get();

        $statusOptions = [
            "Follow up",
            "Request",
            "Quotation send",
            "Waiting approval",
            "Approve",
            "On progress",
            "On going",
            "On process",
            "On review",
            "Finish",
            "Done / Closing"
        ];

        return view('marketing.create', compact('staffOptions', 'companies', 'statusOptions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'description' => 'nullable|string',
            'client_id' => 'required|exists:companies,id',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'staff_id' => 'nullable|exists:users,id',

            'last_contact' => 'nullable|sometimes',
            'next_follow_up' => 'nullable|sometimes',

            'status' => 'nullable|string|max:50',
            'revenue' => 'nullable|string|max:100',
            'vessel_name' => 'nullable|string|max:255',
            'remark' => 'nullable|string',
        ]);

        // Convert dd/mm/yyyy → Y-m-d
        $lastContact = $request->last_contact
            ? Carbon::createFromFormat(...)('d/m/Y', $request->last_contact)->format('Y-m-d')
            : null;

        $nextFollowUp = $request->next_follow_up
            ? Carbon::createFromFormat(...)('d/m/Y', $request->next_follow_up)->format('Y-m-d')
            : null;

        Marketing::create([
            ...$validated,
            'client_name' => Company::find($request->client_id)->name ?? null,
            'last_contact' => $lastContact,
            'next_follow_up' => $nextFollowUp,
        ]);

        return redirect()->route('marketing.index')->with('success', 'Marketing berhasil ditambahkan!');
    }

    public function show($id)
    {
        $marketing = Marketing::with('staff')->findOrFail($id);
        return view('marketing.show', compact('marketing'));
    }

    public function edit($id)
    {
        $marketing = Marketing::findOrFail($id);

        $staffOptions = User::where('role', 'staff')
                            ->where('is_marketing', 1)
                            ->pluck('name', 'id');

        $statusOptions = [
            'Follow up',
            'On progress',
            'Request',
            'Waiting approval',
            'Approve',
            'On going',
            'Quotation send',
            'Done / Closing',
        ];

        return view('marketing.edit', compact('marketing', 'staffOptions', 'statusOptions'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'description' => 'nullable|string',
            'client_name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'staff_id' => 'nullable|exists:users,id',
            'last_contact' => 'nullable|date',
            'next_follow_up' => 'nullable|date',
            'status' => 'nullable|string|max:50',
            'revenue' => 'nullable|string|max:100',
            'vessel_name' => 'nullable|string|max:255',
            'remark' => 'nullable|string',
        ]);

        $marketing = Marketing::findOrFail($id);
        $marketing->update([
            ...$validated,
            'staff_id' => $request->staff_id,
        ]);

        return redirect()->route('marketing.index')->with('success', 'Marketing berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $marketing = Marketing::findOrFail($id);
        $marketing->delete();

        return redirect()->route('marketing.index')->with('success', 'Marketing berhasil dihapus!');
    }

    public function print(Request $request)
    {
        $staffId = $request->staff_id;

        if (!$staffId) {
            return redirect()->back()->with('error', 'Pilih staff terlebih dahulu.');
        }

        $staff = User::findOrFail($staffId);

        $data = Marketing::where('staff_id', $staffId)
            ->with('staff')
            ->orderBy('created_at', 'desc')
            ->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('marketing.print', [
            'data' => $data,
            'staff' => $staff
        ])->setPaper('A4', 'landscape');

        return $pdf->stream('marketing_report_' . $staff->name . '.pdf');
    }

    public function showAll()
    {
        $marketingData = Marketing::with('staff')->orderBy('created_at', 'desc')->paginate(10);

        return view('marketing.show', compact('marketingData'));
    }

    public function profile($id)
    {
        $profile = User::findOrFail($id);

        return view('marketing.profile', compact('profile'));
    }

    public function getCustomerData($companyId)
    {
        $company = Company::with('vessels')->find($companyId);

        if (!$company) {
            return response()->json(['error' => 'Company not found'], 404);
        }

        return response()->json([
            'customer' => [
                'email' => $company->email,
                'phone' => $company->phone,
            ],
            'vessels' => $company->vessels->map(function($v){
                return [
                    'name' => $v->name,
                ];
            }),
        ]);
    }

}
