<?php

namespace App\Console\Commands;

use App\Models\Cita;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class RecordatorioCitaTecnico extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:recordatorio-cita-tecnico';

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
        $citas = Cita::where('fecha_formateada', date('Y-m-d'))
        ->where('status', 1)
        ->get();

        foreach ($citas as $cita) {

            $empleado = User::where('id', $cita->empleado_id)->first();
        
            $body = <<<HTML

            *Sr(a):* {$empleado->name}

            Le recordamos que para el día de hoy tiene una cita agendada, a continuación los detalles:

            *Fecha:* {$cita->fecha}
            *Hora:* {$cita->hora}

            *Con el fin de respetar el tiempo de nuestros clientes, le sugerimos debe llegar puntual a la cita.*
            HTML;

            $params = array(
                'token' => env('TOKEN_API_WHATSAPP'),
                'to' => $empleado->telefono,
                'image' => env('IMAGE'),
                'caption' => $body
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
                Log::info('Recordatorio enviado al tecnico con exito: ' . $response);
            }

            if (isset($res['error'])) {
                Log::error('Error al enviar el recordatorio: ' . $response);
            }
        }

        $this->info($response);
    }
}