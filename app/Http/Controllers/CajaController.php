<?php

namespace App\Http\Controllers;

use App\Models\Comision;
use App\Models\InventarioSucursal;
use App\Models\MetodoPago;
use App\Models\Producto;
use App\Models\Servicio;
use App\Models\VentaProducto as VentaProducto;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class CajaController extends Controller
{
    static function dolares($monto_usd, $metodo_pago, $cod_asignacion, $ref_zelle , $propina_usd , $propina_bsd , $pro_ref_debito_credito , $pro_nro_tarjeta, $metodoUsd ) {

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
                    $venta_producto->metodo_pago        = $metodoUsd;
                    $venta_producto->metodoUsd          = MetodoPago::find($metodo_pago)->descripcion;
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
             if(Servicio::find($valores['servicio_id'])->asignacion == 'vip')
             {
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
                    $cod_asignacion,
                    $valores['costo_total_servicios'],
                    $calculos['comision_total'],
                    $calculos['comision_gerente'],
                    $valores['info_cliente_user'],
                    $ref_zelle,
                    $propina_usd,
                    $propina_bsd,
                    $pro_ref_debito_credito,
                    $pro_nro_tarjeta

                );

                //Asiento tabla de Venta
                VentaController::venta($cod_asignacion, $valores['total_venta'], $metodo_pago, $metodo_pago_dos = 'N/A');

                return true;
             }

             if(Servicio::find($valores['servicio_id'])->asignacion == 'general')
             {
                //Calculo de Comision
                $calculos = UtilsController::calculo_general(
                    $valores['costo_total_servicios'],
                    $valores['porcen_vip_emp'],
                );

                //Asiento en la tabla de ventas Servicios
                VentaServicioController::venta_servicio_usd(
                    $cod_asignacion,
                    $valores['costo_total_servicios'],
                    $calculos['comision_total'],
                    $calculos['comision_gerente'],
                    $valores['info_cliente_user'],
                    $ref_zelle,
                    $propina_usd,
                    $propina_bsd,
                    $pro_ref_debito_credito,
                    $pro_nro_tarjeta
                );

                //Asiento tabla de Venta
                VentaController::venta($cod_asignacion, $valores['total_venta'], $metodo_pago, $metodo_pago_dos = 'N/A');

                return true;

             }

            //code...
        } catch (\Throwable $th) {
            LogController::log(Auth::user()->id, 'excepcion', $th->getMessage(), $response = null);
            Notification::make()
            ->title('Notificacion: CajaController::dolares() ')
            ->icon('heroicon-o-shield-check')
            ->iconColor('danger')
            ->body($th->getMessage())
            ->send();
        }
    }

    static function bolivares($metodo_pago_dos, $cod_asignacion, $ref_pago_movil , $ref_debito_credito , $nro_tarjeta , $propina_usd , $propina_bsd , $pro_ref_debito_credito , $pro_nro_tarjeta, $monto_bsd ) {

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
                    $venta_producto->metodo_pago        = 'Bsd';
                    $venta_producto->metodoBsd          = MetodoPago::find($metodo_pago_dos)->descripcion;
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
             if(Servicio::find($valores['servicio_id'])->asignacion == 'vip')
             {
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
                    $metodo_pago_dos,
                    $cod_asignacion,
                    $valores['costo_total_servicios'],
                    $calculos['comision_total'],
                    $calculos['comision_gerente'],
                    $valores['info_cliente_user'],
                    $ref_pago_movil,
                    $ref_debito_credito,
                    $nro_tarjeta,
                    $propina_usd,
                    $propina_bsd,
                    $pro_ref_debito_credito,
                    $pro_nro_tarjeta
                );

                //Asiento tabla de Venta
                VentaController::venta($cod_asignacion, $valores['total_venta'],$metodo_pago = 'N/A', $metodo_pago_dos);

                return true;
             }

             if(Servicio::find($valores['servicio_id'])->asignacion == 'general')
             {
                //Calculo de Comision
                $calculos = UtilsController::calculo_general_bsd(
                    $valores['porcen_vip_emp'],
                    $valores['costo_total_servicios'],
                );

                //Asiento en la tabla de ventas Servicios
                VentaServicioController::venta_servicio_bsd(
                    $metodo_pago_dos,
                    $cod_asignacion,
                    $valores['costo_total_servicios'],
                    $calculos['comision_total'],
                    $calculos['comision_gerente'],
                    $valores['info_cliente_user'],
                    $ref_pago_movil,
                    $ref_debito_credito,
                    $nro_tarjeta,
                    $propina_usd,
                    $propina_bsd,
                    $pro_ref_debito_credito,
                    $pro_nro_tarjeta
                );

                //Asiento tabla de Venta
                VentaController::venta($cod_asignacion, $valores['total_venta'], $metodo_pago = 'N/A', $metodo_pago_dos);

                return true;

             }


        } catch (\Throwable $th) {
            LogController::log(Auth::user()->id, 'excepcion', $th->getMessage(), $response = null);
            Notification::make()
            ->title('Notificacion: CajaController::bolivares() ')
            ->icon('heroicon-o-shield-check')
            ->iconColor('danger')
            ->body($th->getMessage())
            ->send();
        }
    }


    static function multiple($monto_usd, $monto_bsd, $cod_asignacion,$metodo_pago, $metodo_pago_dos, $ref_zelle, $ref_pago_movil, $ref_debito_credito, $nro_tarjeta, $propina_usd, $propina_bsd, $pro_ref_debito_credito, $pro_nro_tarjeta) {
        // dd($monto_usd, $monto_bsd, $cod_asignacion,$metodo_pago, $metodo_pago_dos, $ref_zelle , $ref_pago_movil , $ref_debito_credito , $nro_tarjeta );
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
                    $venta_producto->metodo_pago        = 'Multiple';
                    $venta_producto->metodoUsd          = MetodoPago::find($metodo_pago)->descripcion;
                    $venta_producto->metodoBsd          = MetodoPago::find($metodo_pago_dos)->descripcion;
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
             if(Servicio::find($valores['servicio_id'])->asignacion == 'vip')
             {
                //4.- Calculo de los servicios adicionales si existen!
                $serv_adicionales = $valores['costo_total_servicios'] - $valores['costo_quiropedia_basica'];
                // dd($valores['costo_total_servicios'], $valores['costo_quiropedia_basica']);
                //Calculo de Comision
                $calculos = UtilsController::calculo_vip_multiple(
                    $monto_usd,
                    $monto_bsd,
                    $valores['total_venta'],
                    $valores['costo_quiropedia_basica'],
                    $serv_adicionales,
                    $valores['porcen_vip_emp'],
                    $valores['porcen_adi_emp'],
                    $valores['porcen_vip_gte'],
                );

                //Asiento en la tabla de ventas Servicios
                VentaServicioController::venta_servicio_multiple(
                    $monto_usd,
                    $monto_bsd,
                    $metodo_pago,
                    $metodo_pago_dos,
                    $cod_asignacion,
                    $valores['costo_total_servicios'],
                    $calculos['comision_dolares'],
                    $calculos['comision_bolivares'],
                    $calculos['comision_gerente'],
                    $ref_zelle,
                    $ref_pago_movil,
                    $ref_debito_credito,
                    $nro_tarjeta,
                    $valores['info_cliente_user'],
                    $propina_usd,
                    $propina_bsd,
                    $pro_ref_debito_credito,
                    $pro_nro_tarjeta
                );

                //Asiento tabla de Venta
                VentaController::venta($cod_asignacion, $valores['total_venta'], $metodo_pago, $metodo_pago_dos);

                return true;
             }

             if(Servicio::find($valores['servicio_id'])->asignacion == 'general')
             {
                //Calculo de Comision
                $calculos = UtilsController::calculo_general_multiple(
                    $monto_usd,
                    $monto_bsd,
                    $valores['total_venta'],
                    $valores['porcen_vip_emp'],
                    $valores['costo_total_servicios'],
                );
                //Asiento en la tabla de ventas Servicios
                VentaServicioController::venta_servicio_multiple(
                    $monto_usd,
                    $monto_bsd,
                    $metodo_pago,
                    $metodo_pago_dos,
                    $cod_asignacion,
                    $valores['costo_total_servicios'],
                    $calculos['comision_dolares'],
                    $calculos['comision_bolivares'],
                    $calculos['comision_gerente'],
                    $ref_zelle,
                    $ref_pago_movil,
                    $ref_debito_credito,
                    $nro_tarjeta,
                    $valores['info_cliente_user'],
                    $propina_usd,
                    $propina_bsd,
                    $pro_ref_debito_credito,
                    $pro_nro_tarjeta
                );

                //Asiento tabla de Venta
                VentaController::venta($cod_asignacion, $valores['total_venta'], $metodo_pago, $metodo_pago_dos);

                return true;

             }



        } catch (\Throwable $th) {
            LogController::log(Auth::user()->id, 'excepcion', $th->getMessage(), $response = null);
            Notification::make()
            ->title('Notificacion: CajaController::multiple() ')
            ->icon('heroicon-o-shield-check')
            ->iconColor('danger')
            ->body($th->getMessage())
            ->send();
        }
    }

    static function manejo_propinas() {

    }
}