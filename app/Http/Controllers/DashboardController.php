<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\CustomerVessel; // Tambahkan ini

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // --- Filter tanggal ---
        $date = $request->input('date', date('Y-m-d'));

        // --- Query customer ---
        $customers = Customer::with('vessels')
            ->whereHas('vessels', function($q) use ($date) {
                $q->whereDate('created_at', $date);
            })
            ->get();

        // --- Query vessel report berdasarkan tanggal ---
        $reports = CustomerVessel::whereDate('created_at', $date)->get();

        $totalRevenue = $customers->sum(function($c) {
            return $c->vessels->sum('potential_revenue');
        });
        $totalCustomer = $customers->count();

        // --- Summary counts ---
        $allCustomers = Customer::all();

        $summary = [
            'totalCustomers'   => $allCustomers->count(),
            'leads'            => $allCustomers->where('status', 'Lead')->count(),
            'quotationSent'    => $allCustomers->where('status', 'Quotation Sent')->count(),
            'negotiation'      => $allCustomers->where('status', 'Negotiation')->count(),
            'onGoingVessel'    => $allCustomers->where('status', 'On Going Vessel')->count(),
            'pendingPayment'   => $allCustomers->where('status', 'Pending Payment')->count(),
            'closing'          => $allCustomers->where('status', 'Closing')->count(),
            'overdue'          => $allCustomers->where('next_follow_up','<', now()->format('Y-m-d'))->count(),
            'dueToday'         => $allCustomers->where('next_follow_up', now()->format('Y-m-d'))->count()
        ];

        // --- Dummy recent activities ---
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

            // tambahan buat daily report
            'date' => $date,
            'totalRevenue' => $totalRevenue,
            'totalCustomer' => $totalCustomer,
        ]);
    }
}
