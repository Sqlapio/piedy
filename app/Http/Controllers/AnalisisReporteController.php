<?php

namespace App\Http\Controllers;

use App\Models\Gasto;
use App\Models\TasaBcv;
use App\Models\PreNomina;
use App\Models\Requisicion;
use Illuminate\Http\Request;
use App\Models\NominaGeneral;
use App\Models\VentaProducto;
use App\Models\VentaServicio;
use App\Models\AnalisisReporte;
use App\Models\Compra;
use Illuminate\Support\Facades\Auth;

class AnalisisReporteController extends Controller
{
    public static function cierre($records, $fecha_ini, $fecha_fin, $cod_nomina)
    {
        try {
            /**
             * Para ejecutar el cierre del periodo debemos cumplir con las siguientes restricciones:
             * 1.- LA nomina debe estar en estatus 8 = 'Nomina-totalizada'
             * 2.- El detalle generado por el calculo de la nomina, que es los empleados calculados
             * deben estar en estatus 8 = 'Nomina-totalizada'
             */
            // dd($records[0]->fecha_ini . ' 06:00:00.000', $records[0]->fecha_fin . ' 23:59:59.000');

            if ($records[0]->status_id == 8) {

                $tasa_bcv = TasaBcv::where('fecha', date('d-m-Y'))->first()->tasa;

                //SERVICIOS
                //---------------------------------------------------------------------------------------------------------------------//

                //USD
                $venta_srv_usd = VentaServicio::where('sucursal_id', $records[0]->sucursal_id)
                    ->whereBetween('created_at', [$records[0]->fecha_ini . ' 06:00:00.000', $records[0]->fecha_fin . ' 23:59:59.000'])
                    ->sum('pago_usd');

                //BSD
                $venta_srv_bsd = VentaServicio::where('sucursal_id', $records[0]->sucursal_id)
                    ->whereBetween('created_at', [$records[0]->fecha_ini . ' 06:00:00.000', $records[0]->fecha_fin . ' 23:59:59.000'])
                    ->sum('pago_bsd');

                $conversion_srv = $venta_srv_bsd / $tasa_bcv;

                $total_venta_srv_usd = $venta_srv_usd + $conversion_srv;

                //---------------------------------------------------------------------------------------------------------------------//


                //PRODUCTOS
                //---------------------------------------------------------------------------------------------------------------------//

                //USD
                $venta_prod_usd = VentaProducto::where('sucursal_id', $records[0]->sucursal_id)
                    ->whereBetween('created_at', [$records[0]->fecha_ini . ' 06:00:00.000', $records[0]->fecha_fin . ' 23:59:59.000'])
                    ->sum('montoUsd');
                //BSD
                $venta_prod_bsd = VentaProducto::where('sucursal_id', $records[0]->sucursal_id)
                    ->whereBetween('created_at', [$records[0]->fecha_ini . ' 06:00:00.000', $records[0]->fecha_fin . ' 23:59:59.000'])
                    ->sum('montoBsd');

                $conversion_prod = $venta_prod_bsd / $tasa_bcv;

                $total_prod_usd = $venta_prod_usd + $conversion_prod;

                //---------------------------------------------------------------------------------------------------------------------//


                //GASTOS
                //---------------------------------------------------------------------------------------------------------------------//

                //USD
                $gastos_usd = Gasto::where('sucursal_id', $records[0]->sucursal_id)
                    ->whereBetween('created_at', [$records[0]->fecha_ini . ' 06:00:00.000', $records[0]->fecha_fin . ' 23:59:59.000'])
                    ->sum('monto_usd');
                //BSD
                $gastos_bsd = Gasto::where('sucursal_id', $records[0]->sucursal_id)
                    ->whereBetween('created_at', [$records[0]->fecha_ini . ' 06:00:00.000', $records[0]->fecha_fin . ' 23:59:59.000'])
                    ->sum('monto_bsd');

                $total_gastos_usd = Gasto::where('sucursal_id', $records[0]->sucursal_id)
                    ->whereBetween('created_at', [$records[0]->fecha_ini . ' 06:00:00.000', $records[0]->fecha_fin . ' 23:59:59.000'])
                    ->sum('conversion_a_usd');

                $conversion_gastos = $gastos_bsd / $tasa_bcv;

                //---------------------------------------------------------------------------------------------------------------------//


                //REQUISICIONES
                //---------------------------------------------------------------------------------------------------------------------//

                //USD
                $requisiciones_usd = Requisicion::where('sucursal_id', $records[0]->sucursal_id)
                    ->whereBetween('created_at', [$records[0]->fecha_ini . ' 06:00:00.000', $records[0]->fecha_fin . ' 23:59:59.000'])
                    ->sum('total_usd');
                //---------------------------------------------------------------------------------------------------------------------//


                //NOMINA
                $nomina_usd = NominaGeneral::where('sucursal_id', $records[0]->sucursal_id)
                    ->where('cod_nomina', $records[0]->cod_nomina)
                    ->where('status_id', 8)
                    ->sum('total_general');
                //---------------------------------------------------------------------------------------------------------------------//


                //COMISIONES
                //--------------------------------------------------------------------------------------------------------------------//
                //USD
                $comisiones_srv_usd = VentaServicio::where('sucursal_id', $records[0]->sucursal_id)
                    ->whereBetween('created_at', [$records[0]->fecha_ini . ' 06:00:00.000', $records[0]->fecha_fin . ' 23:59:59.000'])
                    ->sum('comision_dolares');
                    // dd($comisiones_srv_usd);

                $comisiones_gte_srv_usd = VentaServicio::where('sucursal_id', $records[0]->sucursal_id)
                    ->whereBetween('created_at', [$records[0]->fecha_ini . ' 06:00:00.000', $records[0]->fecha_fin . ' 23:59:59.000'])
                    ->sum('comision_gerente');

                $comisiones_prod_usd = VentaProducto::where('sucursal_id', $records[0]->sucursal_id)
                    ->whereBetween('created_at', [$records[0]->fecha_ini . ' 06:00:00.000', $records[0]->fecha_fin . ' 23:59:59.000'])
                    ->sum('comision_empleado');

                $comisiones_gte_prod_usd = VentaProducto::where('sucursal_id', $records[0]->sucursal_id)
                    ->whereBetween('created_at', [$records[0]->fecha_ini . ' 06:00:00.000', $records[0]->fecha_fin . ' 23:59:59.000'])
                    ->sum('comision_gerente');

                $total_comisiones_usd = $comisiones_srv_usd + $comisiones_gte_srv_usd + $comisiones_prod_usd + $comisiones_gte_prod_usd;

                //BSD
                $comisiones_bsd = VentaServicio::where('sucursal_id', $records[0]->sucursal_id)
                    ->whereBetween('created_at', [$records[0]->fecha_ini . ' 06:00:00.000', $records[0]->fecha_fin . ' 23:59:59.000'])
                    ->sum('comision_bolivares');

                $conversion_comisiones = $comisiones_bsd / $tasa_bcv;

                $total_general_comisiones = $total_comisiones_usd + $conversion_comisiones;

                //---------------------------------------------------------------------------------------------------------------------//


                //Creamos el registro en la tabla de Analisis y reporte
                $analisis = new AnalisisReporte();
                $analisis->cod_nomina               = $cod_nomina;
                $analisis->sucursal_id              = $records[0]->sucursal_id;
                $analisis->nomina_general_id        = $records[0]->id;
                $analisis->tasa_bcv                 = $tasa_bcv;

                $analisis->fecha_calculo            = date('Y-m-d');
                $analisis->fecha_ini                = $records[0]->fecha_ini;
                $analisis->fecha_fin                = $records[0]->fecha_fin;

                $analisis->productos_asignados      = $requisiciones_usd;

                $analisis->nomina                   = $nomina_usd;

                $analisis->venta_servicios_usd      = $venta_srv_usd;
                $analisis->venta_servicios_bsd      = $venta_srv_bsd;
                $analisis->con_ven_srv_bsd_a_usd    = $conversion_srv;
                $analisis->total_srv                = $analisis->venta_srv_usd + $conversion_srv;

                $analisis->venta_productos_usd      = $venta_prod_usd;
                $analisis->venta_productos_bsd      = $venta_prod_bsd;
                $analisis->con_ven_prod_bsd_a_usd   = $conversion_prod;
                $analisis->total_prod                = $analisis->venta_prod_usd + $conversion_prod;


                $analisis->comisiones_usd           = $total_comisiones_usd;
                $analisis->comisiones_bsd           = $comisiones_bsd;
                $analisis->con_comi_bsd_a_usd       = $conversion_comisiones;
                $analisis->total_comisiones         = $analisis->total_comisiones_usd + $conversion_comisiones;


                $analisis->gastos_usd               = $gastos_usd;
                $analisis->gastos_bsd               = $gastos_bsd;
                $analisis->con_gas_bsd_a_usd        = $conversion_gastos;
                $analisis->total_gastos             = $analisis->gastos_usd + $conversion_gastos;


                $analisis->sub_total_ingresos       = $analisis->venta_servicios_usd + $analisis->con_ven_srv_bsd_a_usd + $analisis->venta_productos_usd + $analisis->con_ven_prod_bsd_a_usd;
                $analisis->sub_total_egresos        = $total_gastos_usd + $requisiciones_usd + $nomina_usd;

                $analisis->neto                     = $analisis->sub_total_ingresos - $analisis->sub_total_egresos;
                $analisis->save();

                if($analisis->save()){
                    return true;
                }

            } else {
                return false;
                //false
            }

        } catch (\Throwable $th) {
            LogController::log(Auth::user()->id, 'excepcion-AnalisisReporteController(cierre)', $th->getMessage(), $response = null);
        }
    }
}
