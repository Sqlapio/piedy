<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Producto;
use App\Models\Inventario;
use Illuminate\Http\Request;
use App\Models\AsignarProducto;
use App\Models\DetalleAsignacion;
use App\Models\InventarioSucursal;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;
use App\Models\MovimientoInventarioSucursal;

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
            LogController::log(Auth::user()->id, 'excepcion-InventarioSucursalController(cargar_movimiento)', $th->getMessage(), $response = null);
            Notification::make()
                ->title('NOTIFICACIÓN')
                ->icon('heroicon-c-x-circle')
                ->color('danger')
                ->iconColor('danger')
                ->body($th->getMessage())
                ->send();
        }

    }

    public static function asignar_producto($data,$producto_id)
    {
        // dd($data, $producto_id);
        try {

            $existencia_actual = InventarioSucursal::where('sucursal_id', Auth::user()->sucursal_id)
            ->where('producto_id', $producto_id)
            ->first()
            ->cantidad;


            //Validacion para saber si tenemos existencia para asignar el producto
            if($data['cantidad'] > $existencia_actual){
                throw new Exception("No hay suficiente existencia para realizar la asigancion", 401);
            }

            if($data['feedback'] == 'tienda'){

                //Buscamos el ultimo registro del producto
                $ultimo_registro = AsignarProducto::where('producto_id', $producto_id)
                ->where('asignado_a_sucursal', $data['sucursal'])
                ->orderBy('created_at', 'desc')
                ->first();

                if($ultimo_registro != null) {
                    //Calculamos cuando dias han transcurrido desde la ultima asignacion del producto
                    $diferencia = now()->diffInDays($ultimo_registro->created_at);

                    $producto_asignado = new AsignarProducto();
                    $producto_asignado->producto_id = $producto_id;
                    $producto_asignado->cantidad = $data['cantidad'];
                    $producto_asignado->fecha_entrega = now()->format('d-m-Y');
                    $producto_asignado->responsable = Auth::user()->name;
                    $producto_asignado->sucursal_id = Auth::user()->sucursal_id;
                    $producto_asignado->servicios_facturados = $diferencia;
                    $producto_asignado->asignado_a_sucursal =  $data['sucursal'];
                    $producto_asignado->asignacion = $data['feedback'];
                    $producto_asignado->save();

                    if ($producto_asignado->save()) {
                        $existencia = InventarioSucursal::where('sucursal_id', Auth::user()->sucursal_id)
                            ->where('producto_id', $producto_id)
                            ->first();
                        $existencia->cantidad = $existencia->cantidad - $data['cantidad'];
                        $existencia->save();
                    }

                }else{

                    $producto_asignado = new AsignarProducto();
                    $producto_asignado->producto_id = $producto_id;
                    $producto_asignado->cantidad = $data['cantidad'];
                    $producto_asignado->fecha_entrega = now()->format('d-m-Y');
                    $producto_asignado->responsable = Auth::user()->name;
                    $producto_asignado->sucursal_id = Auth::user()->sucursal_id;
                    $producto_asignado->asignado_a_sucursal =  $data['sucursal'];
                    $producto_asignado->asignacion = $data['feedback'];
                    $producto_asignado->save();

                    if ($producto_asignado->save()) {
                        $existencia = InventarioSucursal::where('sucursal_id', Auth::user()->sucursal_id)
                            ->where('producto_id', $producto_id)
                            ->first();
                        $existencia->cantidad = $existencia->cantidad - $data['cantidad'];
                        $existencia->save();
                    }
                    
                }

                //creamos la salida en la tabla de movimiento_inventario_sucursal
                MovimientoInventarioSucursalController::crear_movimiento_inventario_sucursal($producto_id, $data['cantidad'], 'salida', 'tienda');
                
            }

            if ($data['feedback'] == 'tecnico') {

                //Buscamos el ultimo registro del usuario
                $ultimo_registro = AsignarProducto::where('user_id', $data['user_id'])
                    ->where('producto_id', $producto_id)
                    ->orderBy('created_at', 'desc')
                    ->first();

                if ($ultimo_registro != null) {
                    //Calculamos el total de servicios realizados entre la ultima fecha de entrega y la fecha actual
                    $total_servicios = DetalleAsignacion::where('empleado_id', $data['user_id'])
                        ->whereBetween('created_at', [$ultimo_registro->created_at->format('Y-m-d') . ' 00:00:00.000', now()->format('Y-m-d') . ' 23:59:59.000'])
                        ->where('status', 2)
                        ->count();


                    $producto_asignado = new AsignarProducto();
                    $producto_asignado->user_id = $data['user_id'];
                    $producto_asignado->producto_id = $producto_id;
                    $producto_asignado->cantidad = $data['cantidad'];
                    $producto_asignado->fecha_entrega = now()->format('d-m-Y');
                    $producto_asignado->responsable = Auth::user()->name;
                    $producto_asignado->sucursal_id = Auth::user()->sucursal_id;
                    $producto_asignado->servicios_facturados = $total_servicios;
                    $producto_asignado->asignacion = $data['feedback'];
                    $producto_asignado->save();

                    if ($producto_asignado->save()) {
                        $existencia = InventarioSucursal::where('sucursal_id', Auth::user()->sucursal_id)
                            ->where('producto_id', $producto_id)
                            ->first();
                        $existencia->cantidad = $existencia->cantidad - $data['cantidad'];
                        $existencia->save();
                    }
                    
                }else {

                    $producto_asignado = new AsignarProducto();
                    $producto_asignado->user_id = $data['user_id'];
                    $producto_asignado->producto_id = $producto_id;
                    $producto_asignado->cantidad = $data['cantidad'];
                    $producto_asignado->fecha_entrega = now()->format('d-m-Y');
                    $producto_asignado->responsable = Auth::user()->name;
                    $producto_asignado->sucursal_id = Auth::user()->sucursal_id;
                    $producto_asignado->asignacion = $data['feedback'];
                    $producto_asignado->save();

                    if ($producto_asignado->save()) {
                        $existencia = InventarioSucursal::where('sucursal_id', Auth::user()->sucursal_id)
                            ->where('producto_id', $producto_id)
                            ->first();
                        $existencia->cantidad = $existencia->cantidad - $data['cantidad'];
                        $existencia->save();
                    } 
                }

                //creamos la salida en la tabla de movimiento_inventario_sucursal
                MovimientoInventarioSucursalController::crear_movimiento_inventario_sucursal($producto_id, $data['cantidad'], 'salida', 'tecnico');

            }

            Notification::make()
                ->title('El producto fue asignado con exito con éxito.')
                ->color('success')
                ->icon('heroicon-o-document-text')
                ->iconColor('success')
                ->send();

            //code...
        } catch (\Throwable $th) {
            LogController::log(Auth::user()->id, 'excepcion-InventarioSucursalController(asignar_producto)', $th->getMessage(), $response = null);
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