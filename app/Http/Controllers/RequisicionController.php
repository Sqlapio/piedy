<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Gasto;
use App\Models\TasaBcv;
use App\Models\Producto;
use App\Models\Servicio;
use App\Models\Inventario;
use App\Models\Requisicion;
use Illuminate\Http\Request;
use App\Models\AsignarProducto;
use App\Models\DetalleAsignacion;
use App\Models\DetalleRequisicion;
use App\Models\InventarioSucursal;
use App\Models\RecepcionInventario;
use Illuminate\Support\Facades\Log;
use App\Models\MovimientoInventario;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;
use App\Models\MovimientoInventarioSucursal;

class RequisicionController extends Controller
{
    static function crearRequisicion($data)
    {
        try {

            $requisicion = Requisicion::create([
                'codigo'        => $data['codigo'],
                'sucursal_id'   => Auth::user()->sucursal_id,
                'user_id'       => Auth::user()->id,
                'fecha'         => now()->format('d-m-Y'),
            ]);
    
            for ($i = 0; $i < count($data['productos']); $i++) {

                    $info_producto = Producto::where('id', $data['productos'][$i]['producto_id'])->first();
                    DetalleRequisicion::create([
                        'codigo'         => $data['codigo'],
                        'requisicion_id' => $requisicion->id,
                        'producto_id'    => $data['productos'][$i]['producto_id'],
                        'sucursal_id'    => Auth::user()->sucursal_id,
                        'cantidad'       => $data['productos'][$i]['cantidad'],
                        'uso'            => $info_producto->uso,
                        'costo'          => $info_producto->costo,
                        'observacion'    => $data['productos'][$i]['observacion'],
                        'contenido'      => $info_producto->contenido_neto . '' . $info_producto->unidad,
                        'sub_total'      => $data['productos'][$i]['cantidad'] * $info_producto->costo,
                    ]);
                    
            }

            return true;
            
        } catch (\Throwable $th) {
            LogController::log(Auth::user()->id, 'excepcion-RequisicionController(crearRequisicion)', $th->getMessage(), $response = null);
            Notification::make()
                ->title('NOTIFICACIÓN')
                ->icon('heroicon-c-x-circle')
                ->color('danger')
                ->iconColor('danger')
                ->body($th->getMessage())
                ->send();
        }
    }

    static function detalleRequisicion($codigo, $sucursal_id)
    {
        try {

            $detalle = Requisicion::where('codigo', $codigo)->where('sucursal_id', $sucursal_id)->with('sucursal')->first();
            $responsable = User::where('id', $detalle->user_id)->first()->name;
            return view('detalle-requisicion', compact('codigo', 'sucursal_id', 'detalle', 'responsable'));
        } catch (\Throwable $th) {
            // dd($th);
            LogController::log(1, 'excepcion(detalleRequisicion Link externo)', $th->getMessage(), $response = null);
        }
    }

