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
        // $schedule->command('app:send-mail-command')
        // ->everyMinute()
        // ->emailOutputTo('gusta.acp@gmail.com');

        $schedule->command('app:appointment-reminder')
        ->weekdays()
        ->dailyAt('9:00')
        ->emailOutputTo('gusta.acp@gmail.com');

        // $schedule->call(function () {
        //     DB::table('citas')->delete();
        // })->everyFiveMinutes();

        // $schedule->call(function () {
        //     DB::table('disponibles')->delete();
        // })->everyFiveMinutes();
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
// php artisan schedule:run
// php artisan schedule:work
// php artisan
