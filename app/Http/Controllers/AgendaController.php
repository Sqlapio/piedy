<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Cliente;
use App\Models\Horario;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AgendaController extends Controller
{
    static function agendar_cita($cliente_id, $servicio_id, $empleado_id, $fecha_formateada, $hora_id)
    {
        try {

            $hora = Horario::find($hora_id)->hora;
            $hora_formateada = date('h:i a', strtotime($hora));

            $cita = DB::table('citas')
                ->select('cliente_id', 'empleado_id')
                ->where('fecha_formateada', $fecha_formateada)
                ->where('hora', $hora_formateada)
                ->get();

            //Restriccion de la agenda
            if (count($cita) > 0) {
                if ($cita[0]->cliente_id == $cliente_id) {
                    throw new Exception("No puede agendar citas al mismo cliente a la misma hora y en la misma fecha. Valide la información y vuelva a intentar");
                } elseif ($cita[0]->empleado_id == $empleado_id) {
                    throw new Exception("No puede agendar citas al cliente con el mismo tecnico a la misma hora y en la misma fecha. Valide la información y vuelva a intentar");
                }
            }


            $cliente = Cliente::find($cliente_id);

            // $dia = UtilsController::agenda($mes, $opcion);
            $citas = new Cita();
            $citas->cod_cita = 'Pci-' . random_int(11111, 99999);
            $citas->cliente_id = $cliente->id;
            $citas->correo = $cliente->email;
            $citas->telefono = $cliente->telefono;
            $citas->cliente = $cliente->nombre;
            $citas->hora = date("h:ia", strtotime($hora));
            $citas->fecha = Carbon::parse($fecha_formateada)->isoFormat('dddd, D MMM');
            $citas->fecha_formateada = $fecha_formateada;
            $citas->responsable = Auth::user()->name;
            $citas->empleado_id = $empleado_id;
            $citas->servicio_id = $servicio_id;
            $citas->sucursal_id = Auth::user()->sucursal_id;
            $citas->status = 1;
            $citas->save();

            if ($citas->save()) {

                $data = [
                    'id'                => $citas->id,
                    'cliente_fullname'  => $citas->cliente,
                    'fecha_cita'        => $citas->fecha,
                    'hora_cita'         => $citas->hora,
                    'telefono'          => $citas->telefono,
                ];

                /**Notificacion por Whatsapp */
                $notificacion = NotificacionesController::notificacion_cita_wp($data);

                if ($notificacion['success'] == true) {
                    Notification::make()
                        ->title('NOTIFICACIÓN')
                        ->icon('heroicon-o-document-text')
                        ->iconColor('success')
                        ->color('success')
                        ->body($notificacion['message'])
                        ->send();
                } else {
                    Notification::make()
                        ->title('NOTIFICACIÓN')
                        ->icon('heroicon-o-document-text')
                        ->iconColor('danger')
                        ->color('danger')
                        ->body($notificacion['message'])
                        ->send();
                }

                return $response = [
                    'success' => true,
                    'message' => 'La cita fue agendada con exito!!!',
                ];
            }
        } catch (\Throwable $th) {
            LogController::log(Auth::user()->id, 'excepcion-AgendaController(agendar_cita)', $th->getMessage(), $response = null);
            Notification::make()
                ->title('NOTIFICACIÓN')
                ->icon('heroicon-o-shield-check')
                ->iconColor('danger')
                ->body($th->getMessage())
                ->send();
        }
    }

    static function asignar_tecnico($cita_id, $empleado_id)
    {

        try {

            $cita = Cita::find($cita_id);
            $cita->empleado = User::find($empleado_id)->name;
            $cita->save();

            return true;

            //code...
        } catch (\Throwable $th) {
            LogController::log(Auth::user()->id, 'excepcion-AgendaController(asignar_tecnico)', $th->getMessage(), $response = null);
            Notification::make()
                ->title('NOTIFICACIÓN')
                ->icon('heroicon-o-shield-check')
                ->iconColor('danger')
                ->body($th->getMessage())
                ->send();
        }
    }

    static function validaciones($horario_id, $fecha_formateada, $cliente_id, $servicio_id, $user_id)
    {
        // dd($horario_id, $fecha_formateada, $cliente_id, $servicio_id, $user_id);
        try {

            $hora = Horario::find($horario_id)->hora;
            $hora_formateada = date('h:ia', strtotime($hora));

            // Verificar si el horario ya se encuentra ocupado por el tecnico
            // en la fecha seleccionada
            $tecnico = Cita::where('empleado_id', $user_id)
                ->where('hora', $hora_formateada)
                ->where('fecha_formateada', $fecha_formateada)
                ->where('status', 1)
                ->where('sucursal_id', Auth::user()->sucursal_id)
                ->get();

            // Verificar si el cliente ya tiene una cita agendada en la fecha seleccionada
            $cliente = Cita::where('empleado_id', $user_id)
                ->where('cliente_id', $cliente_id)
                ->where('hora', $hora_formateada)
                ->where('fecha_formateada', $fecha_formateada)
                ->where('status', 1)
                ->where('sucursal_id', Auth::user()->sucursal_id)
                ->get();

            // Verificar si el servicio ya tiene una cita agendada en la fecha seleccionada
            $servicio = Cita::whereBetween('servicio_id', [1, 8])
                ->where('hora', $hora_formateada)
                ->where('fecha_formateada', $fecha_formateada)
                ->where('status', 1)
                ->where('sucursal_id', Auth::user()->sucursal_id)
                ->get();

            // Si el tecnico tiene un horario ocupado en la fecha seleccionada
            // con el mismo cliente, lanzar una excepcion
            if ($tecnico->count() > 0) {
                return $response = [
                    'success' => false,
                    'message' => 'El tecnico ya tiene un horario ocupado en la fecha seleccionada',
                ];
            } elseif ($cliente->count() > 0) {
                return $response = [
                    'success' => false,
                    'message' => 'El cliente ya tiene una cita agendada en la fecha seleccionada',
                ];
            } elseif ($servicio->count() >= 4) {
                return $response = [
                    'success' => false,
                    'message' => 'No puede agendar el mismo servicio mas de 4 veces a la misma hora',
                ];
            } elseif ($fecha_formateada < now()->format('Y-m-d')) {
                return $response = [
                    'success' => false,
                    'message' => 'La fecha seleccionada es menor a la fecha actual',
                ];
            } else {
                return $response = [
                    'success' => true,
                    'message' => 'El horario esta disponible',
                ];
            }

            //code...
        } catch (\Throwable $th) {
            LogController::log(Auth::user()->id, 'excepcion-AgendaController(validaciones)', $th->getMessage(), $response = null);
            Notification::make()
                ->title('NOTIFICACIÓN')
                ->icon('heroicon-o-shield-check')
                ->iconColor('danger')
                ->body($th->getMessage())
                ->send();
        }
    }

    static function confirmacion($cita_id)
    {
        try {

            $confimacion = Cita::where('id', $cita_id)->first();
            $confimacion->confirmacion = 1;
            $confimacion->save();

            LogController::log(1, 'usuario externo', 'Usuario confirmo cita, id: ' . $cita_id, $response = null);

            return view('confirmacion');
        } catch (\Throwable $th) {
            LogController::log(1, 'excepcion(confirmacion link externo)', $th->getMessage(), $response = null);
        }
    }

    static function cancelacion($cita_id)
    {

        try {

            Cita::where('id', $cita_id)->first()->update([
                'status' => 3,
                'confirmacion' => 2
            ]);

            LogController::log(1, 'usuario externo', 'Usuario cancelo cita, id: ' . $cita_id, $response = null);

            return view('cancelacion');
            
        } catch (\Throwable $th) {
            LogController::log(1, 'excepcion(cancelacion link externo)', $th->getMessage(), $response = null);
        }
    }

    static function reagendar($cita_id, $nueva_fecha, $nueva_hora)
    {

        try {

            $nueva_cita = Cita::where('id', $cita_id)->first();
            $nueva_cita->fecha = Carbon::parse($nueva_fecha)->isoFormat('dddd, D MMM');
            $nueva_cita->fecha_formateada = $nueva_fecha;
            $nueva_cita->hora = date('h:i a', strtotime($nueva_hora));
            $nueva_cita->save();

            $data = [
                'id'                => $nueva_cita->id,
                'cliente_fullname'  => $nueva_cita->cliente,
                'fecha_cita'        => $nueva_cita->fecha,
                'hora_cita'         => $nueva_cita->hora,
                'telefono'          => $nueva_cita->telefono,
            ];

            return $data;

            
        } catch (\Throwable $th) {
            LogController::log(Auth::user()->id, 'excepcion-AgendaController(reagendar)', $th->getMessage(), $response = null);
            Notification::make()
                ->title('NOTIFICACIÓN')
                ->icon('heroicon-o-shield-check')
                ->iconColor('danger')
                ->body($th->getMessage())
                ->send();
        }
    }
}