    static function updateDetalleRequisicion($data)
    {
        
        try {

            //Validamos si los productos asociados pertenecen a la requisicion seleccionada
            $requisicion = Requisicion::where('codigo', $data['codigo'])->first();
            
            if (isset($requisicion)) {

                for ($i = 0; $i < count($data['productos']); $i++) {
                    
                    //El producto debe tener costo asignado
                    $info_producto = Producto::where('id', $data['productos'][$i]['producto_id'])->first();
                    if($info_producto->costo == null) {
                        throw new Exception("El producto " . ProductoController::get_dscripcion($data['productos'][$i]['producto_id']) . "  debe tener costo asociado, por favor verifique y actualize la informacion", 400);
                        
                    }
                    
                    //El producto no puede estar duplicado
                    $existe_producto = DetalleRequisicion::where('codigo', $data['codigo'])->where('producto_id', $data['productos'][$i]['producto_id'])->first();
                    if (isset($existe_producto)) {
                        throw new Exception("El producto " . ProductoController::get_dscripcion($data['productos'][$i]['producto_id']) . " ya existe, por favor verifique y elija uno diferente", 400);
                        
                    }
                    // $info_producto_inventario = Inventario::where('producto_id', $data['productos'][$i]['producto_id'])->first();
                    DetalleRequisicion::create([
                        'codigo'         => $data['codigo'],
                        'requisicion_id' => $requisicion->id,
                        'producto_id'    => $data['productos'][$i]['producto_id'],
                        'sucursal_id'    => $requisicion->sucursal_id,
                        'cantidad'       => $data['productos'][$i]['cantidad'],
                        'uso'            => $info_producto->uso,
                        'costo'          => $info_producto->costo,
                        'observacion'    => $data['productos'][$i]['observacion'],
                        'contenido'      => $info_producto->contenido_neto.''.$info_producto->unidad,
                        'sub_total'      => $data['productos'][$i]['cantidad'] * $info_producto->costo,

                    ]);  
                }

                return true;

            }else {
                Notification::make()
                ->title('NOTIFICACIÓN')
                ->icon('heroicon-c-x-circle')
                ->color('danger')
                ->iconColor('danger')
                ->body('El codigo de la requisicion no existe, por favor vuelva a intentarlo')
                ->send();
            }
            
        } catch (\Throwable $th) {
            LogController::log(1, 'excepcion(detalleRequisicion Link externo)', $th->getMessage(), $response = null);
            Notification::make()
                ->title('NOTIFICACIÓN')
                ->icon('heroicon-c-x-circle')
                ->color('danger')
                ->iconColor('danger')
                ->body($th->getMessage())
                ->send();
        }
    }

