<?php

namespace App\Http\Controllers;

use App\Mail\NotificacionesEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class NotificacionesController extends Controller
{
    static function notification($mailData, $type)
	{

		try {

			if ($type == 'cliente') {
				$view = 'emails.cliente';
				Mail::to($mailData['cliente_email'])->send(new NotificacionesEmail($mailData, $view));
			}

			if ($type == 'servicio') {
				$view = 'emails.servicio_facturado';
				Mail::to($mailData['user_email'])->send(new NotificacionesEmail($mailData, $view));
			}

            if ($type == 'reseteo_password') {
				$view = 'emails.reseteo_password';
				Mail::to($mailData['user_email'])->send(new NotificacionesEmail($mailData, $view));
			}

		} catch (\Throwable $th) {
			$message = $th->getMessage();
			dd('Error UtilsController.send_mail()', $message);
		}
	}
}
