<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            $customers = \App\Models\Customer::whereDate('next_followup_date', now())->get();

            foreach ($customers as $customer) {
                // misal kirim notifikasi email
                $staffEmail = $customer->assigned_staff_email; 
                \Mail::to($staffEmail)->send(new \App\Mail\FollowupReminder($customer));
            }
        })->dailyAt('08:00'); // cek tiap jam 8 pagi
    }


    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
