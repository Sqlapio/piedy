<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Producto;
use App\Models\Sucursal;
use App\Models\Inventario;
use Illuminate\Http\Request;
use App\Models\SalidaInventario;
use App\Models\EntradaInventario;
use App\Models\InventarioSucursal;
use App\Models\RecepcionInventario;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;

class InventarioController extends Controller
{
    public static function reposicion($inventario_id, $cantidad)
    {

        try {

            $producto = Producto::where('id', Inventario::find($inventario_id)->producto_id)->first();

            $existencia = Inventario::where('producto_id', $producto->id)->first()->cantidad;

            $inventario = Inventario::find($inventario_id);
            $inventario->cantidad += $cantidad;
            $inventario->save();

            if($inventario->save()){

                //Escribimos en la tabla de entradas
                //la cantidad que fue movida del inventario principal al
                //inventario de la sucursal
                EntradaInventarioController::crear_entrada($inventario_id, $cantidad, 'reposicion');

                Notification::make()
                    ->title('La Reposicion se realizo con éxito.')
                    ->color('success')
                    ->icon('heroicon-o-document-text')
                    ->iconColor('success')
                    ->send();
            }

        } catch (\Throwable $th) {
            LogController::log(Auth::user()->id, 'excepcion', $th->getMessage(), $response = null);
            Notification::make()
                ->title('NOTIFICACIÓN: InventarioController(reposicion)')
                ->icon('heroicon-c-x-circle')
                ->color('danger')
                ->iconColor('danger')
                ->body($th->getMessage())
                ->send();
        }
    }

    public static function asignacion_sucursal($inventario_id, $sucursal_id, $cantidad)
    {
        try {

            //Informacion del producto
            $producto = Producto::where('id', Inventario::find($inventario_id)->producto_id)->first();

            if($cantidad <= 0)
            {
                throw new Exception("No puede realizar el movimiento ya que el inventario esta en 0. Por favor comuniquese con el Administrador", 401);
            }

            $inventario = Inventario::where('producto_id', $producto->id)->first();
            //Cantidad en inventario general
            if($cantidad > $inventario->cantidad)
            {
                throw new Exception("No puede realizar el movimiento ya que la cantidad solicitada es mayor a la existencia total. Por favor comuniquese con el Administrador", 401);
            }

            //Si el producto no exite en la sucursal
            //creamos un nuevo inventario
            $recepcion = new RecepcionInventario();
            $recepcion->inventario_id = $inventario_id;
            $recepcion->producto_id = $producto->id;
            $recepcion->cantidad    = $cantidad;
            $recepcion->uso         = $producto->uso;
            $recepcion->responsable = Auth::user()->name;
            $recepcion->sucursal_id = Auth::user()->sucursal_id;
            $recepcion->save();
            
            SalidaInventarioController::crear_salida($inventario_id, $sucursal_id, $cantidad, 'envio-sucursal');
            
            if($recepcion->save()) {
                $restaExistencia = Inventario::where('producto_id', $producto->id)->first();
                $restaExistencia->update([
                    'cantidad' => $restaExistencia->cantidad - $cantidad
                ]);

                //Calculo del porcentaje de exitencia minima
                $porcentaje = ($restaExistencia->cantidad * 20) / 100;

                if($restaExistencia->cantidad <= $inventario->min)
                {
                    $notificacion = NotificacionesController::notificacion_exitencia_minima($restaExistencia->cantidad, $producto->id, $inventario->almacen->nombre);
                }
                
                Notification::make()
                    ->title('El Movimiento se realizo con éxito.')
                    ->color('success')
                    ->icon('heroicon-o-document-text')
                    ->iconColor('success')
                    ->send();
            }


        } catch (\Throwable $th) {
            LogController::log(Auth::user()->id, 'excepcion', $th->getMessage(), $response = null);
            Notification::make()
                ->title('NOTIFICACIÓN: InventarioController(asignacion_sucursal)')
                ->icon('heroicon-c-x-circle')
                ->color('danger')
                ->iconColor('danger')
                ->body($th->getMessage())
                ->send();
        }

    }

    public static function entrada_directa($producto_id, $uso, $min, $almacen_id, $cantidad)
    {

        try {

            //Si el producto no exite en la sucursal
            //creamos un nuevo inventario
            $inventario = new Inventario();
            $inventario->producto_id = $producto_id;
            $inventario->almacen_id  = $almacen_id;
            $inventario->cantidad    = $cantidad;
            $inventario->uso         = $uso;
            $inventario->min         = $min;
            $inventario->responsable = Auth::user()->name;
            $inventario->save();


            //Creamos la entrada en la tabla de inventario
            $entrada = new EntradaInventario();
            $entrada->cod_movimiento    = 'Psi-'.random_int(11111, 99999);
            $entrada->almacen_id        = $almacen_id;
            $entrada->producto_id       = $producto_id;
            $entrada->cantidad          = $cantidad;
            $entrada->tipo_movimiento   = 'primera carga';
            $entrada->responsable       = Auth::user()->name;
            $entrada->save();

            if($inventario->save() && $entrada->save()) {
                Notification::make()
                    ->title('El carga directa se realizo con éxito.')
                    ->color('success')
                    ->icon('heroicon-o-document-text')
                    ->iconColor('success')
                    ->send();
            }


        } catch (\Throwable $th) {
            LogController::log(Auth::user()->id, 'excepcion', $th->getMessage(), $response = null);
            Notification::make()
                ->title('NOTIFICACIÓN: InventarioController(entrada_directa)')
                ->icon('heroicon-c-x-circle')
                ->color('danger')
                ->iconColor('danger')
                ->body($th->getMessage())
                ->send();
        }

    }
}