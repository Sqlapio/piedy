<?php

namespace App\Console\Commands;

use App\Models\Cliente;
use Illuminate\Console\Command;
use App\Models\NotificacionMasiva;

class EjecutaNotificacionMasiva extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:ejecuta-notificacion-masiva';

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

            $info = NotificacionMasiva::where('fecha_programacion', date('Y-m-d'))->first();     

            $user_phone = Cliente::all();

            foreach ($user_phone as $value) {

                $params = array(
                    'token' => env('TOKEN_API_WHATSAPP'),
                    'to' => $value->telefono,
                    'image' => env('APP_URL') . '/storage/' . $info->image,
                    'caption' => $info->caption
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

                if ($err) {
                    $this->info("cURL Error #:" . $err);
                } else {
                    $this->info($response);
                }
            }
            //code...
        } catch (\Throwable $th) {
        }
    }
}