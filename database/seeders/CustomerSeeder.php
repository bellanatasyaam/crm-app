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
                'name'              => 'Saipem Indonesia',
                'assigned_staff'    => 'Wika Purnama',
                'last_followup_date'=> '2025-09-01',
                'next_followup_date'=> '2025-09-07',
                'status'            => 'On Going',
                'potential_revenue' => 50000,
                'notes'             => 'Handling crew change & supply',
            ],
            [
                'name'              => 'Bayu Maritim',
                'assigned_staff'    => 'Leni Marlina',
                'last_followup_date'=> '2025-09-01',
                'next_followup_date'=> '2025-09-12',
                'status'            => 'Negotiation',
                'potential_revenue' => 30000,
                'notes'             => 'Discussing new offshore project',
            ],
            [
                'name'              => 'Wintermar',
                'assigned_staff'    => 'Fatwa Aulia',
                'last_followup_date'=> '2025-09-01',
                'next_followup_date'=> '2025-09-15',
                'status'            => 'Pending Payment',
                'potential_revenue' => 15000,
                'notes'             => 'Invoice outstanding 15 days',
            ],
            [
                'name'              => 'Petrovet',
                'assigned_staff'    => 'Wika Purnama',
                'last_followup_date'=> '2025-09-01',
                'next_followup_date'=> '2025-09-10',
                'status'            => 'Quotation Sent',
                'potential_revenue' => 25000,
                'notes'             => 'Quotation sent for tug service',
            ],
            [
                'name'              => 'Ocean Marine',
                'assigned_staff'    => 'Leni Marlina',
                'last_followup_date'=> '2025-09-01',
                'next_followup_date'=> '2025-09-11',
                'status'            => 'Customer',
                'potential_revenue' => 20000,
                'notes'             => 'Schedule meeting next week',
            ],
        ];

        foreach ($data as $item) {
            Customer::create($item);
        }
    }
}
