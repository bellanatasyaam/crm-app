<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Marketing;

class MarketingController extends Controller
{
    public function index()
    {
        $statusOptions = ['Follow up', 'On going', 'On progress', 'Quotation send'];
        $staffOptions  = User::where('role', 'staff')->pluck('name');

        // 1️⃣ Marketing Profiles (User yang staff dan is_marketing = 1)
        $marketingProfiles = User::where('role', 'staff')
                                ->where('is_marketing', 1)
                                ->get();

        // 2️⃣ Tabel Marketing
        $marketingData = Marketing::with('staff')->orderBy('created_at', 'desc')->paginate(10);

        return view('marketing.index', compact(
            'statusOptions',
            'staffOptions',
            'marketingProfiles',
            'marketingData'
        ));
    }

    public function create()
    {
        $staffOptions = User::where('role', 'staff')->pluck('name', 'id');
        $statusOptions = ['Follow up', 'On going', 'On progress', 'Quotation send'];

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
        $staffOptions = User::where('role', 'staff')->pluck('name');
        $statusOptions = ['Follow up', 'On going', 'On progress', 'Quotation send'];

        return view('marketing.edit', compact('marketing', 'staffOptions', 'statusOptions'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'description' => 'nullable|string',
            'client_name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'staff' => 'nullable|string|max:255',
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
        $marketingData = Marketing::all();
        return view('marketing.print', compact('marketingData'));
    }
}
