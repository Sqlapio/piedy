<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Cita;
use App\Models\TasaBcv;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    /**
     * Webhook para agendar una cita a traves de PiedyBot.
     * 
     * Recibe los siguientes par pametros:
     * - $name: nombre del cliente
     * - $phone: telefono del cliente
     * - $fecha: fecha de la cita
     * - $hora: hora de la cita
     * - $servicio_id: id del servicio
     * 
     * Si la fecha es mayor o igual a la fecha actual, crea una nueva cita en la BD
     * y la guarda.
     * 
     * Si hay un error al guardar la cita, devuelve un 500 con un mensaje de error.
     * 
     * Si la fecha es menor a la fecha actual, devuelve un 400 con un mensaje de error.
     * 
     * Devuelve un 200 con un mensaje de confirmacion si se agendo correctamente.
     */
    public function webhookAgendarCita ($name, $phone, $fecha, $hora, $servicio_id) {

        $fecha_api = date("Y-m-d", strtotime($fecha));
        
        if($fecha_api >= now()->format('Y-m-d'))
        {
            try {
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
                $citas->servicio_id = $servicio_id;
                $citas->save();

                Log::info('Nueva cita agendada por PiedyBot: '.$citas->cod_cita);
                return response()->json(['message' => 'cita agendada'], 200);
                
            } catch (\Throwable $th) {
                Log::error('Error al agendar cita por PiedyBot: '.$th->getMessage());
                return response()->json(['message' => 'La fecha debe ser mayor o igual a la fecha actual'], 500);
            }
            
        }else{
            return response()->json(['message' => 'La fecha debe ser mayor o igual a la fecha actual'], 400);

        }

    }
    //
}