    static function enviarRequisicion($records)
    {
        // dd($records);
        /**
         * NOTA:
         * Para poder enviar los producto de la requisicion, deben cumplir las siguientes restricciones
         * 1- el producto debe estar registrado en el almacen
         * 2- el producto debe tener existencia mayor a la cantidad solicitada en la requisicion
         */
        try {
            
            $records = json_decode($records, true);

            $total_requisicion = 0;

            /**
             * Este FOR se encarga de separar los productos que estan aptos para ser enviados a la sucursal
             * de los que no cumplen ccon requisitos del sistema
             */
            
            for ($i = 0; $i < count($records); $i++) {
                
                //Validamos que el producto este registrado en el almacen
                $producto_inventario = Inventario::where('producto_id', $records[$i]['producto_id'])->first();
                if (!isset($producto_inventario)) {
                    $rollback = DetalleRequisicion::where('codigo', $records[$i]['codigo'])->get();
                    foreach ($rollback as $item) {
                        $item->status_id = 5;
                        $item->sub_total = 0.00;
                        $item->estado = 'en-revision';
                        $item->save();
                    }
                    throw new Exception("El producto " . ProductoController::get_dscripcion($records[$i]['producto_id']) . " no se encuentra en el inventario. Por favor comuniquese con el Administrador", 401);
                }

                //Validamos que el producto tenga existencia suficiente
                if ($producto_inventario->cantidad <= 0) {
                    $rollback = DetalleRequisicion::where('codigo', $records[$i]['codigo'])->get();
                    foreach ($rollback as $item) {
                        $item->status_id = 5;
                        $item->sub_total = 0.00;
                        $item->estado = 'en-revision';
                        $item->save();
                    }
                    throw new Exception("El producto " . ProductoController::get_dscripcion($records[$i]['producto_id']) . " esta en 0. Por favor comuniquese con el Administrador", 401);
                }

                //Validamos que la cantidad solicitada no supere la existencia del producto
                if ($records[$i]['cantidad'] > $producto_inventario->cantidad) {
                    $rollback = DetalleRequisicion::where('codigo', $records[$i]['codigo'])->get();
                    foreach ($rollback as $item) {
                        $item->status_id = 5;
                        $item->sub_total = 0.00;
                        $item->estado = 'en-revision';
                        $item->save();
                    }
                    throw new Exception("El producto " . ProductoController::get_dscripcion($records[$i]['producto_id']) . " no tiene suficiente existencia. Por favor comuniquese con el Administrador", 401);
                }

                //Validamos que la cantidad solicitada sea mayor a 0
                if ($records[$i]['cantidad'] <= 0) {
                    $rollback = DetalleRequisicion::where('codigo', $records[$i]['codigo'])->get();
                    foreach ($rollback as $item) {
                        $item->status_id = 5;
                        $item->sub_total = 0.00;
                        $item->estado = 'en-revision';
                        $item->save();
                    }
                    throw new Exception("El producto " . ProductoController::get_dscripcion($records[$i]['producto_id']) . " esta en 0. Por favor comuniquese con el Administrador", 401);
                }

                //Despues de validar creamos el producto en la tabla de recepcion de inventario
                $recepcion = new RecepcionInventario();
                $recepcion->inventario_id   = $producto_inventario->id;
                $recepcion->producto_id     = $records[$i]['producto_id'];
                $recepcion->cantidad        = $records[$i]['cantidad'];
                $recepcion->uso             = $producto_inventario->uso;
                $recepcion->responsable     = Auth::user()->name;
                $recepcion->sucursal_id     = $records[$i]['sucursal_id'];
                $recepcion->save();

                SalidaInventarioController::crear_salida($recepcion->inventario_id, $records[$i]['sucursal_id'], $records[$i]['cantidad'], 'envio-sucursal');

                //Actualizacmos el estatus del producto en la requisicion
                DetalleRequisicion::where('codigo', $records[$i]['codigo'])
                ->where('producto_id', $records[$i]['producto_id'])
                ->update([
                    'status_id' => 6,
                    'estado'    => 'entregado',
                ]);

                //Realizamos la resta del inventario general
                if ($recepcion->save()) {
                    $restaExistencia = $producto_inventario->cantidad - $records[$i]['cantidad'];
                    $producto_inventario->cantidad = $restaExistencia;
                    $producto_inventario->save();

                    //Calculo del porcentaje de exitencia minima
                    $porcentaje = ($restaExistencia * 20) / 100;

                    if ($restaExistencia <= Producto::where('id', $records[$i]['producto_id'])->first()->min) {
                        $notificacion = NotificacionesController::notificacion_exitencia_minima($restaExistencia, $records[$i]['producto_id'], $producto_inventario->almacen->nombre);
                    }

                }
                
                //Calculamos el total de la requisicion
                $total_requisicion += $records[$i]['sub_total'];
                
            }

            //Actualizamos el satus de las requisicion
            $requisicion = Requisicion::where('codigo', $records[0]['codigo'])->first();
            $requisicion->status_id = 6;
            $requisicion->total_usd = $requisicion->total_usd + $total_requisicion;
            $requisicion->save();

            //creamos el gasto asociado a la sucursal que envio la requisicion
            Gasto::create([
                'sucursal_id'           => $records[0]['sucursal_id'],
                'descripcion'           => 'Requisicion de inventario',
                'forma_pago'            => 'dolares',
                'monto_usd'             => $requisicion->total_usd,
                'monto_bsd'             => 0.00,
                'fecha_factura'         => Requisicion::where('codigo', $records[0]['codigo'])->first()->fecha,
                'numero_factura_gasto'  => $records[0]['codigo'],
                'proveedor_id'          => 000,
                'metodo_pago'           => 1,
                'tasa_bcv'              => TasaBcv::where('id', 1)->first()->tasa,
                'responsable'           => Auth::user()->name,
                'conversion_a_usd'      => $requisicion->total_usd,
            ]);

            return true;
            
        } catch (\Throwable $th) {
            LogController::log(1, 'excepcion-RequisicionController(enviarRequisicion)', $th->getMessage(), $response = null);
            Notification::make()
                ->title('NOTIFICACIÓN')
                ->icon('heroicon-c-x-circle')
                ->color('danger')
                ->iconColor('danger')
                ->body($th->getMessage())
                ->persistent()
                ->send();
        }
    }

