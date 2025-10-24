<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Marketing; // misal kamu punya model Marketing

class MarketingController extends Controller
{
    public function index()
    {
        $statusOptions = ['New', 'Contacted', 'Qualified', 'Lost', 'Won'];
        $staffOptions  = User::where('role', 'staff')->pluck('name');

        $marketingData = Marketing::paginate(10);

        // --- Chart staff ---
        $staffLabels = $staffOptions->toArray();
        $staffValues = [];

        foreach ($staffLabels as $staffName) {
            // Hitung jumlah marketing yang ditangani masing-masing staff
            $count = Marketing::where('staff', $staffName)->count();
            $staffValues[] = $count;
        }

        $marketings = \App\Models\Marketing::all();
        $marketingData = \App\Models\User::where('role','staff')->paginate(10); 

        return view('marketing.index', compact(
            'statusOptions',
            'staffOptions',
            'marketingData',
            'staffLabels',
            'staffValues',
            'marketings',
            'marketingData'
        ));
    }

    public function create()
    {
        $staffOptions = User::where('role', 'staff')->pluck('name');

        return view('marketing.create', compact('staffOptions'));
    }

    public function print()
    {
        $marketingData = Marketing::all();
        return view('marketing.print', compact('marketingData')); 
    }

    public function show($id)
    {
        $company = \App\Models\Company::findOrFail($id);
        $statusOptions = ['New', 'Contacted', 'Qualified', 'Lost', 'Won'];
        $staffOptions  = User::where('role', 'staff')->pluck('name');
        
        return view('marketing.show', compact('company', 'statusOptions', 'staffOptions'));
    }

    public function showProfile($id)
    {
        $marketing = \App\Models\User::findOrFail($id); // atau model Marketing kamu
        return view('marketing.profile', compact('marketing'));
    }


}
