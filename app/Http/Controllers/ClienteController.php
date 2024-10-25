<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\FichaMedica;
use App\Models\Frecuencia;
use Exception;
use Filament\Notifications\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClienteController extends Controller
{
    public static function crear($nombre, $cedula, $email, $telefono)
    {

        try {

            $user = Auth::user();

            if(Cliente::where('cedula', '=', $cedula)->exists() || Cliente::where('email', '=', $email)->exists())
            {
                throw new Exception("El Cliente ya se encuentra registrado. La Cédula o el Correo Electrónico ya exiten, por favor intente con otro gracias.!", 401);
            }

            $cliente = new Cliente();
            $cliente->nombre      = strtoupper($nombre);
            $cliente->cedula      = $cedula;
            $cliente->email       = $email;
            $cliente->telefono    = $telefono;
            $cliente->user_id     = $user->id;
            $cliente->responsable = $user->name;

            $cliente->save();

            /** El nuevo cliente es cargado en la tabla de frecuencias
             * para fines estadisticos
             */
            $cliente_nuevo = new Frecuencia();
            $cliente_nuevo->cliente_id  = $cliente->id;
            $cliente_nuevo->nombre      = strtoupper($cliente->nombre);
            $cliente_nuevo->sucursal_id = $user->sucursal_id;
            $cliente_nuevo->save();

            Notification::make()
                ->title('NOTIFICACIÓN')
                ->icon('heroicon-m-shield-check')
                ->iconColor('success')
                ->color('success')
                ->body('El Cliente fue registrado con éxito')
                ->send();


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
