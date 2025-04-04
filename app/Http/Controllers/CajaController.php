<?php

namespace App\Http\Controllers;

use App\Models\TasaBcv;
use App\Models\Comision;
use App\Models\Producto;
use App\Models\Servicio;
use App\Models\Disponible;
use App\Models\MetodoPago;
use App\Models\DetalleAsignacion;
use App\Models\InventarioSucursal;
use App\Models\ConfiguracionNomina;
use App\Models\FacturacionMultiple;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;
use App\Models\VentaProducto as VentaProducto;

class CajaController extends Controller
{
    static function dolares($monto_usd, $metodo_pago, $cod_asignacion, $ref_zelle, $propina_usd, $propina_bsd, $pro_ref_debito_credito, $pro_nro_tarjeta, $metodoUsd)
    {

        try {

            //Valores necesarios para realizar los asientos
            $valores = UtilsController::info($cod_asignacion);

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
                    $venta_producto->metodo_pago        = $metodoUsd;
                    $venta_producto->metodoUsd          = MetodoPago::find($metodo_pago)->descripcion;
                    $venta_producto->comision_gerente   = 0.00;
                    $venta_producto->comision_empleado  = (($valores['porcen_producto_emp'] * $producto->precio_venta) / 100) * $item->cantidad;
                    $venta_producto->fecha_venta        = now()->format('d-m-Y');
                    $venta_producto->cantidad           = $item->cantidad;
                    $venta_producto->total_venta        = $producto->precio_venta * $item->cantidad;
                    $venta_producto->responsable        = Auth::user()->name;
                    $venta_producto->sucursal_id        = Auth::user()->sucursal->id;
                    $venta_producto->cliente_id         = $valores['info_cliente_user']->cliente_id;
                    $venta_producto->empleado_id        = $valores['info_cliente_user']->empleado_id;
                    $venta_producto->montoUsd           = $producto->precio_venta * $item->cantidad;
                    
                    //Impuestos US$
                    $venta_producto->base_imponible_usd       = $venta_producto->montoUsd / 1.19;
                    $venta_producto->iva_usd                  = $venta_producto->base_imponible_usd * 0.16 ?? 0.00;
                    $venta_producto->impuesto_igtf                     = $venta_producto->base_imponible_usd * 0.03 ?? 0.00;
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
                    $cod_asignacion,
                    $valores['costo_total_servicios'],
                    $calculos['comision_total'],
                    $calculos['comision_gerente'],
                    $valores['info_cliente_user'],
                    $ref_zelle,
                    $propina_usd,
                    $propina_bsd,
                    $pro_ref_debito_credito,
                    $pro_nro_tarjeta,
                    $metodo_pago

                );

                //Asiento tabla de Venta
                VentaController::venta($cod_asignacion, $valores['total_venta'], $metodo_pago, $metodo_pago_dos = 'N/A');

                return true;
            }

            if (Servicio::find($valores['servicio_id'])->asignacion == 'general') {
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
                    $pro_nro_tarjeta,
                    $metodo_pago
                );

                //Asiento tabla de Venta
                VentaController::venta($cod_asignacion, $valores['total_venta'], $metodo_pago, $metodo_pago_dos = 'N/A');

