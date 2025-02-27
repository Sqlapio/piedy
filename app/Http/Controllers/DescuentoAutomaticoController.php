<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Producto;
use App\Models\Disponible;
use Illuminate\Http\Request;
use App\Models\ProductoServicio;
use App\Models\InventarioSucursal;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;
use App\Http\Controllers\MovimientoInventarioSucursalController;

class DescuentoAutomaticoController extends Controller
{
    
    public static function descuento_automatico($cod_asignacion) {
        /**
         * DESCUENTO AUTOMATICO
         * --------------------
         * 
         * 1.- Buscamos el servicio en la tabla de disponibles
         * 2.- Entramos en la tabla producto_servicios para obtener el id del producto a descontar
         * 3.- Hacemos el descuento en la existencia en el inventario de la sucursal
         * 4.- Escribimos el movimiento en la tabla de movimiento_invenatrio_sucursals
         */

        try {
            
            $servicio_id_facturado = Disponible::where('cod_asignacion', $cod_asignacion)
                ->where('status', 'facturado')
                ->get('servicio_id')
                ->toArray();

            if (isset($servicio_id_facturado)) {
                $producto_id = ProductoServicio::where('servicio_id', $servicio_id_facturado[0]['servicio_id'])->get()->toArray();

                //Recorremos el array de los productos que seran descontados de forma automatica
                for ($i = 0; $i < count($producto_id); $i++) {

                    $cant_desc_auto = Producto::where('id', $producto_id[$i]['producto_id'])->first()->cant_desc_auto;

                    $existencia = InventarioSucursal::where('producto_id', $producto_id[$i]['producto_id'])
                    ->where('sucursal_id', Auth::user()->sucursal_id)
                    ->with('producto')
                    ->first();

                    if (isset($existencia)) {

                        //existencia cero
                        if ($existencia->cantidad == 0) {
                            throw new Exception('El producto ' . $existencia->producto->descripcion . ' tiene exitencia cero(0)', 401);
                        
                            //existencia mayor a cero
                        } else {
                            $existencia->cantidad = $existencia->cantidad - $cant_desc_auto;
                            $existencia->save();

                            //creamos la salida en la tabla de movimiento_inventario_sucursal
                            MovimientoInventarioSucursalController::crear_movimiento_inventario_sucursal($producto_id[$i]['producto_id'], $cant_desc_auto, 'salida', 'descuento-automatico');
                            
                        }
                    } else {
                        throw new Exception('El producto ' . $existencia->producto->descripcion . ' no se encuentra en el inventario', 401);
                        
                    }
                    
                }
                
            }else{
                throw new Exception('El servicio no se encuentra facturado', 401);
                
            }
            
            return true;
            
        } catch (Exception $e) {
            Notification::make()
            ->title('Notificacion')
            ->color('danger')
            ->icon('heroicon-o-shield-check')
            ->iconColor('danger')
            ->body($e->getMessage())
            ->send();
            
        }
        
    }

}