<?php

namespace App\Http\Controllers;

use App\Mail\NotificacionesEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class NotificacionesController extends Controller
{
    static function notification($mailData, $type, $asunto=null)
	{

		try {

			if ($type == 'cliente') {
				$view = 'emails.cliente';
                $subject = 'Cliente Piedy';
				Mail::to($mailData['cliente_email'])->send(new NotificacionesEmail($mailData, $view, $subject));
			}

			if ($type == 'servicio') {
				$view = 'emails.servicio_facturado';
                $subject = 'Servicio Facturado';
				Mail::to($mailData['user_email'])->send(new NotificacionesEmail($mailData, $view, $subject));
			}

            if ($type == 'reseteo_password') {
				$view = 'emails.reseteo_password';
                $subject = 'Reseteo de password';
				Mail::to($mailData['user_email'])->send(new NotificacionesEmail($mailData, $view, $subject));
			}

            if ($type == 'servicio_anulado') {
				$view = 'emails.servicio_anulado';
                $subject = 'Servicio Anulado';
				Mail::to($mailData['user_email'])->send(new NotificacionesEmail($mailData, $view, $subject));
			}

            if ($type == 'cierre_diario') {
				$view = 'emails.cierre_diario';
                $subject = 'Cierre Diario';
				Mail::to($mailData['user_email'])->send(new NotificacionesEmail($mailData, $view, $subject));
			}

            if ($type == 'cierre_general') {
				$view = 'emails.cierre_general';
                $subject = 'Cierre General';
				Mail::to($mailData['user_email'])->send(new NotificacionesEmail($mailData, $view, $subject));
			}

            if ($type == 'gift-card') {
                $view = 'emails.gift-card';
                $subject = 'GiftCard';
                Mail::to($mailData['user_email'])->send(new NotificacionesEmail($mailData, $view, $subject));
            }

            if ($type == 'gift-card-creada') {
                $view = 'emails.gift-card-creada';
                $subject = 'operación: '.$asunto;
                Mail::to($mailData['user_email'])->send(new NotificacionesEmail($mailData, $view, $subject));
            }

            if ($type == 'gift-card-usada') {
                $view = 'emails.gift-card-usada';
                $subject = 'Fecha de Uso: '.date('d-m-Y');
                Mail::to($mailData['user_email'])->send(new NotificacionesEmail($mailData, $view, $subject));
            }

            if ($type == 'membresia-usada') {
                $view = 'emails.membresia-usada';
                $subject = 'Membresia';
                Mail::to($mailData['user_email'])->send(new NotificacionesEmail($mailData, $view, $subject));
            }

            if ($type == 'membresia') {
                $view = 'emails.membresia';
                $subject = 'Membresias Piedy';
                Mail::to($mailData['user_email'])->send(new NotificacionesEmail($mailData, $view, $subject));
            }

            if ($type == 'membresia-renovada') {
                $view = 'emails.membresia-renovada';
                $subject = 'Membresia';
                Mail::to($mailData['user_email'])->send(new NotificacionesEmail($mailData, $view, $subject));
            }

            if ($type == 'membresia-activada') {
                $view = 'emails.membresia-activada';
                $subject = 'Membresia '.$asunto;
                Mail::to($mailData['user_email'])->send(new NotificacionesEmail($mailData, $view, $subject));
            }


		} catch (\Throwable $th) {
			$message = $th->getMessage();
			dd('Error UtilsController.send_mail()', $message);
		}
	}

    static function notificacion_cita_wp(array $data)
    {

        try {

            $ubication = 'https://maps.google.com/maps?q=Piedy%20Sambil%20Chacao,%20Distrito%20Capital&amp;t=&amp;z=13&amp;ie=UTF8&amp;iwloc=&amp;output=embed';

            $body = <<<HTML

            *Sr(a):* {$data['cliente_fullname']}

            Le informamos que Usted acaba de agendar una cita en Piedy. Te esperamos...

            *Detalle:*
            *Fecha:* {$data['fecha_cita']}
            *Hora:* {$data['hora_cita']}

            *Ubicación:* {$ubication}
            HTML;

            $params = array(
                'token' => env('TOKEN_API_WHATSAPP'),
                'to' => $data['telefono'],
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

        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
