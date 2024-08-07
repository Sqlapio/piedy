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
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('app:appointment-reminder')
        ->dailyAt('9:30')
        ->emailOutputTo('gusta.acp@gmail.com');

        $schedule->command('app:activa-periodo-nomina')
        ->monthlyOn(1, '9:30')
        ->emailOutputTo('gusta.acp@gmail.com');

        $schedule->command('app:expira-membresia')
        ->dailyAt('9:30')
        ->emailOutputTo('gusta.acp@gmail.com');

        $schedule->command('app:expira-giftcard')
        ->dailyAt('9:30')
        ->emailOutputTo('gusta.acp@gmail.com');
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
