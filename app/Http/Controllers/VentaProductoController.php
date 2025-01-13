<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\TasaBcv;
use App\Models\Comision;
use App\Models\Producto;
use App\Models\MetodoPago;
use App\Models\CarProducto;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\VentaProducto;
use App\Models\InventarioSucursal;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;
use App\Http\Controllers\NotificacionesController;

class VentaProductoController extends Controller
{
    static function facturarProducto_usd($metodoUsd, $montoUsd, $cliente_id, $empleado_id, $ref_zelle)
    {
        // dd($metodoUsd, $montoUsd, $cliente_id, $empleado_id);
        try {

            $codigoAsignacion = 'Pca-' . random_int(11111111, 99999999);

            $total_compra = CarProducto::sum('total_compra_usd');

            if ($montoUsd == $total_compra) {

                /**
                 * Comisiones de venta
                 */
                if ($empleado_id == null) {
                    //La venta fue asignada al gerente
                    //Comision Gerente 15%
                    $porComGte = Comision::where('aplicacion', 'producto')
                        ->where('beneficiario', 'gerente')
                        ->where('accion', 'directa')
                        ->where('status', '1')->first()
                        ->porcentaje;
                } else {
                    //La venta fue asignada a un quiropedista o una manicurista
                    //Se calculan ambas comisiones tanto para el gerente como para el empleado
                    //Comision Empleado 10%
                    $porComEmp = Comision::where('aplicacion', 'producto')
                        ->where('beneficiario', 'empleado')
                        ->where('accion', 'directa')
                        ->where('status', '1')->first()
                        ->porcentaje;

                    //Comision Empleado 5%
                    $porComGte = Comision::where('aplicacion', 'producto')
                        ->where('beneficiario', 'gerente')
                        ->where('accion', 'indirecta')
                        ->where('status', '1')->first()
                        ->porcentaje;
                }

                $productos = CarProducto::where('sucursal_id', Auth::user()->sucursal_id)->where('status', 1)->get();

                foreach ($productos as $item) {

                    $producto = Producto::where('cod_producto', $item->cod_prod)->first();

                    $venta_producto = new VentaProducto();
                    $venta_producto->cod_asignacion     = $codigoAsignacion;
                    $venta_producto->gerente_id         = Auth::user()->id;
                    $venta_producto->producto_id        = $producto->id;
                    $venta_producto->costo_producto     = $producto->precio_venta;

                    $venta_producto->metodoUsd          = MetodoPago::where('id', $metodoUsd)->first()->descripcion;
                    $venta_producto->montoUsd           = $producto->precio_venta * $item->cantidad;

                    $venta_producto->comision_gerente   = ($porComGte * $producto->precio_venta) / 100;
                    $venta_producto->comision_empleado  = ($empleado_id == null) ? 0.00 : ($porComEmp * $producto->precio_venta) / 100;

                    $venta_producto->fecha_venta        = now()->format('d-m-Y');
                    $venta_producto->cantidad           = $item->cantidad;
                    $venta_producto->total_venta        = $producto->precio_venta * $item->cantidad;
                    $venta_producto->responsable        = Auth::user()->name;
                    $venta_producto->sucursal_id        = Auth::user()->sucursal->id;

                    $venta_producto->cliente_id         = $cliente_id;
                    $venta_producto->empleado_id        = ($empleado_id == null) ? Auth::user()->id : $empleado_id;
                    $venta_producto->ref_zelle          = $ref_zelle;

                    $venta_producto->save();

                    //Descuento la cantidad vendida de la exitencia del producto por sucursal
                    $productoSucursal = InventarioSucursal::where('producto_id', $producto->id)->where('sucursal_id', Auth::user()->sucursal->id)->first();
                    $productoSucursal->cantidad = $productoSucursal->cantidad - $item->cantidad;
                    $productoSucursal->save();

                    if ($productoSucursal->cantidad == 0) {

                        $productoSucursal->accepted_at = null;
                        $productoSucursal->save();
                    }

                    //Notificacion por existencia minima del producto
                    if ($productoSucursal->cantidad <= $producto->existencia_min_sucursal) {

                        $notificacion = NotificacionesController::notificacion_exitencia_minima_sucursal($productoSucursal->cantidad, $producto->id, Auth::user()->sucursal_id);
                        if ($notificacion['success'] == true) {
                            Notification::make()
                                ->title('NOTIFICACIÓN')
                                ->icon('heroicon-o-document-text')
                                ->iconColor('success')
                                ->color('success')
                                ->body($notificacion['message'])
                                ->send();
                        } else {
                            Notification::make()
                                ->title('NOTIFICACIÓN')
                                ->icon('heroicon-o-document-text')
                                ->iconColor('danger')
                                ->color('danger')
                                ->body($notificacion['message'])
                                ->send();
                        }
                    }

                    //Cargamos el movimiento de inventario en su tabla
                    MovimientoInventarioController::registrar_movimiento(
                        $producto->id,
                        $item->cantidad,
                        $venta_producto->sucursal_id,
                        $codigoAsignacion,
                        'Venta',
                    );

                    //Cargamos la venta en su tabla de ventas
                    VentaController::venta_producto(
                        $codigoAsignacion,
                        $venta_producto->total_venta,
                        $venta_producto->metodoUsd,
                        'N/A',
                    );
                }

                return true;
            } else {

                return false;
            }
        } catch (\Throwable $th) {
            dd($th);
            Notification::make()
                ->title('NOTIFICACIÓN')
                ->icon('heroicon-o-document-text')
                ->iconColor('danger')
                ->color('danger')
                ->body($th->getMessage())
                ->send();
        }
    }

