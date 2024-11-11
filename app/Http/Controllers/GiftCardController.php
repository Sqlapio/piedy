<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DetalleAsignacion;
use App\Models\Disponible;
use App\Models\GiftCard;
use App\Models\InventarioSucursal;
use App\Models\Membresia;
use App\Models\MetodoPago;
use App\Models\Producto;
use App\Models\Servicio;
use App\Models\VentaProducto;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;

class GiftCardController extends Controller
{
    static function validaGiftCard($codigo, $cod_asignacion)
    {
        try {

            $item = Disponible::where('cod_asignacion', $cod_asignacion)
                ->where('sucursal_id', Auth::user()->sucursal_id)
                ->where('status', 'cerrado')
                ->first();

            $valida_codigo = GiftCard::where('pgc', $codigo)->first();

            if (isset($valida_codigo)) {

                if ($valida_codigo->status == '1' && $valida_codigo->cliente_id != $item->cliente_id) {
                    return $array = [
                        'status' => 'error',
                        'mensaje' => 'TARJETA GIFTCARD ACTIVA!, PERO NO PERTENECE AL CLIENTE',
                    ];
                }

                if ($valida_codigo->status == '2') {
                    return $array = [
                        'status' => 'error',
                        'mensaje' => 'TARJETA GIFTCARD INACTIVA. FECHA DE USO: ' . $valida_codigo->updated_at . ''
                    ];
                }
            } else {
                return $array = [
                    'status' => 'error',
                    'mensaje' => 'CODIGO NO EXISTE'
                ];
            }

            $monto_giftCard = $valida_codigo->monto;

            $resta = $item->venta_total - $monto_giftCard;

            if ($resta < 0) {
                return $array = [
                    'status' => 'error',
                    'mensaje' => 'El monto de la GiftCard es mayor las monto total de venta'
                ];
            } else {
                return $array = [
                    'status' => 'true',
                    'valor' => $resta
                ];
            }
        } catch (\Throwable $th) {
            Notification::make()
                ->title('NOTIFICACIÓN')
                ->icon('heroicon-o-shield-check')
                ->iconColor('danger')
                ->body($th->getMessage())
                ->send();
        }
    }

