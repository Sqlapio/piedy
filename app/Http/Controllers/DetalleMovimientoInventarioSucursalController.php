<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;
use App\Models\DetalleMovimientoInventarioSucursal;

class DetalleMovimientoInventarioSucursalController extends Controller
{
    static function crearDetalle($movimientos, $auditoria_id)
    {
        try {

            $transaction = DB::transaction(function () use ($movimientos, $auditoria_id) {

                for ($i = 0; $i < count($movimientos); $i++) {
                    $detalle = new DetalleMovimientoInventarioSucursal();
                    $detalle->auditoria_inventario_id = $auditoria_id;
                    $detalle->producto_id               = $movimientos[$i]['producto_id'];
                    $detalle->cantidad                  = $movimientos[$i]['cantidad'];
                    $detalle->costo                     = $movimientos[$i]['costo'];
                    $detalle->total                     = $movimientos[$i]['total'];
                    $detalle->consumo                   = $movimientos[$i]['consumo'];
                    $detalle->fecha_movimiento          = $movimientos[$i]['fecha'];
                    $detalle->responsable               = $movimientos[$i]['responsable'];
                    $detalle->save();
                }
            });

            return [
                'success' => true,
                'message' => 'La Auditoria de inventario fue creada correctamente.',
            ];
        } catch (\Throwable $th) {
            DB::rollBack();
            LogController::log(Auth::user()->id, 'excepcion-DetalleMovimientoInventarioGeneralController(crearDetalle)', $th->getMessage(), $response = null);
            Notification::make()
                ->title('Notificacion: DetalleMovimientoInventarioGeneralController::crearDetalle()')
                ->icon('heroicon-o-shield-check')
                ->iconColor('danger')
                ->body($th->getMessage())
                ->send();
        }
    }
}