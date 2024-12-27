<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Producto;
use App\Models\Disponible;
use Illuminate\Http\Request;
use App\Mail\NotificacionesEmail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\LogController;
use Filament\Notifications\Notification;

class NotificacionesController extends Controller
{
    static function notification($mailData, $type, $asunto = null)
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
                $subject = 'operación: ' . $asunto;
                Mail::to($mailData['user_email'])->send(new NotificacionesEmail($mailData, $view, $subject));
            }

            if ($type == 'gift-card-usada') {
                $view = 'emails.gift-card-usada';
                $subject = 'Fecha de Uso: ' . date('d-m-Y');
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
                $subject = 'Membresia ' . $asunto;
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

            $link_confirmar = env('LINK_CONFIRMACION') . $data['id'];
            $link_cancelar = env('LINK_CANCELACION') . $data['id'];

            $ubication = 'https://maps.google.com/maps?q=Piedy%20Sambil%20Chacao,%20Distrito%20Capital&amp;t=&amp;z=13&amp;ie=UTF8&amp;iwloc=&amp;output=embed';

            $body = <<<HTML

            *Sr(a):* {$data['cliente_fullname']}

            Le informamos que Usted acaba de agendar una cita en Piedy. Te esperamos...

            *Detalle:*
            *Fecha:* {$data['fecha_cita']}
            *Hora:* {$data['hora_cita']}

            *Para confirmar su asistencia por favor ingrese al link:*
            {$link_confirmar}

            *Para cancelar su asistencia por favor ingrese al link:*
            {$link_cancelar}

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
            
            $res = json_decode($response, true);

            curl_close($curl);

            if (isset($res['sent']) and $res['sent'] == 'true') {
                // dd(1);
                LogController::log(Auth::user()->id, 'sistema', 'envio exitoso', $response);
                return $response = [
                    'success' => true,
                    'message' => 'Se acaba de enviar recordatorio via WhatsApp al cliente'
                ];
              }

            if (isset($res['error'])) {
                // dd(2);
                LogController::log(Auth::user()->id, 'excepcion', 'falla de servicio WhatsApp', $response);
                return $response = [
                    'success' => false,
                    'message' => 'El mensaje no fue enviado, pongase en contacto con el administrador del sistema'
                ];
            }
            
        } catch (\Throwable $th) {
            Notification::make()
                ->title('NOTIFICACIÓN')
                ->icon('heroicon-o-document-text')
                ->iconColor('danger')
                ->color('danger')
                ->body($th->getMessage())
                ->send();
        }
    }

    static function notificacion_masiva($image, $caption)
    {

        try {

            $user_phone = Cliente::where('status', 1)->get('telefono')->toArray();

            $response_ok = [];
            $response_err = [];
            
            for ($i=0; $i < count($user_phone); $i++) {
                # code...
                $params = array(
                    'token' => env('TOKEN_API_WHATSAPP'),
                    'to' => $user_phone[$i]['telefono'],
                    'image' => env('APP_URL') . '/storage/' . $image,
                    // 'image' => env('IMAGE_PROMOCION'),

                    'caption' => $caption
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

            LogController::log(Auth::user()->id, 'mensajes enviados', 'servicio masivo WhatsApp. Total enviados: '.count($response_ok), $response = null);
            LogController::log(Auth::user()->id, 'excepcion', 'falla de servicio masivo WhatsApp. Total fallidos:'.count($response_err), $response = null);

            return $response = [
                'success' => true,
                'message' => 'Notificaciones enviadas: Total enviados: '.count($response_ok).', Total fallidos: '.count($response_err)
            ];

            //code...
        } catch (\Throwable $th) {
            Notification::make()
                ->title('NOTIFICACIÓN')
                ->icon('heroicon-o-document-text')
                ->iconColor('danger')
                ->color('danger')
                ->body($th->getMessage())
                ->send();
        }
    }

    static function notificacion_exitencia_minima($cantidad, $producto_id, $almacen)
    {
        try {

            $producto = Producto::where('id', $producto_id)->first();

            $body = <<<HTML

            *Srs:* Administracion Piedy

            Le informamos que la existencia del producto: *{$producto->descripcion}* ha llegado a un nivel critico, por lo que se requiere se realize su reposición.

            *Detalles:*
            *Producto:* {$producto->descripcion}
            *Existencia Actual:* {$cantidad}
            *Ubicado en:* {$almacen}
            HTML;

            $params = array(
                'token' => env('TOKEN_API_WHATSAPP'),
                'to' => env('GROUPID'),
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
                LogController::log(Auth::user()->id, 'sistema', 'envio exitoso', $response);
                return $response = [
                    'success' => true,
                    'message' => 'Notificacion enviada con exito'
                ];
            }

            if (isset($res['error'])) {
                LogController::log(Auth::user()->id, 'excepcion', 'falla de servicio WhatsApp', $response);
                return $response = [
                    'success' => false,
                    'message' => 'La Notificacion no fue enviada, por favor comunicarse con el administrador del sistema'
                ];
            }

        } catch (\Throwable $th) {
            
        }
    }
}