    static function auditoria($records)
    {
        try {

            /**
             * Ordenar los registros por fecha de creacion de menor a mayor
             */
            $requisiciones = $records->sortBy('created_at')->toArray();
            $posicion = count($requisiciones) - 1;
            $date_start = Carbon::parse($requisiciones[$posicion]['created_at'])->format('Y-m-d');
            
            /**
             * Agrupar los producto por requisicion
             * @return Array $requisiciones
             */
            $requisiciones = array_map(function ($requisicion) {
                return [
                    'codigo' => $requisicion['codigo'],
                    'fecha'  => $requisicion['created_at'],
                ];
            }, $requisiciones);
            //---------------------------------------------------------------------------------------------------------------------------------------------------------
            // Log::info($requisiciones);
            /**
             * Buscamos los productos de la requisicion en la tabla de detalle_requisicions y los agrupamos por porducto_id
             */
            $productos_array = [];
            for ($i = 0; $i < count($requisiciones); $i++) {
                $detalleRequisiciones = DetalleRequisicion::where('codigo', $requisiciones[$i]['codigo'])->groupBy('producto_id')->get('producto_id')->toArray();

                //Tomamos el producto_id y lo agregamos al productos_array si existe sino no lo agregamos
                foreach ($detalleRequisiciones as $detalleRequisicion) {
                    if (!in_array($detalleRequisicion['producto_id'], $productos_array)) {
                        array_push($productos_array, $detalleRequisicion['producto_id']);
                    }
                }
            }
            //---------------------------------------------------------------------------------------------------------------------------------------------------------

            /**
             * Bucamos la existencia de cada producto en la tabla de inventario_sucursals
             */
            $inventario_sucursals = [];
            for ($i = 0; $i < count($productos_array); $i++) {
                $array = InventarioSucursal::where('producto_id', $productos_array[$i])->with('producto')->first();
                if (isset($array)) {
                    array_push($inventario_sucursals, $array->toArray());
                }
            }

            $map_productos = array_map(function ($inventario_sucursals) {
                return [
                    'producto_id' => $inventario_sucursals['producto_id'],
                    'cantidad'  => $inventario_sucursals['cantidad'],
                    'producto'    => $inventario_sucursals['producto']['descripcion'],
                ];
            }, $inventario_sucursals);
            //---------------------------------------------------------------------------------------------------------------------------------------------------------

            /**
             * Porductos en el movimiento de inventario
             */
            $movimientos = [];
            for ($i = 0; $i < count($productos_array); $i++) {
                $array_mov = MovimientoInventarioSucursal::select('producto_id', 'cantidad', 'tipo_movimiento', 'consumo')
                ->whereBetween('created_at', [$date_start . ' 00:00:00', Carbon::now()])
                ->where('producto_id', $productos_array[$i])
                ->where('tipo_movimiento', 'salida')
                ->with('producto')
                ->first();

                if (isset($array_mov)) {
                    array_push($movimientos, $array_mov->toArray());
                }
            }

            $map_productos_mov = array_map(function ($movimientos) {
                return [
                    'producto_id'       => $movimientos['producto_id'],
                    'cantidad'          => $movimientos['cantidad'],
                    'producto'          => $movimientos['producto']['descripcion'],
                    'precio_venta'      => $movimientos['producto']['costo'],
                    'tipo_movimiento'   => $movimientos['tipo_movimiento'],
                    'consumo'           => $movimientos['consumo'],
                ];
            }, $movimientos);

            //---------------------------------------------------------------------------------------------------------------------------------------------------------

            /**
             * Buscamos los servicios de la requisicion en la tabla de detalle_requisicions y los agrupamos por porducto_id
             */
            $servicios = Servicio::select('id', 'descripcion')->get()->toArray();
            $ser = [];
            //contamos los servicios en la tabla de detalle de asigancion
            for ($i = 0; $i < count($servicios); $i++) {
                $count = DetalleAsignacion::whereBetween('created_at', [$date_start .' 00:00:00', Carbon::now()])->where('servicio_id', $servicios[$i]['id'])->count();
                if($count != 0){
                    array_push($ser, [
                        'descripcion' => $servicios[$i]['descripcion'], 
                        'cantidad' => $count
                    ]); //array_push($servicios, $servicios[$i]['id']);
                }
            }
            //---------------------------------------------------------------------------------------------------------------------------------------------------------
            dd($date_start, Carbon::now(), $map_productos, $ser);
            
            
        } catch (\Throwable $th) {
            dd($th);
            LogController::log(1, 'excepcion(detalleRequisicion Link externo)', $th->getMessage(), $response = null);
        }
    }
}