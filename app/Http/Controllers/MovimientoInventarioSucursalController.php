<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Filament\Notifications\Notification;
use App\Models\MovimientoInventarioSucursal;

class MovimientoInventarioSucursalController extends Controller
{
    //
    public static function crear_movimiento_inventario_sucursal($producto_id, $cantidad, $tipo_movimiento, $consumo)
    {

        try {

            //creamos la salida en la tabla de movimiento_inventario_sucursal
            $movimiento_inventario_sucursal = new MovimientoInventarioSucursal();
            $movimiento_inventario_sucursal->producto_id = $producto_id;
            $movimiento_inventario_sucursal->sucursal_id = auth()->user()->sucursal_id;
            $movimiento_inventario_sucursal->cantidad = $cantidad;
            $movimiento_inventario_sucursal->responsable = auth()->user()->name;
            $movimiento_inventario_sucursal->tipo_movimiento = $tipo_movimiento;
            $movimiento_inventario_sucursal->consumo = $consumo;
            $movimiento_inventario_sucursal->save();


            return true;
            
        } catch (\Throwable $th) {
            Notification::make()
                ->title('Notificacion')
                ->color('danger')
                ->icon('heroicon-o-shield-check')
                ->iconColor('danger')
                ->body($th->getMessage())
                ->send();
        }
    }
}