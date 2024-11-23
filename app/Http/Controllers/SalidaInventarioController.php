<?php

namespace App\Http\Controllers;

use App\Models\Inventario;
use App\Models\SalidaInventario;
use Filament\Notifications\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SalidaInventarioController extends Controller
{

    public static function crear_salida($inventario_id, $sucursal_id, $cantidad, $movimiento)
    {
        try{

            $info = Inventario::find($inventario_id)->first();

            $salida = new SalidaInventario();
            $salida->cod_movimiento     = 'Psi-'.random_int(11111, 99999);
            $salida->producto_id        = $info->producto_id;
            $salida->almacen_id         = $info->almacen_id;
            $salida->sucursal_id        = $sucursal_id;
            $salida->cantidad           = $cantidad;
            $salida->tipo_movimiento    = $movimiento;
            $salida->responsable        = Auth::user()->name;
            $salida->save();

            //escribimos en el log del sistema
            $descripcion = 'Realizo '.$movimiento;
            LogController::log_inventario(Auth::user()->id, $movimiento, $descripcion);

        }catch (\Throwable $th) {
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
