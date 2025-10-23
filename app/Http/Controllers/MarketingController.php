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

        return view('marketing.index', compact(
            'statusOptions',
            'staffOptions',
            'marketingData',
            'staffLabels',
            'staffValues'
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
}
