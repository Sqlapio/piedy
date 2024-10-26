<?php

namespace App\Http\Controllers;

use App\Models\MovimientoInventario;
use Filament\Notifications\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MovimientoInventarioController extends Controller
{
    public static function registrar_movimiento($producto_id, $cantidad, $sucursal_id, $codigo, $tipoMovimiento)
    {
        try {

            //...Carga del movimiento de inventario
            $movimiento = new MovimientoInventario();
            $movimiento->producto_id = $producto_id;
            $movimiento->cantidad = $cantidad;
            $movimiento->sucursal_id = $sucursal_id;
            $movimiento->codigo = $codigo;
            $movimiento->responsable = Auth::user()->name;
            $movimiento->tipo_movimiento = $tipoMovimiento;
            $movimiento->save();
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
