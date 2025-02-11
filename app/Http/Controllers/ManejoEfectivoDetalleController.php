<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ManejoEfectivoDetalle;
use Filament\Notifications\Notification;

class ManejoEfectivoDetalleController extends Controller
{
    static function crear_detalle($asiento_id, $sucursal_id, $responsable, $monto, $deduccion = null, $fecha, $observacion, $total) {
        try {
            //creamos el detalle del asiento en la tabla manejo_efectivo_detalles

            $detalle = new ManejoEfectivoDetalle();
            $detalle->manejo_efectivo_id = $asiento_id;
            $detalle->sucursal_id = $sucursal_id;
            $detalle->responsable = $responsable;
            $detalle->monto = $monto;
            $detalle->deduccion = $deduccion != null ? $deduccion : 0.00;
            $detalle->fecha = $fecha;
            $detalle->observacion = $observacion;
            $detalle->total = $total;
            $detalle->save();

            Notification::make()
                ->title('NOTIFICACIÓN')
                ->icon('heroicon-c-x-circle')
                ->color('danger')
                ->iconColor('danger')
                ->body('El registrio fue creado con exito.')
                ->send();
            
        } catch (\Throwable $th) {
            dd($th);
        }
        
    }
}