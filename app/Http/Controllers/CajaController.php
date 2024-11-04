<?php

namespace App\Http\Controllers;

use App\Models\Comision;
use Illuminate\Http\Request;
use App\Models\DetalleAsignacion;
use App\Models\Disponible;
use App\Models\InventarioSucursal;
use App\Models\MetodoPago;
use App\Models\Producto;
use App\Models\Servicio;
use App\Models\VentaProducto as VentaProducto;
use Illuminate\Support\Facades\Auth;

class CajaController extends Controller
{
    static function dolares($monto_usd, $metodo_pago, $cod_asignacion, $ref_zelle = null) {

        try {

            //Valores necesarios para realizar los asientos
            $valores = UtilsController::info($cod_asignacion);

            /**
             * Calculo de los productos
             * y crear asiento en la tabla de venta Productos
             */
            $productos = $valores['productos'];

            if(count($productos) > 0)
            {
                foreach($productos as $item)
                {

                    $producto = Producto::where('cod_producto', $item->cod_prod_serv)->first();
                    $venta_producto = new VentaProducto();
                    $venta_producto->cod_asignacion     = $cod_asignacion;
                    $venta_producto->gerente_id         = Auth::user()->id;
                    $venta_producto->producto_id        = $producto->id;
                    $venta_producto->costo_producto     = $producto->precio_venta;
                    $venta_producto->metodo_pago        = 'USD';
                    $venta_producto->metodoUsd          = MetodoPago::find($metodo_pago)->descripcion;
                    $venta_producto->montoUsd           = $producto->precio_venta;
                    $venta_producto->comision_gerente   = ($valores['porcen_producto_gte'] * $producto->precio_venta) / 100;
                    $venta_producto->comision_empleado  = ($valores['porcen_producto_emp'] * $producto->precio_venta) / 100;
                    $venta_producto->fecha_venta        = now()->format('d-m-Y');
                    $venta_producto->cantidad           = 1;
                    $venta_producto->total_venta        = $producto->precio_venta * $item->cantidad;
                    $venta_producto->responsable        = Auth::user()->name;
                    $venta_producto->sucursal_id        = Auth::user()->sucursal->id;
                    $venta_producto->cliente_id         = $valores['info_cliente_user']->cliente_id;
                    $venta_producto->empleado_id        = $valores['info_cliente_user']->empleado_id;
                    $venta_producto->save();

                    //Descuento la cantidad vendida de la exitencia del producto por sucursal
                    $productoSucursal = InventarioSucursal::where('producto_id', $producto->id)->where('sucursal_id', Auth::user()->sucursal->id)->first();
                    $productoSucursal->cantidad = $productoSucursal->cantidad - 1;
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
             if(Servicio::find($valores['servicio_id'])->asignacion == 'vip')
             {
                //4.- Calculo de los servicios adicionales si existen!
                $serv_adicionales = $valores['costo_total_servicios'] - $valores['costo_quiropedia_basica'];

                $calculos = UtilsController::calculo_vip(
                    $valores['costo_quiropedia_basica'],
                    $serv_adicionales,
                    $valores['porcen_vip_emp'],
                    $valores['porcen_adi_emp'],
                    $valores['porcen_vip_gte'],
                );

                VentaServicioController::venta_servicio_usd(
                    $cod_asignacion,
                    $valores['costo_total_servicios'],
                    $calculos['comision_total'],
                    $calculos['comision_gerente']
                );
             }

             if(Servicio::find($valores['servicio_id'])->asignacion == 'general')
             {


             }



            dd('aqui');
            //code...
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    static function bolivares($monto_bsd, $metodo_pago_dos, $cod_asignacion, $ref_pago_movil = null, $ref_debito_credito = null, $nro_tarjeta = null) {
        dd($monto_bsd, $metodo_pago_dos, $cod_asignacion, $ref_pago_movil, $ref_debito_credito, $nro_tarjeta);
        try {
            $producto = DetalleAsignacion::where('cod_asignacion', $cod_asignacion)
            ->where('sucursal_id', Auth::user()->sucursal_id)
            ->where('tipo', 'producto')
            ->where('status', 2)
            ->get();
            //code...
        } catch (\Throwable $th) {
            //throw $th;
        }
    }


    static function multiple($monto_usd, $monto_bsd, $cod_asignacion,$metodo_pago, $metodo_pago_dos, $ref_zelle = null, $ref_pago_movil = null, $ref_debito_credito = null, $nro_tarjeta = null) {
        dd($monto_usd, $monto_bsd, $cod_asignacion, $ref_zelle, $ref_pago_movil, $ref_debito_credito, $nro_tarjeta);
        try {
            $producto = DetalleAsignacion::where('cod_asignacion', $cod_asignacion)
            ->where('sucursal_id', Auth::user()->sucursal_id)
            ->where('tipo', 'producto')
            ->where('status', 2)
            ->get();
            //code...
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    static function manejo_propinas() {

    }
}
