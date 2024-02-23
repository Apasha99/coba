<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB; 
class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            // Update status to 'On going' for pelatihan that has started
            DB::table('pelatihan')
                ->whereDate('start_date', '<=', now())
                ->where('status', 'not started yet')
                ->update(['status' => 'On going']);
    
            // Update status to 'Completed' for pelatihan that has ended
            DB::table('pelatihan')
            ->whereDate('end_date', '<=', now()) 
            ->where('status', 'On going')
            ->update(['status' => 'Completed']);
        })->everyMinute();
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
