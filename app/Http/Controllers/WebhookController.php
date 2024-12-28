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
    public function webhookAgendarCita($name, $phone, $fecha, $hora, $servicio_id)
    {
        try {

            //Eliminar espacios en blanco de la fecha y la hora
            $fecha = trim($fecha);
            $hora = trim($hora);

            $fecha_api = date("Y-m-d", strtotime($fecha));

            $hora_formateada = date("H:i:s", strtotime($hora));

            //Eliminar espacios en blanco del nombre del cliente y del telefono
            $name = trim($name);
            $phone = trim($phone);

            if ($name == null || $phone == null || $fecha == null || $hora == null || $servicio_id == null) {
                return response()->json(['message' => 'Por favor llene todos los campos para poder agendar la cita'], 400);
            } else {

                if ($fecha_api >= now()->format('Y-m-d')) //Valicacion de fecha menor a la fecha actual
                {
                    if ($fecha_api < date("Y-m-d", strtotime(now()->format('Y-m-d') . "+ 15 days"))) {
                        //Si la fecha de la cita es mayor a 15 dias, no se agenda la cita
                        if ($hora_formateada >= '10:00' && $hora_formateada < '22:00') {
                            //Si la hora de la cita es menor a 10:00am o mayor a 22:00pm, no se agenda la cita
                            if ($servicio_id > 0) {
                                //Si el servicio_id exite mas de 4 veces no se agrega la cita
                                $citas = Cita::whereBetween('servicio_id', [1, 8])
                                    ->where('fecha_formateada', $fecha_api)
                                    ->where('hora', $hora)
                                    ->where('status', 1)
                                    ->count();

                                if ($citas >= 4) {
                                    return response()->json(['message' => 'No disponemos de técnico disponible para la hora que solicita la cita, por favor modifique la hora'], 400);
                                } else {
                                    $citas = new Cita();
                                    $citas->cod_cita = 'Pci-' . random_int(11111, 99999);
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

                                    Log::info('Nueva cita agendada por PiedyBot: ' . $citas->cod_cita);
                                    return response()->json(['message' => 'cita agendada'], 200);
                                }
                            }
                        } else {
                            //log
                            Log::error('Error al agendar cita por PiedyBot: La hora debe estar entre las 10:00am y las 10:00pm. Por favor intente nuevamente');
                            return response()->json(['message' => 'Nuestra hora de atención es de 10:00am a 10:00pm, por favor modifique la hora.'], 400);
                        }
                    } else {
                        //log
                        Log::error('Error al agendar cita por PiedyBot: La fecha debe ser menor a 15 dias');
                        return response()->json(['message' => 'La fecha debe ser menor a 15 días'], 400);
                    }
                } else {
                    return response()->json(['message' => 'La fecha debe ser mayor o igual a la fecha actual'], 400);
                }
            }
            //code...
        } catch (\Throwable $th) {
            LogController::log(1, 'PiedyBot', $th->getMessage(), $response = null);
            return response()->json(['message' => 'Se produjo un error al agendar la cita, por favor intente mas tarde'], 500);
        }
        
    }
    //
}