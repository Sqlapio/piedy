<?php

namespace App\Console\Commands;

use App\Models\PeriodoNomina;
use Illuminate\Console\Command;

class ActivaPeriodoNomina extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:activa-periodo-nomina';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            
            $primera = '1'.date('mY');
            $segunda = '2'.date('mY');

            $periodo1 = PeriodoNomina::where('status', '0')->where('cod_quincena', $primera)->first();
            $periodo2 = PeriodoNomina::where('status', '0')->where('cod_quincena', $segunda)->first();

            $periodo1->update(['status', '1']);
            $periodo2->update(['status', '1']);

            $informacion = 'Los periodos de nomina '.$primera.', '.$segunda.' fueron activados de forma correcta';

            $this->info($informacion);

        } catch (\Throwable $th) {
            $this->info($th);
        }
        
        
    }
}
