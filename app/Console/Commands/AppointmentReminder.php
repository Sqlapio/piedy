<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class AppointmentReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:appointment-reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Recordatorio de citas un dia antes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('tarea 2');
    }
}
