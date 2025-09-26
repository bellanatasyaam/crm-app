<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VesselController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Customer $customer)
    {
        $vessels = $customer->vessels()->with('assignedStaff')->get();
        return view('vessels.index', compact('customer','vessels'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if ($vessel->description !== $request->description) {
            DescriptionLog::create([
                'vessel_id' => $vessel->id,
                'changed_by' => auth()->id(),
                'old_description' => $vessel->description,
                'new_description' => $request->description,
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
