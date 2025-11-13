<?php

namespace App\Http\Controllers;

use App\Models\FollowUp;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;

class FollowUpController extends Controller
{
    public function index()
    {
        $followUps = FollowUp::with(['company', 'staff'])->latest()->paginate(10);
        return view('followups.index', compact('followUps'));
    }

    public function create()
    {
        $companies = Company::pluck('name', 'id');
        $staffs = User::where('role', 'staff')->pluck('name', 'id');
        return view('followups.create', compact('companies', 'staffs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_id' => 'nullable|exists:companies,id',
            'assigned_staff_id' => 'nullable|exists:users,id',
            'vessel_name' => 'nullable|string|max:255',
            'status' => 'nullable|string|max:100',
            'revenue_usd' => 'nullable|numeric',
            'description' => 'nullable|string',
            'remark' => 'nullable|string',
            'last_contact' => 'nullable|date',
            'next_follow_up' => 'nullable|date',
        ]);

        FollowUp::create($validated);
        return redirect()->route('followups.index')->with('success', 'Follow-up created successfully!');
    }

    public function show(FollowUp $followUp)
    {
        return view('followups.show', compact('followUp'));
    }

    public function edit(FollowUp $followUp)
    {
        $companies = Company::pluck('name', 'id');
        $staffs = User::where('role', 'staff')->pluck('name', 'id');
        return view('followups.edit', compact('followUp', 'companies', 'staffs'));
    }

    public function update(Request $request, FollowUp $followUp)
    {
        $validated = $request->validate([
            'company_id' => 'nullable|exists:companies,id',
            'assigned_staff_id' => 'nullable|exists:users,id',
            'vessel_name' => 'nullable|string|max:255',
            'status' => 'nullable|string|max:100',
            'revenue_usd' => 'nullable|numeric',
            'description' => 'nullable|string',
            'remark' => 'nullable|string',
            'last_contact' => 'nullable|date',
            'next_follow_up' => 'nullable|date',
        ]);

        $followUp->update($validated);
        return redirect()->route('followups.index')->with('success', 'Follow-up updated successfully!');
    }

    public function destroy(FollowUp $followUp)
    {
        $followUp->delete();
        return redirect()->route('followups.index')->with('success', 'Follow-up deleted successfully!');
    }
}
