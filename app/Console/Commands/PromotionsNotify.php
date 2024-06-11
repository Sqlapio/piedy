<?php

namespace App\Console\Commands;

use App\Models\Cliente;
use App\Models\User;
use Illuminate\Console\Command;

class PromotionsNotify extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:promotions-notify';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comando para envio de promociones via whatsapp';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $user_phone = Cliente::all();

        foreach ($user_phone as $value) {

            $params = array(
                'token' => env('TOKEN_API_WHATSAPP'),
                'to' => $value->telefono,
                'image' => env('IMAGE_PROMOCION'),
                'caption' => 'Ven y disfruta la experiencia Piedy:'
            );
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => env('CURLOPT_URL_IMAGE'),
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_SSL_VERIFYHOST => 0,
                CURLOPT_SSL_VERIFYPEER => 0,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => http_build_query($params),
                CURLOPT_HTTPHEADER => array(
                    "content-type: application/x-www-form-urlencoded"
                ),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);
        }
    
        $this->info($response);
    }
}
