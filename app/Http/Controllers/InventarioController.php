<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Producto;
use App\Models\Sucursal;
use App\Models\Consumible;
use App\Models\Inventario;
use Illuminate\Http\Request;
use App\Models\SalidaInventario;
use App\Models\EntradaInventario;
use App\Models\InventarioSucursal;
use App\Models\RecepcionInventario;
use App\Models\MovimientoInventario;
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

            if ($inventario->save()) {

                //Escribimos en la tabla de entradas
                //la cantidad que fue movida del inventario principal al
                //inventario de la sucursal
                EntradaInventarioController::crear_entrada($inventario_id, $cantidad);

                Notification::make()
                    ->title('La Reposicion se realizo con éxito.')
                    ->color('success')
                    ->icon('heroicon-o-document-text')
                    ->iconColor('success')
                    ->send();
            }
        } catch (\Throwable $th) {
            LogController::log(Auth::user()->id, 'excepcion-InventarioController(reposicion)', $th->getMessage(), $response = null);
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

            if ($cantidad <= 0) {
                throw new Exception("No puede realizar el movimiento ya que el inventario esta en 0. Por favor comuniquese con el Administrador", 401);
            }

            $inventario = Inventario::where('producto_id', $producto->id)->first();
            //Cantidad en inventario general
            if ($cantidad > $inventario->cantidad) {
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

            if ($recepcion->save()) {
                $restaExistencia = Inventario::where('producto_id', $producto->id)->first();
                $restaExistencia->update([
                    'cantidad' => $restaExistencia->cantidad - $cantidad
                ]);

                //Calculo del porcentaje de exitencia minima
                $porcentaje = ($restaExistencia->cantidad * 20) / 100;

                if ($restaExistencia->cantidad <= $inventario->min) {
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
            LogController::log(Auth::user()->id, 'excepcion-InventarioController(asignacion_sucursal)', $th->getMessage(), $response = null);
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
        //Como debo crear una tabla donde pueda almacenar las entradas, las salidas y las ventas de los productos almacenados en mi inventario?

        try {

            //Validamos si el producto ya esxite en el almacen, para evitar productos duplicados
            $exite = Inventario::where('producto_id', $producto_id)->where('almacen_id', $almacen_id)->first();
            if (isset($exite)) {
                Throw new Exception("El producto ya exite en el almacen", 401);
            }

            $info_producto = Producto::where('id', $producto_id)->first();

            //Si el producto no exite en la sucursal
            //creamos un nuevo inventario
            $inventario = new Inventario();
            $inventario->producto_id = $producto_id;
            $inventario->almacen_id  = $almacen_id;
            $inventario->cantidad    = $cantidad;
            $inventario->unidad      = $info_producto->unidad;
            $inventario->uso         = $uso;
            $inventario->min         = $min;
            $inventario->responsable = Auth::user()->name;
            $inventario->save();

            //creamos la entrada de inventario
            $entrada = new MovimientoInventario();
            $entrada->codigo            = 'PEI-' . random_int(11111, 99999);
            $entrada->almacen_id        = $almacen_id;
            $entrada->producto_id       = $producto_id;
            $entrada->cantidad          = $cantidad;
            $entrada->contenido_neto    = $info_producto->contenido_neto;
            $entrada->unidad            = $info_producto->unidad;
            $entrada->tipo_movimiento   = 'entrada';
            $entrada->responsable       = Auth::user()->name;
            $entrada->save();

            if ($inventario->save() && $entrada->save()) {
                Notification::make()
                    ->title('El carga directa se realizo con éxito.')
                    ->color('success')
                    ->icon('heroicon-o-document-text')
                    ->iconColor('success')
                    ->send();
            }
            
        } catch (\Throwable $th) {
            LogController::log(Auth::user()->id, 'excepcion-InventarioController(entrada_directa)', $th->getMessage(), $response = null);
            Notification::make()
                ->title('NOTIFICACIÓN: InventarioController(entrada_directa)')
                ->icon('heroicon-c-x-circle')
                ->color('danger')
                ->iconColor('danger')
                ->body($th->getMessage())
                ->send();
        }
    }

    public static function mover_a_consumible($producto_id, $contenido_neto, $unidad, $can_srv, $tipo_uso)
    {

        try {

            $consumible = new Consumible();
            $consumible->producto_id = $producto_id;
            $consumible->contenido_neto = $contenido_neto;
            $consumible->unidad = $unidad;
            $consumible->can_srv = $can_srv;
            $consumible->tipo_uso = $tipo_uso;
            $consumible->contenido_neto = $contenido_neto;
            $consumible->uso = round($contenido_neto / $can_srv);
            $consumible->save();

            
        } catch (\Throwable $th) {
            LogController::log(Auth::user()->id, 'excepcion-InventarioController(mover_a_consumible)', $th->getMessage(), $response = null);
            Notification::make()
                ->title('NOTIFICACIÓN: InventarioController(mover_a_consumible)')
                ->icon('heroicon-c-x-circle')
                ->color('danger')
                ->iconColor('danger')
                ->body($th->getMessage())
                ->send();
        }
    }
}