<?php

namespace App\Console\Commands;

use App\Models\Cliente;
use Illuminate\Console\Command;
use App\Models\NotificacionMasiva;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\LogController;

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

            $user_phone = Cliente::where('status', 1)->get('telefono')->toArray();

            for ($i = 0; $i < count($user_phone); $i++) {
                # code...
                $params = array(
                    'token' => env('TOKEN_API_WHATSAPP'),
                    'to' => $user_phone[$i]['telefono'],
                    'image' => env('APP_URL') . '/storage/' . $info->image,
                    // 'image' => env('IMAGE_PROMOCION'),

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

                $res = json_decode($response, true);

                curl_close($curl);

                if (isset($res['sent']) and $res['sent'] == 'true') {
                    array_push($response_ok, $res['sent']);
                }

                if (isset($res['error'])) {
                    array_push($response_err, $res['error']);
                }
            }

            LogController::log(1, 'ejecucion job sistema', 'servicio masivo WhatsApp. Total enviados: ' . count($response_ok), $response = null);
            LogController::log(1, 'excepcion job sistema', 'falla de servicio masivo WhatsApp. Total fallidos:' . count($response_err), $response = null);

            //code...
        } catch (\Throwable $th) {
            LogController::log(1, 'excepcion job fallido', $th->getMessage(), $response = null);
        }
    }
}