<?php

namespace App\Http\Controllers;

use App\Models\LogInventario as ModelsLogInventario;
use Filament\Notifications\Notification;
use Illuminate\Http\Request;

class LogInventarioController extends Controller
{
    public static function log_inventario($user_id, $accion, $descripcion)
    {
        try {

            $log = new ModelsLogInventario();
            $log->user_id       = $user_id;
            $log->accion        = $accion;
            $log->descripcion   = $descripcion;
            $log->navegador     = $_SERVER['HTTP_USER_AGENT'];
            $log->ip            = $_SERVER['REMOTE_ADDR'];
            $log->save();

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
