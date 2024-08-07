<?php

namespace App\Console\Commands;

use App\Models\GiftCard;
use Illuminate\Console\Command;

class ExpiraGiftcard extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:expira-giftcard';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comando que controla el vencimiento de las GiftCard';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $array_exp = [];

        $hoy = date('d-m-Y');

        try {

            $fecha_vence = GiftCard::where('fecha_vence', $hoy)->get();
            
            foreach($fecha_vence as $item){
                GiftCard::where('id', $item->id)->first()->update([
                    'status' => 2
                ]);
                array_push($array_exp, $item->pgc);
            }

            $this->info(json_encode($array_exp));

        } catch (\Throwable $th) {
            $this->info($th->getMessage());
        }
    }

}