    static function facturarProducto_bsd($metodoBsd, $montoBsd, $cliente_id, $empleado_id, $ref_pago_movil, $ref_debito_credito, $nro_tarjeta)
    {
        try {

            $codigoAsignacion = 'Pca-' . random_int(11111111, 99999999);

            $total_compra = CarProducto::sum('total_compra_bsd');

            /**
             * Comisiones de venta
             */
            if ($empleado_id == null) {
                //La venta fue asignada al gerente
                //Comision Gerente 15%
                $porComGte = Comision::where('aplicacion', 'producto')
                    ->where('beneficiario', 'gerente')
                    ->where('accion', 'directa')
                    ->where('status', '1')->first()
                    ->porcentaje;
            } else {
                //La venta fue asignada a un quiropedista o una manicurista
                //Se calculan ambas comisiones tanto para el gerente como para el empleado
                //Comision Empleado 10%
                $porComEmp = Comision::where('aplicacion', 'producto')
                    ->where('beneficiario', 'empleado')
                    ->where('accion', 'directa')
                    ->where('status', '1')->first()
                    ->porcentaje;

                //Comision Empleado 5%
                $porComGte = Comision::where('aplicacion', 'producto')
                    ->where('beneficiario', 'gerente')
                    ->where('accion', 'indirecta')
                    ->where('status', '1')->first()
                    ->porcentaje;
            }

            $productos = CarProducto::where('sucursal_id', Auth::user()->sucursal_id)->where('status', 1)->get();

            foreach ($productos as $item) {
                $producto = Producto::where('cod_producto', $item->cod_prod)->first();

                $venta_producto = new VentaProducto();
                $venta_producto->cod_asignacion     = $codigoAsignacion;
                $venta_producto->gerente_id         = Auth::user()->id;
                $venta_producto->producto_id        = $producto->id;
                $venta_producto->costo_producto     = $producto->precio_venta;

                $venta_producto->metodoBsd          = MetodoPago::where('id', $metodoBsd)->first()->descripcion;;
                $venta_producto->comision_gerente   = ($porComGte * $producto->precio_venta) / 100;
                $venta_producto->comision_empleado  = ($empleado_id == null) ? 0.00 : ($porComEmp * $producto->precio_venta) / 100;

                $venta_producto->fecha_venta        = now()->format('d-m-Y');
                $venta_producto->cantidad           = $item->cantidad;
                $venta_producto->total_venta        = $producto->precio_venta * $item->cantidad;
                $venta_producto->montoBsd           = $venta_producto->total_venta * TasaBcv::all()->first()->tasa;
                $venta_producto->ref_pago_movil     = $ref_pago_movil;
                $venta_producto->ref_debito_credito = $ref_debito_credito;
                $venta_producto->nroTarjeta         = $nro_tarjeta;
                $venta_producto->responsable        = Auth::user()->name;
                $venta_producto->sucursal_id        = Auth::user()->sucursal->id;

                $venta_producto->cliente_id         = $cliente_id;
                $venta_producto->empleado_id        = $empleado_id;
                $venta_producto->save();

                //Descuento la cantidad vendida de la exitencia del producto por sucursal
                $productoSucursal = InventarioSucursal::where('producto_id', $producto->id)->where('sucursal_id', Auth::user()->sucursal->id)->first();
                $productoSucursal->cantidad = $productoSucursal->cantidad - $item->cantidad;
                $productoSucursal->save();

                if ($productoSucursal->cantidad == 0) {
                    $productoSucursal->accepted_at = null;
                    $productoSucursal->save();
                }

                //Notificacion por existencia minima del producto
                if ($productoSucursal->cantidad <= $producto->existencia_min_sucural) {
                    $notificacion = NotificacionesController::notificacion_exitencia_minima_sucursal($productoSucursal->cantidad, $producto->id, Auth::user()->sucursal_id);
                    if ($notificacion['success'] == true) {
                        Notification::make()
                            ->title('NOTIFICACIÓN')
                            ->icon('heroicon-o-document-text')
                            ->iconColor('success')
                            ->color('success')
                            ->body($notificacion['message'])
                            ->send();
                    } else {
                        Notification::make()
                            ->title('NOTIFICACIÓN')
                            ->icon('heroicon-o-document-text')
                            ->iconColor('danger')
                            ->color('danger')
                            ->body($notificacion['message'])
                            ->send();
                    }
                }

                //Cargamos el movimiento de inventario en su tabla
                MovimientoInventarioController::registrar_movimiento(
                    $producto->id,
                    $item->cantidad,
                    $venta_producto->sucursal_id,
                    $codigoAsignacion,
                    'Venta',
                );

                //Cargamos la venta en su tabla de ventas
                VentaController::venta_producto(
                    $codigoAsignacion,
                    $venta_producto->total_venta,
                    'N/A',
                    $venta_producto->metodoBsd,
                );
            }

            if ($venta_producto->save()) {
                return true;
            } else {
                return false;
            }
        } catch (\Throwable $th) {
            Notification::make()
                ->title('NOTIFICACIÓN')
                ->icon('heroicon-o-document-text')
                ->iconColor('danger')
                ->color('danger')
                ->body($th->getMessage())
                ->send();
        }
    }

