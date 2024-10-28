<?php

namespace App\Http\Controllers;

use App\Models\Inventario;
use App\Models\InventarioSucursal;
use App\Models\Producto;
use App\Models\Sucursal;
use Exception;
use Filament\Notifications\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InventarioController extends Controller
{
    public static function reposicion($inventario_id, $cantidad)
    {
        try {

            if($cantidad <= 0){
                throw new Exception("No puede realizar la reposición ya que el valor es igual a cero(0). Por favor vuelva a intentarlo colocando un numero mayor a cero(0).", 401);
            }

            $inventario = Inventario::find($inventario_id);
            $inventario->cantidad += $cantidad;
            $inventario->save();

            if($inventario->save()){

                $descripcion = 'Reposición. Producto: '.$inventario->producto->descripcion.' Cantidad: '.$cantidad;
                LogInventario::log_inventario(Auth::user()->id, 'Reposición Almacen Principal', $descripcion);

                Notification::make()
                    ->title('La Reposicion se realizo con éxito.')
                    ->color('success')
                    ->icon('heroicon-o-document-text')
                    ->iconColor('success')
                    ->send();
            }

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

    public static function asigancion_sucursal($inventario_id, $sucursal_id, $cantidad)
    {
        try {

            $producto = Producto::where('id', Inventario::find($inventario_id)->producto_id)->first();

            if($cantidad <= 0)
            {
                throw new Exception("No puede realizar el movimiento ya que el inventario esta en 0. Por favor comuniquese con el Administrador", 401);
            }

            //El prodducto ya exite en la sucursal?
            $inventario_sucursal = InventarioSucursal::where('producto_id', $producto->id)
            ->where('sucursal_id', $sucursal_id)
            ->first();

            if($inventario_sucursal)
            {
                $inventario_sucursal->cantidad += $cantidad;
                $inventario_sucursal->save();

            }else{
                $movimientoInv = new InventarioSucursal();
                $movimientoInv->producto_id = $producto->id;
                $movimientoInv->sucursal_id = $sucursal_id;
                $movimientoInv->cantidad    = $cantidad;
                $movimientoInv->uso         = $producto->uso;
                $movimientoInv->responsable = Auth::user()->name;
                $movimientoInv->save();

            }

            $restaExistencia = Inventario::where('producto_id', $producto->id)->first();
            $restaExistencia->update([
                'cantidad' => $restaExistencia->cantidad - $cantidad
            ]);

            $descripcion = 'Reposición. Producto: '.$producto->descripcion.', Sucursal: '. Sucursal::find($sucursal_id)->nombre .', Cantidad: '.$cantidad;
            LogInventario::log_inventario(Auth::user()->id, 'Reposición en Sucursal', $descripcion);

            Notification::make()
                ->title('El Movimiento se realizo con éxito.')
                ->color('success')
                ->icon('heroicon-o-document-text')
                ->iconColor('success')
                ->send();

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
