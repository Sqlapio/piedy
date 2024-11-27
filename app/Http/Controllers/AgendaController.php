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
            if(count($cita) > 0)
            {
                if($cita[0]->cliente_id == $cliente_id)
                {
                    throw new Exception("No puede agendar citas al mismo cliente a la misma hora y en la misma fecha. Valide la información y vuelva a intentar");
                    
                }elseif($cita[0]->empleado_id == $empleado_id)
                {
                    throw new Exception("No puede agendar citas al cliente con el mismo tecnico a la misma hora y en la misma fecha. Valide la información y vuelva a intentar");
                }
            }

            $cliente = Cliente::find($cliente_id);

            // $dia = UtilsController::agenda($mes, $opcion);
            $citas = new Cita();
            $citas->cod_cita = 'Pci-'.random_int(11111, 99999);
            $citas->cliente_id = $cliente->id;
            $citas->correo = $cliente->email;
            $citas->telefono = $cliente->telefono;
            $citas->cliente = $cliente->nombre;
            $citas->hora = date("h:i a", strtotime($hora));
            $citas->fecha = Carbon::parse($fecha_formateada)->isoFormat('dddd, D MMM');
            $citas->fecha_formateada = $fecha_formateada;
            $citas->responsable = Auth::user()->name;
            $citas->empleado_id = $empleado_id;
            $citas->servicio_id = $servicio_id;
            $citas->sucursal_id = Auth::user()->sucursal_id;
            $citas->status = 1;
            $citas->save();
           
            Notification::make()
                ->title('NOTIFICACIÓN')
                ->icon('heroicon-o-shield-check')
                ->iconColor('danger')
                ->body('La cita fue agendada con éxito')
                ->send();

            $cliente_citado = Cita::where('id', $citas->id)->first();
            $type = 'cliente';
            $mailData = [
                'cliente_email' => $cliente_citado->correo,
                'cliente_fullname' => $cliente_citado->cliente,
                'fecha_cita' => $cliente_citado->fecha,
                'hora_cita' => $cliente_citado->hora,
                'telefono' => $cliente_citado->telefono,
            ];

            if(isset($cliente_id)){
                /**Notificacion por Whatsapp */
                // NotificacionesController::notificacion_cita_wp($mailData);

                /**Notificacion por correo */
                // NotificacionesController::notification($mailData, $type);

            }else{
                /**Notificacion por Whatsapp */
                // NotificacionesController::notificacion_cita_wp($mailData);
            }

            return true;

            // redirect(route('citas'));

        } catch (\Throwable $th) {
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
            Notification::make()
                ->title('NOTIFICACIÓN')
                ->icon('heroicon-o-shield-check')
                ->iconColor('danger')
                ->body($th->getMessage())
                ->send();
        }
    }
}