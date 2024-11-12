<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Cliente;
use App\Models\Horario;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Filament\Notifications\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AgendaController extends Controller
{
    static function agendar_cita($cliente_id, $servicio_id, $empleado_id, $fecha_formateada, $hora_id)
    {

        try {

            $hora = Horario::find($hora_id)->hora;

            // $dia = UtilsController::agenda($mes, $opcion);
            $citas = new Cita();
            $citas->cod_cita = 'Pci-'.random_int(11111, 99999);
            $cliente_existe = Cita::where('cliente_id', $cliente_id)->where('fecha_formateada', $fecha_formateada)->first();
            // dd($cliente_existe, $cliente_existe->fecha_formateada, $fecha_formateada);
            /**Restriccion para dia anterior */
            if($fecha_formateada < date('Y-m-d')){

                throw new Exception("No puede agendar citas en días anteriores a la fecha actual. Por favor intente con otro dia");
            }
            // date("h:i a", strtotime($hora))
            if(isset($cliente_existe) && $cliente_existe->fecha_formateada == $fecha_formateada){
                throw new Exception("No puede agendar citas al mismo cliente a la misma hora. Debe agendar en otra hora");

            }else{

                $cliente = Cliente::find($cliente_id);
                $citas->cliente_id = $cliente->id;
                $citas->correo = $cliente->email;
                $citas->telefono = $cliente->telefono;
                $citas->cliente = $cliente->nombre.' '.$cliente->apellido;
                $citas->hora = date("h:i a", strtotime($hora));
                $citas->fecha = Carbon::parse($fecha_formateada)->isoFormat('dddd, D MMM');
                $citas->fecha_formateada = $fecha_formateada;
                $citas->responsable = Auth::user()->name;
                $citas->empleado = User::find($empleado_id)->name;
                $citas->status = 1;
                $citas->save();
            }

            if(!isset($cliente_existe)){

                $cliente = Cliente::find($cliente_id);
                $citas->cliente_id = $cliente->id;
                $citas->correo = $cliente->email;
                $citas->telefono = $cliente->telefono;
                $citas->cliente = $cliente->nombre.' '.$cliente->apellido;
                $citas->hora = date("h:i a", strtotime($hora));
                $citas->fecha = Carbon::parse($fecha_formateada)->isoFormat('dddd, D MMM');
                $citas->fecha_formateada = $fecha_formateada;
                $citas->responsable = Auth::user()->name;
                $citas->empleado = User::find($empleado_id)->name;
                $citas->status = 1;
                $citas->save();

            }

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
