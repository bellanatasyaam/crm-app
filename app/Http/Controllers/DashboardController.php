<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\CustomerVessel;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // --- Filter tanggal ---
        $date = $request->input('date', date('Y-m-d'));

        // --- Query customer ---
        $companies = Company::with('vessels')
            ->whereHas('vessels', function($q) use ($date) {
                $q->whereDate('created_at', $date);
            })
            ->get();

        // --- Query vessel report berdasarkan tanggal ---
        $reports = CustomerVessel::whereDate('created_at', $date)->get();

        $totalRevenue = $companies->sum(function($c) {
            return $c->vessels->sum('potential_revenue');
        });
        $totalCustomer = $companies->count();

        // --- Summary counts ---
        $companies = Company::all();

        $summary = [
            'totalCustomers'   => $companies->count(),
            'leads'            => $companies->where('status', 'Lead')->count(),
            'quotationSent'    => $companies->where('status', 'Quotation Sent')->count(),
            'negotiation'      => $companies->where('status', 'Negotiation')->count(),
            'onGoingVessel'    => $companies->where('status', 'On Going Vessel')->count(),
            'pendingPayment'   => $companies->where('status', 'Pending Payment')->count(),
            'closing'          => $companies->where('status', 'Closing')->count(),
            'overdue'          => $companies->where('next_follow_up','<', now()->format('Y-m-d'))->count(),
            'dueToday'         => $companies->where('next_follow_up', now()->format('Y-m-d'))->count()
        ];

        // --- Dummy recent activities ---
        $recentActivities = [
            'Wika Purnama updated Lead status for Saipem Indonesia',
            'Fatwa Aulia added a note for Bayu Maritim Group',
            'Leni Marlina closed Wintermar Offshore deal'
        ];

        return view('dashboard', [
            'companies' => $companies,
            'totalCustomers' => $summary['totalCustomers'],
            'leads' => $summary['leads'],
            'quotationSent' => $summary['quotationSent'],
            'negotiation' => $summary['negotiation'],
            'onGoingVessel' => $summary['onGoingVessel'],
            'pendingPayment' => $summary['pendingPayment'],
            'closing' => $summary['closing'],
            'overdue' => $summary['overdue'],
            'dueToday' => $summary['dueToday'],
            'recentActivities' => $recentActivities,

            // tambahan buat daily report
            'date' => $date,
            'totalRevenue' => $totalRevenue,
            'totalCustomer' => $totalCustomer,
        ]);
    }
}
