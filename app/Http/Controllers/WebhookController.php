<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Cita;
use App\Models\TasaBcv;
use Illuminate\Http\Request;

class WebhookController extends Controller
{
    public function webhookAgendarCita ($name, $phone, $fecha, $hora) {
        // dd($name, $phone, $fecha, date("Y-m-d", strtotime($fecha)), $hora, str_replace(' ', '', $hora));
        $fecha_api = date("Y-m-d", strtotime($fecha));
        
        if($fecha_api >= now()->format('Y-m-d'))
        {
            
            $citas = new Cita();
            $citas->cod_cita = 'Pci-'.random_int(11111, 99999);
            $citas->telefono = $phone;
            $citas->cliente = $name;
            $citas->hora = str_replace(' ', '', $hora);
            $citas->fecha = Carbon::parse($fecha_api)->isoFormat('dddd, D MMM');
            $citas->fecha_formateada = $fecha_api;
            $citas->responsable = 'PiedyBot';
            $citas->status = 1;
            $citas->sucursal_id = 1;
            $citas->save();

            return response()->json(['message' => 'cita agendada'], 200);
            
        }else{
            return response()->json(['message' => 'La fecha debe ser mayor o igual a la fecha actual'], 400);

        }

    }
    //
}