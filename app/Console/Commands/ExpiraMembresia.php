<?php

namespace App\Console\Commands;

use App\Models\Membresia;
use Illuminate\Console\Command;

class ExpiraMembresia extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:expira-membresia';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comando que controla el vencimiento de las Membresias';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $array_exp = [];

        $hoy = date('d-m-Y');

        try {

            $fecha_vence = Membresia::where('fecha_exp', $hoy)->get();
        
            foreach($fecha_vence as $item){
                Membresia::where('id', $item->id)->first()->update([
                    'status' => 2
                ]);
                array_push($array_exp, $item->pm);
            }

            $this->info(json_encode($array_exp));

        } catch (\Throwable $th) {
            $this->info($th->getMessage());
        }

    }
}
