<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Marketing;

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

        // === SEARCH ===
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

        return view('marketing.index', compact(
            'statusOptions',
            'staffOptions',
            'marketingProfiles',
            'marketingData'
        ));
    }

    public function create()
    {
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

        return view('marketing.create', compact('staffOptions', 'statusOptions'));
    }

    public function store(Request $request)
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

        Marketing::create([
            ...$validated,
            'staff_id' => $request->staff_id,
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

    public function print()
    {
        $marketingData = Marketing::with('staff')->get();
        return view('marketing.print', compact('marketingData'));
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

}
