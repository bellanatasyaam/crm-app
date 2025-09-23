<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'name' => 'Saipem Indonesia',
                'assigned_staff' => 'Wika Purnama',
                'last_followup_date' => '2025-09-01',
                'next_followup_date' => '2025-09-08',
                'status' => 'On Going Vessel Call',
                'potential_revenue' => 50000,
                'notes' => 'Handling crew change & supply',
            ],
            [
                'name' => 'Bayu Maritim Group',
                'assigned_staff' => 'Leni Marlina',
                'last_followup_date' => '2025-09-03',
                'next_followup_date' => '2025-09-10',
                'status' => 'Negotiation',
                'potential_revenue' => 30000,
                'notes' => 'Discussing new offshore project',
            ],
            [
                'name' => 'Wintermar Offshore',
                'assigned_staff' => 'Fatwa Aulia',
                'last_followup_date' => '2025-09-05',
                'next_followup_date' => '2025-09-12',
                'status' => 'Pending Payment',
                'potential_revenue' => 15000,
                'notes' => 'Invoice outstanding 15 days',
            ],
            [
                'name' => 'Petrovietnam PVD Tech',
                'assigned_staff' => 'Wika Purnama',
                'last_followup_date' => '2025-09-02',
                'next_followup_date' => '2025-09-09',
                'status' => 'Quotation Sent',
                'potential_revenue' => 25000,
                'notes' => 'Quotation sent for tug service',
            ],
            [
                'name' => 'Ocean Mark Shipping',
                'assigned_staff' => 'Leni Marlina',
                'last_followup_date' => '2025-09-04',
                'next_followup_date' => '2025-09-11',
                'status' => 'Customer Visit Planned',
                'potential_revenue' => 20000,
                'notes' => 'Schedule meeting next week',
            ],
        ];

        foreach ($data as $item) {
            Customer::create($item);
        }
    }
}
