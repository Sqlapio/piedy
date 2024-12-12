<?php

namespace App\Http\Controllers;

use App\Models\AsignarProducto;
use App\Models\Inventario;
use App\Models\InventarioSucursal;
use App\Models\Producto;
use Exception;
use Filament\Notifications\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InventarioSucursalController extends Controller
{
    public static function cargar_movimiento($inventario_id, $sucursal_id, $cantidad)
    {
        try {

            $producto = Producto::where('id', Inventario::find($inventario_id)->producto_id)->first();

            if($cantidad <= 0)
            {
                throw new Exception("No puede realizar el movimiento ya que el inventario esta en 0. Por favor comuniquese con el Administrador", 401);
            }

            $movimientoInv = new InventarioSucursal();
            $movimientoInv->producto_id = $producto->id;
            $movimientoInv->sucursal_id = $sucursal_id;
            $movimientoInv->cantidad    = $cantidad;
            $movimientoInv->uso         = $producto->uso;
            $movimientoInv->responsable = Auth::user()->name;
            $movimientoInv->save();

            $restaExistencia = Inventario::where('producto_id', $producto->id)->first();
                $restaExistencia->update([
                    'cantidad' => $restaExistencia->cantidad - $cantidad
                ]);

            Notification::make()
                ->title('El Movimiento se realizo con éxito.')
                ->color('success')
                ->icon('heroicon-o-document-text')
                ->iconColor('success')
                ->send();

            //code...
        } catch (\Throwable $th) {
            LogController::log(Auth::user()->id, 'excepcion', $th->getMessage());
            Notification::make()
                ->title('NOTIFICACIÓN')
                ->icon('heroicon-c-x-circle')
                ->color('danger')
                ->iconColor('danger')
                ->body($th->getMessage())
                ->send();
        }

    }

    public static function asignar_producto($user_id, $cantidad, $producto_id)
    {
        try {

            $existencia_actual = InventarioSucursal::where('sucursal_id', Auth::user()->sucursal_id)
            ->where('producto_id', $producto_id)
            ->first()
            ->cantidad;

            //Validacion para saber si tenemos existencia para asignar el producto
            if($cantidad > $existencia_actual){
                throw new Exception("No hay suficiente existencia para realizar la asigancion", 401);
            }

            $cod_producto = Producto::find($producto_id)->first()->cod_producto;
            
            $producto_asignado = new AsignarProducto();
            $producto_asignado->cod_producto = $cod_producto;
            $producto_asignado->user_id = $user_id;
            $producto_asignado->producto_id = $producto_id;
            $producto_asignado->cantidad = $cantidad;
            $producto_asignado->fecha_entrega = now()->format('d-m-Y');
            $producto_asignado->responsable = Auth::user()->name;
            $producto_asignado->sucursal_id = Auth::user()->sucursal_id;
            $producto_asignado->save();

            if($producto_asignado->save()){
                $existencia = InventarioSucursal::where('sucursal_id', Auth::user()->sucursal_id)
                ->where('producto_id', $producto_id)
                ->first();
                $existencia->cantidad = $existencia->cantidad - $cantidad;
                $existencia->save();
            }



            Notification::make()
                ->title('El producto fue asignado con exito con éxito.')
                ->color('success')
                ->icon('heroicon-o-document-text')
                ->iconColor('success')
                ->send();

            //code...
        } catch (\Throwable $th) {
            LogController::log(Auth::user()->id, 'excepcion', $th->getMessage());
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