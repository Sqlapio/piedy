<?php

namespace App\Console\Commands;

use App\Models\Agenda;
use App\Models\Cita;
use App\Models\Cliente;
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
        $citas = Cita::where('fecha_formateada', date('Y-m-d'))
        ->where('status', 1)
        ->get();

        foreach ($citas as $cita) {

            $cliente = Cliente::where('id', $cita->cliente_id)->first();
            $ubication = 'https://maps.google.com/maps?q=Piedy%20Sambil%20Chacao,%20Distrito%20Capital&amp;t=&amp;z=13&amp;ie=UTF8&amp;iwloc=&amp;output=embed';

            $body = <<<HTML

            *Sr(a):* {$cita->cliente}

            Le recordamos que para el día de hoy tiene una cita agendada en PIEDY. Te esperamos...

            *Fecha:* {$cita->fecha}
            *Hora:* {$cita->hora}

            *Ubicación:* {$ubication}
            HTML;

            $params = array(
                'token' => env('TOKEN_API_WHATSAPP'),
                'to' => $cliente->telefono,
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

            curl_close($curl);
        }

        $this->info('La notificacion fue enviada con exito');
    }
}
