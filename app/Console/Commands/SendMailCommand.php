<?php

namespace App\Console\Commands;

use App\Mail\NotificacionesEmail;
use App\Models\Cliente;
use App\Models\VentaServicio;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class SendMailCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-mail-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comando para enviar correos masivos';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // $fecha_anterior =  date("d-m-Y", strtotime(date("d-m-Y") . "-15 day"));
        // $clientes = DB::table('venta_servicios')
        // ->select('cliente_id', 'cliente')
        // ->groupBy('cliente_id', 'cliente')
        // ->where('fecha_venta', $fecha_anterior)
        // ->get();

        // foreach($clientes as $item)
        // {
        //     $data = Cliente::find($item->cliente_id);

        //     if($data->email != null){
        //         $view = 'emails.correo_masivo';
        //         $mailData = [
        //             'cliente' => $item->nombre.' '.$item->apellido,
        //         ];

        //         Mail::to($item->email)->send(new NotificacionesEmail($mailData, $view));

        //     }

        // }

        // $clientes_tel = VentaServicio::where('fecha_venta', $fecha_anterior)->get();
        // foreach($clientes_tel as $items)
        // {
        //     $data = Cliente::find($items->cliente_id);

        //     if($data->telefono != null){
        //         $params = array(
        //             'token' => '863lb4l0wmldpl3s',
        //             'to' => '+58'.$data->telefono,
        //             'body' => 'Prueba de mensaje desde piedy code'
        //         );
        //         $curl = curl_init();
        //         curl_setopt_array($curl, array(
        //             CURLOPT_URL => "https://api.ultramsg.com/instance83564/messages/chat",
        //             CURLOPT_RETURNTRANSFER => true,
        //             CURLOPT_ENCODING => "",
        //             CURLOPT_MAXREDIRS => 10,
        //             CURLOPT_TIMEOUT => 30,
        //             CURLOPT_SSL_VERIFYHOST => 0,
        //             CURLOPT_SSL_VERIFYPEER => 0,
        //             CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        //             CURLOPT_CUSTOMREQUEST => "POST",
        //             CURLOPT_POSTFIELDS => http_build_query($params),
        //             CURLOPT_HTTPHEADER => array(
        //                 "content-type: application/x-www-form-urlencoded"
        //             ),
        //         ));

        //         $response = curl_exec($curl);
        //         $err = curl_error($curl);

        //         curl_close($curl);

        //         if ($err) {
        //             echo "cURL Error #:" . $err;
        //         } else {
        //             echo $response;
        //         }

        //     }

        // }

        // $res = [
        //     'Emails' => 'Correo enviado a '.count($clientes).' destinatarios correctamente',
        //     'Mensajes' => 'Mensajes enviados a '.count($clientes_tel).' destinatarios correctamente'
        // ];


        // return response()->json($res);

        // $clientes = Cliente::where('email', '!=', '')
        // ->where('id', '>', 461)
        // ->get();

        // foreach($clientes as $item){
        //     $view = 'emails.promociones.DiaDeLasMadres';
        //     $email = $item->email;
        //     $mailData = [
        //         'cliente' => $item->nombre.' '.$item->apellido
        //     ];

        //     Mail::to($email)->send(new NotificacionesEmail($mailData, $view));

        // }






        $view = 'emails.promociones.DiaDeLasMadres';
            $email = 'gusta.acp@gmail.com';
            $mailData = [
                'cliente' => 'Gustavo Camacho'
            ];

            Mail::to($email)->send(new NotificacionesEmail($mailData, $view));



        // $params = array(
        //     'token' => '863lb4l0wmldpl3s',
        //     'to' => '+584147365309',
        //     'image' => 'https://piedy.sqlapio.net/images/PROMO_DiaDeLasMadres.jpg',
        //     'caption' => '¡Este Día de las Madres, regala el cuidado que ella merece! Disfruta de nuestra promoción especial en quiropedia o manicure y haz que mamá se sienta mimada y radiante. ¡Reserva ahora y dale a mamá un momento de relajación y belleza inigualable!. https://linktr.ee/Piedyccs'
        //             );
        //             $curl = curl_init();
        //                 curl_setopt_array($curl, array(
        //                 CURLOPT_URL => "https://api.ultramsg.com/instance83564/messages/image",
        //                 CURLOPT_RETURNTRANSFER => true,
        //                 CURLOPT_ENCODING => "",
        //                 CURLOPT_MAXREDIRS => 10,
        //                 CURLOPT_TIMEOUT => 30,
        //                 CURLOPT_SSL_VERIFYHOST => 0,
        //                 CURLOPT_SSL_VERIFYPEER => 0,
        //                 CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        //                 CURLOPT_CUSTOMREQUEST => "POST",
        //                 CURLOPT_POSTFIELDS => http_build_query($params),
        //                 CURLOPT_HTTPHEADER => array(
        //                     "content-type: application/x-www-form-urlencoded"
        //                 ),
        //             ));

        //             $response = curl_exec($curl);
        //             $err = curl_error($curl);

        //             curl_close($curl);

        //             if ($err) {
        //                 echo "cURL Error #:" . $err;
        //             } else {
        //                 echo $response;
        //             }

                    $this->info('tarea 1');
    }
}
