<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Models\Asistencia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\Debugbar\Facades\Debugbar;
use Filament\Notifications\Notification;

class AsistenciaController extends Controller
{
    
    static function entrada($cedula)
    {
        try {

            $user = User::select('id', 'cedula', 'name')->where('cedula', $cedula)->first();

            if(isset($user)){

                //Restriccion para evitar duplicados
                $asistencia = Asistencia::where('empleado_id', $user->id)->where('fecha', now()->format('d-m-Y'))->where('entrada', '1')->first();
        
                if (isset($asistencia)) {
                    throw new Exception("El empleado ya ha registrado su entrada");
                    
                } else {
                    $asistencia = new Asistencia();
                    $asistencia->empleado_id = $user->id;
                    $asistencia->entrada = '1';
                    $asistencia->fecha = now()->format('d-m-Y');
                    $asistencia->save();
    
                    return true;
                    
                }
                    
            }else{
                throw new Exception("La informacion del empleado no fue encontrada, favor verifique la informacion");
                return false;
            }
            
        } catch (\Throwable $th) {
            Notification::make()
                ->title('NOTIFICACIÓN')
                ->icon('heroicon-c-x-circle')
                ->color('danger')
                ->iconColor('danger')
                ->body($th->getMessage())
                ->send();
        }

    }

    static function salida($cedula)
    {
        try {

            $user = User::select('id', 'cedula', 'name')->where('cedula', $cedula)->first();

            if (isset($user)) {

                //Restriccion para evitar duplicados
                $asistencia = Asistencia::where('empleado_id', $user->id)->where('fecha', now()->format('d-m-Y'))->where('entrada', '1')->first();

                if (isset($asistencia)) {
                    $asistencia->salida = '1';
                    $asistencia->save();

                    return true;
                } else {
                    throw new Exception("El empleado no tiene registro de entrada");
                    
                }
                
            } else {
                throw new Exception("La informacion del empleado no fue encontrada, favor verifique la informacion");
                return false;
            }
            
        } catch (\Throwable $th) {
            Notification::make()
                ->title('NOTIFICACIÓN')
                ->icon('heroicon-c-x-circle')
                ->color('danger')
                ->iconColor('danger')
                ->body($th->getMessage())
                ->send();
        }
    }
    
}