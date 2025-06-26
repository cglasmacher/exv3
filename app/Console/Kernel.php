<?php
namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array<int, class-string>
     */
    protected $commands = [
        // Register your custom Artisan commands here, for example:
        // Commands\RefreshQuotes::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule): void
    {
        // Example scheduled tasks:
        // $schedule->command('inspire')->hourly();
        // $schedule->command('quotes:refresh')->dailyAt('00:00');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands(): void
    {
        // Load all commands in the Commands directory
        $this->load(__DIR__.'/Commands');

        // Optionally include console routes
        require base_path('routes/console.php');
    }
}