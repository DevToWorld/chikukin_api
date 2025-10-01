<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Enqueue scheduled email campaigns every minute
        $schedule->call(function () {
            \App\Models\EmailCampaign::where('status', 'scheduled')
                ->where('scheduled_at', '<=', now())
                ->orderBy('scheduled_at', 'asc')
                ->limit(50)
                ->get()
                ->each(function ($campaign) {
                    \App\Jobs\SendEmailCampaignJob::dispatch($campaign->id);
                });
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
