<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Sucursal;
use App\Models\Inventario;
use Illuminate\Http\Request;
use App\Models\SalidaInventario;
use App\Models\MovimientoInventario;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;

class SalidaInventarioController extends Controller
{

    public static function crear_salida($inventario_id, $sucursal_id, $cantidad)
    {
        try {

            $info = Inventario::find($inventario_id);
            $info_producto = Producto::where('id', $info->producto_id)->with('sucursal')->first();

            // $salida = new SalidaInventario();
            // $salida->cod_movimiento     = 'Psi-' . random_int(11111, 99999);
            // $salida->producto_id        = $info->producto_id;
            // $salida->almacen_id         = $info->almacen_id;
            // $salida->sucursal_id        = $sucursal_id;
            // $salida->cantidad           = $cantidad;
            // $salida->unidad             = Producto::where('id', $salida->producto_id)->first()->unidad;
            // $salida->tipo_movimiento    = $movimiento;
            // $salida->responsable        = Auth::user()->name;
            // $salida->save();

            $salida = new MovimientoInventario();
            $salida->codigo            = 'PSI-' . random_int(11111, 99999);
            $salida->almacen_id        = $info->almacen_id;
            $salida->producto_id       = $info->producto_id;
            $salida->cantidad          = $cantidad;
            $salida->contenido_neto    = $info_producto->contenido_neto;
            $salida->unidad            = $info_producto->unidad;
            $salida->tipo_movimiento   = 'salida';
            $salida->responsable       = Auth::user()->name;
            $salida->save();

            //escribimos en el log del sistema
            $descripcion = '(Salida)Envio a sucursal. Producto: ' . $info_producto->descripcion . ', Sucursal: ' . Sucursal::find($sucursal_id)->nombre . ', Cantidad: ' . $cantidad;
            LogController::log(Auth::user()->id, 'salida de inventario', $descripcion, $response = null);
        } catch (\Throwable $th) {
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