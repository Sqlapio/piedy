<?php

namespace App\Http\Controllers;

use App\Models\Inventario;
use App\Models\SalidaInventario;
use App\Models\Producto;
use App\Models\Sucursal;
use Filament\Notifications\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SalidaInventarioController extends Controller
{

    public static function crear_salida($inventario_id, $sucursal_id, $cantidad, $movimiento)
    {
        try{

            $info = Inventario::find($inventario_id);

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
            $descripcion = 'Envio a sucursal. Producto: '.Producto::find($salida->producto_id)->descripcion.', Sucursal: '. Sucursal::find($sucursal_id)->nombre .', Cantidad: '.$cantidad;
            LogController::log(Auth::user()->id, $movimiento, $descripcion, $response = null);

        }catch (\Throwable $th) {
            LogController::log(Auth::user()->id, 'excepcion-SalidaInventarioController(crear_salida)', $th->getMessage(), $response = null);
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