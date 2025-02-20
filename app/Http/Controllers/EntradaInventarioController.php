<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Inventario;
use Illuminate\Http\Request;
use App\Models\EntradaInventario;
use App\Models\MovimientoInventario;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;

class EntradaInventarioController extends Controller
{
    public static function crear_entrada($inventario_id, $cantidad)
    {
        try{
            
            $info = Inventario::find($inventario_id);
            $info_producto = Producto::where('id', $info->producto_id)->first();

            $entrada = new MovimientoInventario();
            $entrada->codigo            = 'PEI-' . random_int(11111, 99999);
            $entrada->almacen_id        = $info->almacen_id;
            $entrada->producto_id       = $info->producto_id;
            $entrada->cantidad          = $cantidad;
            $entrada->contenido_neto    = $info_producto->contenido_neto;
            $entrada->unidad            = $info_producto->unidad;
            $entrada->tipo_movimiento   = 'entrada';
            $entrada->responsable       = Auth::user()->name;
            $entrada->save();
            
            //escribimos en el log del sistema
            $descripcion = 'Entrada de Inventario. Producto: '.$info_producto->descripcion.', Cantidad: '.$cantidad;
            LogController::log(Auth::user()->id, 'entrada de inventario',$descripcion, $response = null);

        }catch (\Throwable $th) {
            LogController::log(Auth::user()->id, 'excepcion-EntradaInventarioController(crear_entrada)', $th->getMessage(), $response = null);
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