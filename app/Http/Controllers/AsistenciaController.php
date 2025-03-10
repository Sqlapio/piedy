<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Asistencia;
use Illuminate\Http\Request;

class AsistenciaController extends Controller
{
    
    static function entrada($cedula)
    {
        try {

            $user = User::where('cedula', $cedula)->first();

            if(isset($user)){

                $asistencia = new Asistencia();
                $asistencia->empleado_id = $user->id;
                $asistencia->dia = date('m');
                $asistencia->tipo_registro = 'entrada';
                $asistencia->save();

                return true;
                
            }else{
                return false;
            }
            
        } catch (\Throwable $th) {
            dd($th);
        }

    }

    static function salida($cedula)
    {
        try {

            $user = User::where('cedula', $cedula)->first();

            if (isset($user)) {

                $asistencia = new Asistencia();
                $asistencia->empleado_id = $user->id;
                $asistencia->dia = date('m');
                $asistencia->tipo_registro = 'salida';
                $asistencia->save();

                return true;
                
            } else {
                return false;
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
    
}