<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;

class DashboardController extends Controller
{
    public function index()
    {
        $customers = Customer::all();

        // Summary counts
        $summary = [
            'totalCustomers'   => $customers->count(),
            'leads'            => $customers->where('status', 'Lead')->count(),
            'quotationSent'    => $customers->where('status', 'Quotation Sent')->count(),
            'negotiation'      => $customers->where('status', 'Negotiation')->count(),
            'onGoingVessel'    => $customers->where('status', 'On Going Vessel')->count(),
            'pendingPayment'   => $customers->where('status', 'Pending Payment')->count(),
            'closing'          => $customers->where('status', 'Closing')->count(),
            'overdue'          => $customers->where('next_follow_up','<', now()->format('Y-m-d'))->count(),
            'dueToday'         => $customers->where('next_follow_up', now()->format('Y-m-d'))->count()
        ];

        // Dummy recent activities
        $recentActivities = [
            'Wika Purnama updated Lead status for Saipem Indonesia',
            'Fatwa Aulia added a note for Bayu Maritim Group',
            'Leni Marlina closed Wintermar Offshore deal'
        ];

        return view('dashboard', [
            'customers' => $customers,
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
        ]);
    }
}