    static function facturarProducto_multiple($montoUsd, $montoBsd, $metodoUsd, $metodoBsd, $cliente_id, $empleado_id, $ref_zelle, $ref_pago_movil, $ref_debito_credito, $nro_tarjeta)
    {
        try {

            $codigoAsignacion = 'Pca-' . random_int(11111111, 99999999);

            // /**Validacion para no permitir los numero de referencias duplicados */
            // $exite_referencia = VentaProducto::where('referenciaUsd', $referenciaUsd)
            // ->orWhere('referenciaBsd', $referenciaBsd)
            // ->orWhere('referenciaBsd', $referenciaBsd)
            // ->orWhere('referenciaBsd', $referenciaBsd)
            // ->get();
            // if(count($exite_referencia) > 0){
            //     throw new Exception("Numero de referencia duplicado. Por favor vuelva a intentarlo", 401);
            // }

            $total_compra_usd = CarProducto::sum('total_compra_usd');
            // $total_compra_bsd = CarProducto::sum('total_compra_bsd')

            /**
             * Comisiones de venta
             */
            if ($empleado_id == null) {
                //La venta fue asignada al gerente
                //Comision Gerente 15%
                $porComGte = Comision::where('aplicacion', 'producto')
                    ->where('beneficiario', 'gerente')
                    ->where('accion', 'directa')
                    ->where('status', '1')->first()
                    ->porcentaje;
            } else {
                //La venta fue asignada a un quiropedista o una manicurista
                //Se calculan ambas comisiones tanto para el gerente como para el empleado
                //Comision Empleado 10%
                $porComEmp = Comision::where('aplicacion', 'producto')
                    ->where('beneficiario', 'empleado')
                    ->where('accion', 'directa')
                    ->where('status', '1')->first()
                    ->porcentaje;

                //Comision Empleado 5%
                $porComGte = Comision::where('aplicacion', 'producto')
                    ->where('beneficiario', 'gerente')
                    ->where('accion', 'indirecta')
                    ->where('status', '1')->first()
                    ->porcentaje;
            }

            $productos = CarProducto::where('sucursal_id', Auth::user()->sucursal_id)->where('status', 1)->get();

            foreach ($productos as $item) {

                $producto = Producto::where('cod_producto', $item->cod_prod)->first();

                $venta_producto = new VentaProducto();
                $venta_producto->cod_asignacion     = $codigoAsignacion;
                $venta_producto->gerente_id         = Auth::user()->id;
                $venta_producto->producto_id        = $producto->id;
                $venta_producto->costo_producto     = $producto->precio_venta;

                $venta_producto->metodoUsd          = MetodoPago::where('id', $metodoUsd)->first()->descripcion;
                $venta_producto->metodoBsd          = MetodoPago::where('id', $metodoBsd)->first()->descripcion;

                $venta_producto->montoUsd           = $montoUsd;
                $venta_producto->montoBsd           = $montoBsd;

                $venta_producto->comision_gerente   = ($porComGte * $producto->precio_venta) / 100;
                $venta_producto->comision_empleado  = ($empleado_id == null) ? 0.00 : ($porComEmp * $producto->precio_venta) / 100;

                $venta_producto->fecha_venta        = now()->format('d-m-Y');
                $venta_producto->cantidad           = $item->cantidad;
                $venta_producto->total_venta        = $producto->precio_venta * $item->cantidad;

                $venta_producto->ref_pago_movil     = $ref_pago_movil;
                $venta_producto->ref_debito_credito = $ref_debito_credito;
                $venta_producto->nroTarjeta         = $nro_tarjeta;
                $venta_producto->ref_zelle          = $ref_zelle;

                $venta_producto->responsable        = Auth::user()->name;
                $venta_producto->sucursal_id        = Auth::user()->sucursal->id;

                $venta_producto->cliente_id         = $cliente_id;
                $venta_producto->empleado_id        = $empleado_id;
                $venta_producto->save();

                //Descuento la cantidad vendida de la exitencia del producto por sucursal
                $productoSucursal = InventarioSucursal::where('producto_id', $producto->id)->where('sucursal_id', Auth::user()->sucursal->id)->first();
                $productoSucursal->cantidad = $productoSucursal->cantidad - $item->cantidad;
                $productoSucursal->save();

                if ($productoSucursal->cantidad == 0) {
                    $productoSucursal->accepted_at = null;
                    $productoSucursal->save();
                }

                //Notificacion por existencia minima del producto
                if ($productoSucursal->cantidad <= $producto->existencia_min_sucural) {
                    $notificacion = NotificacionesController::notificacion_exitencia_minima_sucursal($productoSucursal->cantidad, $producto->id, Auth::user()->sucursal_id);
                    if ($notificacion['success'] == true) {
                        Notification::make()
                            ->title('NOTIFICACIÓN')
                            ->icon('heroicon-o-document-text')
                            ->iconColor('success')
                            ->color('success')
                            ->body($notificacion['message'])
                            ->send();
                    } else {
                        Notification::make()
                            ->title('NOTIFICACIÓN')
                            ->icon('heroicon-o-document-text')
                            ->iconColor('danger')
                            ->color('danger')
                            ->body($notificacion['message'])
                            ->send();
                    }
                }

                //Cargamos el movimiento de inventario en su tabla
                MovimientoInventarioController::registrar_movimiento(
                    $producto->id,
                    $item->cantidad,
                    $venta_producto->sucursal_id,
                    $codigoAsignacion,
                    'Venta',
                );

                //Cargamos la venta en su tabla de ventas
                VentaController::venta_producto(
                    $codigoAsignacion,
                    $venta_producto->total_venta,
                    $venta_producto->metodoUsd,
                    $venta_producto->metodoBsd,
                );
            }

            if ($venta_producto->save()) {
                return true;
            } else {
                return false;
            }
        } catch (\Throwable $th) {
            Notification::make()
                ->title('NOTIFICACIÓN')
                ->icon('heroicon-o-document-text')
                ->iconColor('danger')
                ->color('danger')
                ->body($th->getMessage())
                ->send();
        }
    }
}
