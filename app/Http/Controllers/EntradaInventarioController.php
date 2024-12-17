<?php

namespace App\Http\Controllers;

use App\Models\EntradaInventario;
use App\Models\Inventario;
use App\Models\Producto;
use Filament\Notifications\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EntradaInventarioController extends Controller
{
    public static function crear_entrada($inventario_id, $cantidad, $movimiento)
    {
        try{
            
            $info = Inventario::find($inventario_id);
            
            $entrada = new EntradaInventario();
            $entrada->cod_movimiento     = 'Psi-'.random_int(11111, 99999);
            $entrada->producto_id        = $info->producto_id;
            $entrada->almacen_id         = $info->almacen_id;
            $entrada->cantidad           = $cantidad;
            $entrada->tipo_movimiento    = $movimiento;
            $entrada->responsable        = Auth::user()->name;
            $entrada->save();
            
            //escribimos en el log del sistema
            $descripcion = 'Reposición. Producto: '.Producto::find($entrada->producto_id)->descripcion.', Cantidad: '.$cantidad;
            LogController::log(Auth::user()->id, $movimiento,$descripcion, $response = null);

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