                return true;
            }

            //code...
        } catch (\Throwable $th) {
            LogController::log(Auth::user()->id, 'excepcion-CajaController(dolares)', $th->getMessage(), $response = null);
            Notification::make()
                ->title('Notificacion: CajaController::dolares() ')
                ->icon('heroicon-o-shield-check')
                ->iconColor('danger')
                ->body($th->getMessage())
                ->send();
        }
    }

    static function bolivares($metodo_pago_dos, $cod_asignacion, $ref_pago_movil, $ref_debito_credito, $nro_tarjeta, $propina_usd, $propina_bsd, $pro_ref_debito_credito, $pro_nro_tarjeta, $monto_bsd)
    {

        try {
            //Valores necesarios para realizar los asientos
            $valores = UtilsController::info($cod_asignacion);

            $descuento = ConfiguracionNomina::select('iva_nomina', 'igtf')->first(); // IVA = 1.16

            /**
             * Calculo de los productos
             * y crear asiento en la tabla de venta Productos
             */
            $productos = $valores['productos'];

            $tasa = TasaBcv::all()->first()->tasa;

            if (count($productos) > 0) {
                foreach ($productos as $item) {

                    $producto = Producto::where('cod_producto', $item->cod_prod_serv)->first();
                    $venta_producto = new VentaProducto();
                    $venta_producto->cod_asignacion     = $cod_asignacion;
                    $venta_producto->gerente_id         = Auth::user()->id;
                    $venta_producto->producto_id        = $producto->id;
                    $venta_producto->costo_producto     = $producto->precio_venta;
                    $venta_producto->metodo_pago        = 'Bsd';
                    $venta_producto->metodoBsd          = MetodoPago::find($metodo_pago_dos)->descripcion;
                    $venta_producto->comision_gerente   = 0.00;
                    $venta_producto->comision_empleado  = (($valores['porcen_producto_emp'] * $producto->precio_venta) / 100) * $item->cantidad;
                    $venta_producto->fecha_venta        = now()->format('d-m-Y');
                    $venta_producto->cantidad           = $item->cantidad;
                    $venta_producto->total_venta        = $producto->precio_venta * $item->cantidad;
                    $venta_producto->responsable        = Auth::user()->name;
                    $venta_producto->sucursal_id        = Auth::user()->sucursal->id;
                    $venta_producto->cliente_id         = $valores['info_cliente_user']->cliente_id;
                    $venta_producto->empleado_id        = $valores['info_cliente_user']->empleado_id;
                    $venta_producto->montoBsd           = ($producto->precio_venta * $item->cantidad) * $tasa;

                    //Impuestos VES
                    $venta_producto->base_imponible_bsd       = $venta_producto->montoBsd / $descuento->iva_nomina;
                    $venta_producto->iva_bsd                  = $venta_producto->base_imponible_bsd * 0.16 ?? 0.00;
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
                //-------------------------------------------------
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
                //----------------------------------------------------------------------------------------------------------
                VentaController::venta($cod_asignacion, $valores['total_venta'], $metodo_pago = 'N/A', $metodo_pago_dos);

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
            LogController::log(Auth::user()->id, 'excepcion-CajaController(bolivares)', $th->getMessage(), $response = null);
            Notification::make()
                ->title('Notificacion: CajaController::bolivares() ')
                ->icon('heroicon-o-shield-check')
                ->iconColor('danger')
                ->body($th->getMessage())
                ->send();
        }
    }


    static function multiple($monto_srv_usd, $monto_srv_bsd, $monto_prod_usd, $monto_prod_bsd, $cod_asignacion, $metodo_pago, $metodo_pago_dos, $ref_zelle, $ref_pago_movil, $ref_debito_credito, $nro_tarjeta, $propina_usd, $propina_bsd, $pro_ref_debito_credito, $pro_nro_tarjeta)
    {

        try {

            //Valores necesarios para realizar los asientos
            $valores = UtilsController::info($cod_asignacion);

            $descuento = ConfiguracionNomina::select('iva_nomina', 'igtf')->first(); // IVA = 1.16

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
                    $venta_producto->metodo_pago        = 'Multiple';
                    $venta_producto->metodoUsd          = MetodoPago::find($metodo_pago)->descripcion;
                    $venta_producto->metodoBsd          = MetodoPago::find($metodo_pago_dos)->descripcion;
                    $venta_producto->comision_gerente   = 0.00;
                    $venta_producto->comision_empleado  = (($valores['porcen_producto_emp'] * $producto->precio_venta) / 100) * $item->cantidad;
                    $venta_producto->fecha_venta        = now()->format('d-m-Y');
                    $venta_producto->cantidad           = $item->cantidad;
                    $venta_producto->total_venta        = $producto->precio_venta * $item->cantidad;
                    $venta_producto->responsable        = Auth::user()->name;
                    $venta_producto->sucursal_id        = Auth::user()->sucursal->id;
                    $venta_producto->cliente_id         = $valores['info_cliente_user']->cliente_id;
                    $venta_producto->empleado_id        = $valores['info_cliente_user']->empleado_id;
                    
                    $venta_producto->montoUsd           = $monto_prod_usd;
                    //Impuestos US$
                    $venta_producto->base_imponible_usd       = $venta_producto->montoUsd / 1.19;
                    $venta_producto->iva_usd                  = $venta_producto->base_imponible_usd * 0.16 ?? 0.00;
                    $venta_producto->impuesto_igtf                     = $venta_producto->base_imponible_usd * 0.03 ?? 0.00;
                    
                    $venta_producto->montoBsd           = $monto_prod_bsd;
                    //Impuestos VES
                    $venta_producto->base_imponible_bsd       = $venta_producto->montoBsd / $descuento->iva_nomina;
                    $venta_producto->iva_bsd                  = $venta_producto->base_imponible_bsd * 0.16 ?? 0.00;

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
                // dd($valores['costo_total_servicios'], $valores['costo_quiropedia_basica']);
                //Calculo de Comision
                $calculos = UtilsController::calculo_vip_multiple(
                    $monto_srv_usd,
                    $monto_srv_bsd,
                    $valores['total_venta'],
                    $valores['costo_quiropedia_basica'],
                    $serv_adicionales,
                    $valores['porcen_vip_emp'],
                    $valores['porcen_adi_emp'],
                    $valores['porcen_vip_gte'],
                );

                //Asiento en la tabla de ventas Servicios
                VentaServicioController::venta_servicio_multiple(
                    $monto_srv_usd,
                    $monto_srv_bsd,
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

            if (Servicio::find($valores['servicio_id'])->asignacion == 'general') {
                //Calculo de Comision
                $calculos = UtilsController::calculo_general_multiple(
                    $monto_srv_usd,
                    $monto_srv_bsd,
                    $valores['total_venta'],
                    $valores['porcen_vip_emp'],
                    $valores['costo_total_servicios'],
                );
                //Asiento en la tabla de ventas Servicios
                VentaServicioController::venta_servicio_multiple(
                    $monto_srv_usd,
                    $monto_srv_bsd,
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
            LogController::log(Auth::user()->id, 'excepcion-CajaController(multiple)', $th->getMessage(), $response = null);
            Notification::make()
                ->title('Notificacion: CajaController::multiple() ')
                ->icon('heroicon-o-shield-check')
                ->iconColor('danger')
                ->body($th->getMessage())
                ->send();
        }
    }


    /**
     * Logica para la facturacion multiple de servicios
     */

    static function calculo_porcentajes_srv_fm($cod_asigancion, $pago_usd, $pago_bsd)
    {

        try {

            $total_venta = FacturacionMultiple::where('sucursal_id', Auth::user()->sucursal_id)->first()->venta_total_usd;
            
            //Calculo de los porcentajes de venta por representacion
            $total_servicios = Disponible::where('cod_asignacion', $cod_asigancion)
                ->where('sucursal_id', Auth::user()->sucursal_id)
                ->where('status', 'cerrado')
                ->first();
            
            // $total_venta = $total_servicios->venta_total;
            $total_venta_srv = $total_servicios->acu_servicios;
            
            $porcen_servicio = ($total_venta_srv * 100) / $total_venta;
            
            //Calculo del equivalente en dolares
            $valor_usd = ($pago_usd * $porcen_servicio) / 100;
            
            //Calculo del equivalente en bolivares
            $valor_bsd = ($pago_bsd * $porcen_servicio) / 100;

            return $array = [
                'valor_usd' => $valor_usd,
                'valor_bsd' => $valor_bsd
            ];
        } catch (\Throwable $th) {
            LogController::log(Auth::user()->id, 'excepcion-CajaController(multiple)', $th->getMessage(), $response = null);
            Notification::make()
                ->title('Notificacion: CajaController::multiple() ')
                ->icon('heroicon-o-shield-check')
                ->iconColor('danger')
                ->body($th->getMessage())
                ->send();
        }
    }

    static function calculo_porcentajes_prod_fm($cod_asigancion, $pago_usd, $pago_bsd)
    {

        try {

            $total_venta = FacturacionMultiple::where('sucursal_id', Auth::user()->sucursal_id)->first()->venta_total_usd;

            //Calculo de los porcentajes de venta por representacion
            $total_servicios = Disponible::where('cod_asignacion', $cod_asigancion)
                ->where('sucursal_id', Auth::user()->sucursal_id)
                ->where('status', 'cerrado')
                ->first();

            // $total_venta = $total_servicios->venta_total;
            $total_venta_prod = $total_servicios->acu_productos;

            $porcen_prod = ($total_venta_prod * 100) / $total_venta;

            //Calculo del equivalente en dolares
            $valor_usd = ($pago_usd * $porcen_prod) / 100;

            //Calculo del equivalente en bolivares
            $valor_bsd = ($pago_bsd * $porcen_prod) / 100;

            return $array = [
                'valor_usd' => $valor_usd,
                'valor_bsd' => $valor_bsd
            ];
        } catch (\Throwable $th) {
            LogController::log(Auth::user()->id, 'excepcion-CajaController(multiple)', $th->getMessage(), $response = null);
            Notification::make()
                ->title('Notificacion: CajaController::multiple() ')
                ->icon('heroicon-o-shield-check')
                ->iconColor('danger')
                ->body($th->getMessage())
                ->send();
        }
    }


    /**
     * Logica para el pago multiple
     */
    static function calculo_porcentajes_srv($cod_asigancion, $pago_usd, $pago_bsd)
    {

        try {

            $total_servicios = Disponible::where('cod_asignacion', $cod_asigancion)
                ->where('sucursal_id', Auth::user()->sucursal_id)
                ->where('status', 'cerrado')
                ->first();

            $total_venta = $total_servicios->venta_total;

            $total_venta_srv = $total_servicios->acu_servicios;

            $porcen_servicio = ($total_venta_srv * 100) / $total_venta;

            //Calculo del equivalente en dolares
            $valor_usd = ($pago_usd * $porcen_servicio) / 100;

            //Calculo del equivalente en bolivares
            $valor_bsd = ($pago_bsd * $porcen_servicio) / 100;

            return $array = [
                'valor_usd' => $valor_usd,
                'valor_bsd' => $valor_bsd
            ];
        } catch (\Throwable $th) {
            LogController::log(Auth::user()->id, 'excepcion-CajaController(multiple)', $th->getMessage(), $response = null);
            Notification::make()
                ->title('Notificacion: CajaController::multiple() ')
                ->icon('heroicon-o-shield-check')
                ->iconColor('danger')
                ->body($th->getMessage())
                ->send();
        }
    }

    static function calculo_porcentajes_prod($cod_asigancion, $pago_usd, $pago_bsd)
    {

        try {

            //Calculo de los porcentajes de venta por representacion
            $total_servicios = Disponible::where('cod_asignacion', $cod_asigancion)
                ->where('sucursal_id', Auth::user()->sucursal_id)
                ->where('status', 'cerrado')
                ->first();
  
            $total_venta = $total_servicios->venta_total;
  
            $total_venta_prod = $total_servicios->acu_productos;

            $porcen_prod = ($total_venta_prod * 100) / $total_venta;

            //Calculo del equivalente en dolares
            $valor_usd = ($pago_usd * $porcen_prod) / 100;
            //Calculo del equivalente en bolivares
            $valor_bsd = ($pago_bsd * $porcen_prod) / 100;

            return $array = [
                'valor_usd' => $valor_usd,
                'valor_bsd' => $valor_bsd
            ];
        } catch (\Throwable $th) {
            LogController::log(Auth::user()->id, 'excepcion-CajaController(multiple)', $th->getMessage(), $response = null);
            Notification::make()
                ->title('Notificacion: CajaController::multiple() ')
                ->icon('heroicon-o-shield-check')
                ->iconColor('danger')
                ->body($th->getMessage())
                ->send();
        }
    }

    static function detalleServicio($cod_asignacion)
    {
        // dd($cod_asignacion);
        try {

            $detalle = Disponible::where('cod_asignacion', $cod_asignacion)
                ->with('cliente', 'empleado')
                ->first();
            return view('detalle-servicio-facturado', compact('detalle'));
        } catch (\Throwable $th) {
            LogController::log(Auth::user()->id, 'excepcion-CajaController(detalleServicio)', $th->getMessage(), $response = null);
        }
    }
}