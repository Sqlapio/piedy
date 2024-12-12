<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Cita;
use App\Models\TasaBcv;
use Illuminate\Http\Request;

class WebhookController extends Controller
{
    public function webhookAgendarCita ($name, $phone, $fecha, $hora) {
// dd(Carbon::parse($fecha)->isoFormat('dddd, D MMM'));
            // $dia = UtilsController::agenda($mes, $opcion);
            $citas = new Cita();
            $citas->cod_cita = 'Pci-'.random_int(11111, 99999);
            $citas->telefono = $phone;
            $citas->cliente = $name;
            $citas->hora = $hora;
            $citas->fecha = Carbon::parse($fecha)->isoFormat('dddd, D MMM');
            $citas->fecha_formateada = $fecha;
            $citas->responsable = 'PiedyBot';
            $citas->status = 1;
            $citas->save(); `

        if($citas->save()) {
            return response()->json(['message' => 'cita agendada'], 200);
            } else {
                return response()->json(['message' => 'Error al agendar'], 400);
                }
    }
    //
}