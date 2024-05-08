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


		} catch (\Throwable $th) {
			$message = $th->getMessage();
			dd('Error UtilsController.send_mail()', $message);
		}
	}
}
