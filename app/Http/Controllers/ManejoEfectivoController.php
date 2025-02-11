<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ManejoEfectivo;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;

class ManejoEfectivoController extends Controller
{
    static function crear_asiento($user, $monto) {

        try {

            // Creamos un asiento de contabilidad
            $asiento = new ManejoEfectivo();
            $asiento->responsable = $user;
            $asiento->monto = $monto;
            $asiento->sucursal_id = Auth::user()->sucursal_id;
            $asiento->save();
            
            return $asiento->id;
            //code...
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