    static function ejecutar_pago($codigo, $cod_asignacion, $cliente_id)
    {
        try {

            //Valores necesarios para realizar los asientos
            $valores = UtilsController::info($cod_asignacion);

            $giftCard = GiftCard::where('pgc', $codigo)
                ->where('sucursal_id', Auth::user()->sucursal_id)
                ->where('cliente_id', $cliente_id)
                ->first();

            //Logica cuando la gifcard se pago en Dolares
            if ($giftCard->metodo_pago == 1 || $giftCard->metodo_pago == 3) {
                /**
                 * Calculo de los productos
                 * y crear asiento en la tabla de venta Productos
                 */
                $productos = $valores['productos'];

                if (count($productos) > 0) {
                    foreach ($productos as $item) {

                        $producto = Producto::where('cod_producto', $item->cod_prod_serv)->first();
                        $venta_producto = new VentaProducto();
                        $venta_producto->cod_asignacion     = $cod_asignacion;
                        $venta_producto->gerente_id         = Auth::user()->id;
                        $venta_producto->producto_id        = $producto->id;
                        $venta_producto->costo_producto     = $producto->precio_venta;
                        $venta_producto->metodo_pago        = 'Usd';
                        $venta_producto->metodoUsd          = $giftCard->metodo_pago;
                        $venta_producto->comision_gerente   = (($valores['porcen_producto_gte'] * $producto->precio_venta) / 100) * $item->cantidad;
                        $venta_producto->comision_empleado  = (($valores['porcen_producto_emp'] * $producto->precio_venta) / 100) * $item->cantidad;
                        $venta_producto->fecha_venta        = now()->format('d-m-Y');
                        $venta_producto->cantidad           = $item->cantidad;
                        $venta_producto->total_venta        = $producto->precio_venta * $item->cantidad;
                        $venta_producto->responsable        = Auth::user()->name;
                        $venta_producto->sucursal_id        = Auth::user()->sucursal->id;
                        $venta_producto->cliente_id         = $valores['info_cliente_user']->cliente_id;
                        $venta_producto->empleado_id        = $valores['info_cliente_user']->empleado_id;
                        $venta_producto->save();

                        //Descuento la cantidad vendida de la exitencia del producto por sucursal
                        $productoSucursal = InventarioSucursal::where('producto_id', $producto->id)->where('sucursal_id', Auth::user()->sucursal->id)->first();
                        $productoSucursal->cantidad = $productoSucursal->cantidad - $item->cantidad;
                        $productoSucursal->save();

                        //Cargamos el movimiento de inventario en su tabla
                        MovimientoInventarioController::registrar_movimiento(
                            $producto->id,
                            $item->cantidad,
                            $venta_producto->sucursal_id,
                            $cod_asignacion,
                            'Venta',
                        );
                    }
                }

                /**
                 * LOGICA PARA EL CALCULO DE LAS COMICIONES
                 * DE ACUERDO CON EL SERVICIO SELECCIONADO
                 * POR EL CLIENTE
                 */
                if (Servicio::find($valores['servicio_id'])->asignacion == 'vip') {
                    //4.- Calculo de los servicios adicionales si existen!
                    $serv_adicionales = $valores['costo_total_servicios'] - $valores['costo_quiropedia_basica'];

                    //Calculo de Comision
                    $calculos = UtilsController::calculo_vip(
                        $valores['costo_quiropedia_basica'],
                        $serv_adicionales,
                        $valores['porcen_vip_emp'],
                        $valores['porcen_adi_emp'],
                        $valores['porcen_vip_gte'],
                    );


                    //Asiento en la tabla de ventas Servicios
                    VentaServicioController::venta_servicio_usd(
                        $giftCard->metodo_pago,
                        $cod_asignacion,
                        $valores['costo_total_servicios'],
                        $calculos['comision_total'],
                        $calculos['comision_gerente'],
                        $valores['info_cliente_user'],
                        $ref_pago_movil = null,
                        $ref_debito_credito = null,
                        $nro_tarjeta = null,
                        $propina_usd = null,
                        $propina_bsd = null,
                        $pro_ref_debito_credito = null,
                        $pro_nro_tarjeta = null
                    );

                    //Asiento tabla de Venta
                    VentaController::venta($cod_asignacion, $valores['total_venta'], $metodo_pago = 'N/A', $giftCard->metodo_pago);

                    return true;
                }

                if (Servicio::find($valores['servicio_id'])->asignacion == 'general') {
                    //Calculo de Comision
                    $calculos = UtilsController::calculo_general(
                        $valores['porcen_vip_emp'],
                        $valores['costo_total_servicios'],
                    );

                    //Asiento en la tabla de ventas Servicios
                    VentaServicioController::venta_servicio_usd(
                        $cod_asignacion,
                        $valores['costo_total_servicios'],
                        $calculos['comision_total'],
                        $calculos['comision_gerente'],
                        $valores['info_cliente_user'],
                        $ref_zelle = null,
                        $propina_usd = null,
                        $propina_bsd = null,
                        $pro_ref_debito_credito = null,
                        $pro_nro_tarjeta = null
                    );

                    //Asiento tabla de Venta
                    VentaController::venta($cod_asignacion, $valores['total_venta'], $metodo_pago = 'N/A', $giftCard->metodo_pago);

                    return true;
                }

                return true;
            }

            //Logica cuando la gifcard se pago en Bolivares
            if ($giftCard->metodo_pago == 4 || $giftCard->metodo_pago == 5 || $giftCard->metodo_pago == 7) {
                /**
                 * Calculo de los productos
                 * y crear asiento en la tabla de venta Productos
                 */
                $productos = $valores['productos'];

                if (count($productos) > 0) {
                    foreach ($productos as $item) {

                        $producto = Producto::where('cod_producto', $item->cod_prod_serv)->first();
                        $venta_producto = new VentaProducto();
                        $venta_producto->cod_asignacion     = $cod_asignacion;
                        $venta_producto->gerente_id         = Auth::user()->id;
                        $venta_producto->producto_id        = $producto->id;
                        $venta_producto->costo_producto     = $producto->precio_venta;
                        $venta_producto->metodo_pago        = 'Bsd';
                        $venta_producto->metodoBsd          = $giftCard->metodo_pago;
                        $venta_producto->comision_gerente   = (($valores['porcen_producto_gte'] * $producto->precio_venta) / 100) * $item->cantidad;
                        $venta_producto->comision_empleado  = (($valores['porcen_producto_emp'] * $producto->precio_venta) / 100) * $item->cantidad;
                        $venta_producto->fecha_venta        = now()->format('d-m-Y');
                        $venta_producto->cantidad           = $item->cantidad;
                        $venta_producto->total_venta        = $producto->precio_venta * $item->cantidad;
                        $venta_producto->responsable        = Auth::user()->name;
                        $venta_producto->sucursal_id        = Auth::user()->sucursal->id;
                        $venta_producto->cliente_id         = $valores['info_cliente_user']->cliente_id;
                        $venta_producto->empleado_id        = $valores['info_cliente_user']->empleado_id;
                        $venta_producto->save();

                        //Descuento la cantidad vendida de la exitencia del producto por sucursal
                        $productoSucursal = InventarioSucursal::where('producto_id', $producto->id)->where('sucursal_id', Auth::user()->sucursal->id)->first();
                        $productoSucursal->cantidad = $productoSucursal->cantidad - $item->cantidad;
                        $productoSucursal->save();

                        //Cargamos el movimiento de inventario en su tabla
                        MovimientoInventarioController::registrar_movimiento(
                            $producto->id,
                            $item->cantidad,
                            $venta_producto->sucursal_id,
                            $cod_asignacion,
                            'Venta',
                        );
                    }
                }

                /**
                 * LOGICA PARA EL CALCULO DE LAS COMICIONES
                 * DE ACUERDO CON EL SERVICIO SELECCIONADO
                 * POR EL CLIENTE
                 */
                if (Servicio::find($valores['servicio_id'])->asignacion == 'vip') {
                    //4.- Calculo de los servicios adicionales si existen!
                    $serv_adicionales = $valores['costo_total_servicios'] - $valores['costo_quiropedia_basica'];

                    //Calculo de Comision
                    $calculos = UtilsController::calculo_vip_bsd(
                        $valores['costo_quiropedia_basica'],
                        $serv_adicionales,
                        $valores['porcen_vip_emp'],
                        $valores['porcen_adi_emp'],
                        $valores['porcen_vip_gte'],
                    );


                    //Asiento en la tabla de ventas Servicios
                    VentaServicioController::venta_servicio_bsd(
                        $giftCard->metodo_pago,
                        $cod_asignacion,
                        $valores['costo_total_servicios'],
                        $calculos['comision_total'],
                        $calculos['comision_gerente'],
                        $valores['info_cliente_user'],
                        $ref_pago_movil = null,
                        $ref_debito_credito = null,
                        $nro_tarjeta = null,
                        $propina_usd = null,
                        $propina_bsd = null,
                        $pro_ref_debito_credito = null,
                        $pro_nro_tarjeta = null
                    );

                    //Asiento tabla de Venta
                    VentaController::venta($cod_asignacion, $valores['total_venta'], $metodo_pago = 'N/A', $giftCard->metodo_pago);

                    return true;
                }

                if (Servicio::find($valores['servicio_id'])->asignacion == 'general') {
                    //Calculo de Comision
                    $calculos = UtilsController::calculo_general_bsd(
                        $valores['porcen_vip_emp'],
                        $valores['costo_total_servicios'],
                    );

                    //Asiento en la tabla de ventas Servicios
                    VentaServicioController::venta_servicio_bsd(
                        $giftCard->metodo_pago,
                        $cod_asignacion,
                        $valores['costo_total_servicios'],
                        $calculos['comision_total'],
                        $calculos['comision_gerente'],
                        $valores['info_cliente_user'],
                        $ref_pago_movil = null,
                        $ref_debito_credito = null,
                        $nro_tarjeta = null,
                        $propina_usd = null,
                        $propina_bsd = null,
                        $pro_ref_debito_credito = null,
                        $pro_nro_tarjeta = null
                    );

                    //Asiento tabla de Venta
                    VentaController::venta($cod_asignacion, $valores['total_venta'], $metodo_pago = 'N/A', $giftCard->metodo_pago);

                    return true;
                }

                return true;
            }
        } catch (\Throwable $th) {
            Notification::make()
                ->title('NOTIFICACIÓN')
                ->icon('heroicon-o-shield-check')
                ->iconColor('danger')
                ->body($th->getMessage())
                ->send();
        }
    }
    //
}
