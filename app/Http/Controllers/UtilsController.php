<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Comision;
use App\Models\CarProducto;
use App\Models\DetalleAsignacion;
use App\Models\Servicio;
use App\Models\Disponible;
use App\Models\Producto;
use App\Models\InventarioSucursal;
use App\Models\VentaProducto;
use App\Models\TasaBcv;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Exception;
use Filament\Notifications\Notification;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class UtilsController extends Controller
{
    static function cal_comision_gerente($total_venta)
    {
        $porcentaje = Comision::where('aplicacion', 'servicio')
            ->where('beneficiario', 'gerente')
            ->first()
            ->porcentaje;

        $calculo = ($total_venta * $porcentaje) / 100;

        return $calculo;
    }

    static function cal_comision_giftCard($monto, $tipoSrv)
    {

        try {
            /**1.- Busco el valor de la quiropedia basica */
            $quiroBasica = Servicio::where('descripcion', 'Quiropedia Basica')->first()->costo;

            /**2.- Calculo la diferencia del los productos adicionales */
            $prod_adi = $monto - $quiroBasica;

            if ($tipoSrv == 'vip') {

                /**Porcentaje de la quiropedia basica */
                $porcentaje_basico = Comision::where('aplicacion', 'servicio')
                    ->where('beneficiario', 'empleado')
                    ->first()
                    ->porcentaje;

                /**Porcentaje de los productos adicionales */
                $porcentaje_vip = Comision::where('aplicacion', 'vip')
                    ->where('beneficiario', 'empleado')
                    ->first()
                    ->porcentaje;

                $porcentaje_vip_gerente = Comision::where('aplicacion', 'vip')
                    ->where('beneficiario', 'gerente')
                    ->first()
                    ->porcentaje;

                /**Calculo de la comision en dolares el 40%, Quipedia basica */
                $comision_usd_qb = (floatval($quiroBasica) * $porcentaje_basico) / 100;

                /**Calculo de la comision en dolares el 10%, productos adicionales */
                $comision_usd_pa = (floatval($prod_adi) * $porcentaje_vip) / 100;

                /**Calculo de la comision total */
                $comision_usd_emp = $comision_usd_qb + $comision_usd_pa;

                /**Calculo de la comision del gerente de tienda */
                $comision_usd_gerente = (floatval($monto) * $porcentaje_vip_gerente) / 100;

                /**Array de comisiones */
                $array_comisiones = [
                    'comision_usd_emp_valorUno' => round($comision_usd_emp, 2),
                    'comision_bs_emp_valorDos'  => 0.00,
                    'comision_usd_gte'          => round($comision_usd_gerente, 2),
                ];

                // dd($array_comisiones);

                return $array_comisiones;
            }

            if ($tipoSrv != 'vip') {

                $porcentaje = Comision::where('aplicacion', 'servicio')
                    ->where('beneficiario', 'empleado')
                    ->first()
                    ->porcentaje;

                $comision_usd_emp = ($monto * $porcentaje) / 100;

                /**Array de comisiones */
                $array_comisiones = [
                    'comision_usd_emp_valorUno' => round($comision_usd_emp, 2),
                    'comision_bs_emp_valorDos'  => 0.00,
                    'comision_usd_gte' => 0.00,
                ];

                return $array_comisiones;
            }
            //code...
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    static function cal_comision_empleado($valorUno, $valorDos, $tipoSrv, $tipoSrvID, $total_vista = null, $monto_giftcard = 0)
    {

        try {

                $porcentaje = Comision::where('aplicacion', 'servicio')
                    ->where('beneficiario', 'empleado')
                    ->first()
                    ->porcentaje;

                /**Porcentaje de comision para servicio vip para empleados */
                $porcen_vip_emp = Comision::where('aplicacion', 'vip')
                    ->where('beneficiario', 'empleado')
                    ->first()
                    ->porcentaje;

                /**Porcentaje de comision para servicio vip para gerentes */
                $porcen_vip_gte = Comision::where('aplicacion', 'vip')
                    ->where('beneficiario', 'gerente')
                    ->first()
                    ->porcentaje;

                /**Porcentaje de comision para servicio adicionales para empleado */
                $porcen_adi_emp = Comision::where('aplicacion', 'servicio-adicional')
                    ->where('beneficiario', 'empleado')
                    ->first()
                    ->porcentaje;

            $valorUno = floatval($valorUno) + floatval($monto_giftcard);

            if ($tipoSrv != 'vip') {

                if($tipoSrvID == '2'){
                    /**1.- Busco el valor de la quiropedia basica */
                    $quiroBasica = Servicio::where('descripcion', 'Quiropedia Basica')->first()->costo;

                    /**Calculo la resta entre el total de la vista(el costo del servicio) - el costo de la quiropedia basica
                     * para obtener el costo de los productos adicionales
                     */
                    $costoProAdi = $total_vista - $quiroBasica;

                    /**Como el pago se puede realizar de dos formas, las porciones del pago seran tratadas por porcentaje,
                     * este valor de $pagoTotal equivale al 100% del monto a pagar
                     */
                    $pagoTotal = $quiroBasica + $costoProAdi;

                    /**Calculamos el porcentaje que representa la quiropedia basica */
                    $porcentajeQuiroBasica = ($quiroBasica * 100) / $pagoTotal;

                    /**Calculamos el porcentaje que representa el servicio adicional */
                    $porcentajeAdicional = ($costoProAdi * 100) / $pagoTotal;


                    /**Calculo de los porcentajes para el valor 1, que es el valor en dolares */
                    /************************************************************************************* */

                    /**Calculo del porcentaje para el valor 1 44,44%*/
                    $_equivaleValorUno_1 = (floatval($valorUno) * $porcentajeQuiroBasica) / 100;

                    /**Calculo del porcentaje para el valor 1 55,56%*/
                    $_equivaleValorUno_2 = (floatval($valorUno) * $porcentajeAdicional) / 100;

                    /**Calculo de la comision del empleado (40%, segun valor en tabla) sobre el valor 1 ($_equivaleValorUno_1), que equivale al costo de la quiropedia basica */
                    $comisionEmpleado_valorUno_1 = ($_equivaleValorUno_1 * $porcentaje) / 100;

                    /**Calculo de la comision del empleado (10%, segun valor en tabla) sobre el costo de los materiales adiciones ($_equivaleValorUno_2) */
                    $comisionEmpleado_valorUno_2 = ($_equivaleValorUno_2 * $porcen_adi_emp) / 100;

                    /**Comision total del empleado sobre el valor 1 */
                    $comisionEmpleado_valorUno = $comisionEmpleado_valorUno_1 + $comisionEmpleado_valorUno_2;

                    /**fin */
                    /************************************************************************************* */
                    /************************************************************************************* */



                    /**Calculo de los porcentajes para el valor 2, que es el valor en bolivares */
                    /******************************************* */

                    /**Calculo del porcentaje para el valor 1 44,44%*/
                    $_equivaleValorDos_1 = (floatval(Str::replace(',', '.', (Str::replace('.', '', $valorDos)))) * $porcentajeQuiroBasica) / 100;

                    /**Calculo del porcentaje para el valor 1 55,56%*/
                    $_equivaleValorDos_2 = (floatval(Str::replace(',', '.', (Str::replace('.', '', $valorDos)))) * $porcentajeAdicional) / 100;

                    /**Calculo de la comision del empleado (40%, segun valor en tabla) sobre el valor 2 ($_equivaleValorUno_1), que equivale al costo de la quiropedia basica */
                    $comisionEmpleado_valorDos_1 = ($_equivaleValorDos_1 * $porcentaje) / 100;

                    /**Calculo de la comision del empleado (10%, segun valor en tabla) sobre el costo de los materiales adiciones ($_equivaleValorUno_2) */
                    $comisionEmpleado_valorDos_2 = ($_equivaleValorDos_2 * $porcen_adi_emp) / 100;

                    /**Comision total del empleado sobre el valor 1 */
                    $comisionEmpleado_valorDos = $comisionEmpleado_valorDos_1 + $comisionEmpleado_valorDos_2;
                    /**fin */
                    /************************************************************************************* */
                    /************************************************************************************* */

                    /**Comisiones totales para la tabla de ventas */
                    $array_comisiones = [
                        'comision_usd_emp_valorUno' => round($comisionEmpleado_valorUno, 2),
                        'comision_bs_emp_valorDos'  => round($comisionEmpleado_valorDos, 2),
                        'comision_usd_gte'          => 0.00,
                    ];

                    return $array_comisiones;

                }

                if($tipoSrvID == '1'){
                    /**Calculo de la comision en dolares */
                    $comision_usd = (floatval($valorUno) * $porcentaje) / 100;

                    /**Calculo de la comision en bolivares */
                    $comision_bsd = (floatval(Str::replace(',', '.', (Str::replace('.', '', $valorDos)))) * $porcentaje) / 100;

                    /**Array de comisiones */
                    $array_comisiones = [
                        'comision_usd_emp_valorUno' => round($comision_usd, 2),
                        'comision_bs_emp_valorDos'  => round($comision_bsd, 2),
                        'comision_usd_gte'          => 0.00,
                    ];

                    return $array_comisiones;

                }

            }

            if ($tipoSrv == 'vip') {

                /**1.- Busco el valor de la quiropedia basica */
                $quiroBasica = Servicio::where('descripcion', 'Quiropedia Basica')->first()->costo;

                /**Calculo la resta entre el total de la vista(el costo del servicio) - el costo de la quiropedia basica
                 * para obtener el costo de los productos adicionales
                 */
                $costoProAdi = $total_vista - $quiroBasica;

                /**Como el pago se puede realizar de dos formas, las porciones del pago seran tratadas por porcentaje,
                 * este valor de $pagoTotal equivale al 100% del monto a pagar
                 */
                $pagoTotal = $quiroBasica + $costoProAdi;

                /**Calculamos el porcentaje que representa la quiropedia basica y el adicional 44,44% */
                $porcentajeQuiroBasica = ($quiroBasica * 100) / $pagoTotal;

                /**Calculamos el porcentaje que representa el adicional 55,56%*/
                $porcentajeAdicional = ($costoProAdi * 100) / $pagoTotal;

                /**Calculo de la comision del grente sobre el pago total en dolares*/
                $comisionGerente = ($pagoTotal * $porcen_vip_gte) / 100;


                /**Calculo de los porcentajes para el valor 1, que es el valor en dolares */
                /******************************************* */

                /**Calculo del porcentaje para el valor 1 44,44%*/
                $_equivaleValorUno_1 = (floatval($valorUno) * $porcentajeQuiroBasica) / 100;

                /**Calculo del porcentaje para el valor 1 55,56%*/
                $_equivaleValorUno_2 = (floatval($valorUno) * $porcentajeAdicional) / 100;

                /**Calculo de la comision del empleado (40%, segun valor en tabla) sobre el valor 1 ($_equivaleValorUno_1), que equivale al costo de la quiropedia basica */
                $comisionEmpleado_valorUno_1 = ($_equivaleValorUno_1 * $porcentaje) / 100;

                /**Calculo de la comision del empleado (10%, segun valor en tabla) sobre el costo de los materiales adiciones ($_equivaleValorUno_2) */
                $comisionEmpleado_valorUno_2 = ($_equivaleValorUno_2 * $porcen_vip_emp) / 100;

                /**Comision total del empleado sobre el valor 1 */
                $comisionEmpleado_valorUno = $comisionEmpleado_valorUno_1 + $comisionEmpleado_valorUno_2;

                /**fin */
                /******************************************* */


                /**Calculo de los porcentajes para el valor 2, que es el valor en bolivares */
                /******************************************* */

                /**Calculo del porcentaje para el valor 1 44,44%*/
                $_equivaleValorDos_1 = (floatval(Str::replace(',', '.', (Str::replace('.', '', $valorDos)))) * $porcentajeQuiroBasica) / 100;

                /**Calculo del porcentaje para el valor 1 55,56%*/
                $_equivaleValorDos_2 = (floatval(Str::replace(',', '.', (Str::replace('.', '', $valorDos)))) * $porcentajeAdicional) / 100;

                /**Calculo de la comision del empleado (40%, segun valor en tabla) sobre el valor 2 ($_equivaleValorUno_1), que equivale al costo de la quiropedia basica */
                $comisionEmpleado_valorDos_1 = ($_equivaleValorDos_1 * $porcentaje) / 100;

                /**Calculo de la comision del empleado (10%, segun valor en tabla) sobre el costo de los materiales adiciones ($_equivaleValorUno_2) */
                $comisionEmpleado_valorDos_2 = ($_equivaleValorDos_2 * $porcen_vip_emp) / 100;

                /**Comision total del empleado sobre el valor 1 */
                $comisionEmpleado_valorDos = $comisionEmpleado_valorDos_1 + $comisionEmpleado_valorDos_2;
                /**fin */
                /******************************************* */


                /**Comisiones totales para la tabla de ventas */
                $array_comisiones = [
                    'comision_usd_emp_valorUno' => round($comisionEmpleado_valorUno, 2),
                    'comision_bs_emp_valorDos'  => round($comisionEmpleado_valorDos, 2),
                    'comision_usd_gte'          => round($comisionGerente, 2),
                ];

                return $array_comisiones;
            }

            //code...
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    static function agenda($key, $mes)
    {

        $data = Trend::model(Cita::class)
            ->between(
                now()->startOfMonth()->month($mes),
                now()->endOfMonth()->month($mes),
            )
            ->perDay()
            ->count();
        $array = $data->map(fn (TrendValue $value) => Carbon::parse($value->date)->isoFormat('dddd, D MMM'))->toArray();

        return $array[$key];
    }

    static function comiEmple_ventaProducto($costo)
    {
        $comision = Comision::where('aplicacion', 'producto')
        ->where('beneficiario', 'empleado')
        ->where('accion', 'directa')
        ->first()
        ->porcentaje;

        $calculo = ($comision * $costo) / 100;

        return $calculo;
    }

    static function comiGerente_ventaProducto($costo)
    {
        $comision = Comision::where('aplicacion', 'producto')
        ->where('beneficiario', 'gerente')
        ->where('accion', 'indirecta')
        ->first()
        ->porcentaje;

        $calculo = ($comision * $costo) / 100;

        return $calculo;
    }

    static function comiGerente_ventaProducto_directa($costo)
    {
        $comision = Comision::where('aplicacion', 'producto')
        ->where('beneficiario', 'gerente')
        ->where('accion', 'directa')
        ->first()
        ->porcentaje;

        $calculo = ($comision * $costo) / 100;

        return $calculo;
    }

    static function info($cod_asignacion)
    {
        try {

            $costo_quiropedia_basica = Servicio::where('sucursal_id', Auth::user()->sucursal_id)
            ->where('descripcion', 'Quiropedia Basica')
            ->where('rol_id', 2)
            ->where('categoria', 'principal')
            ->first()->costo;

            /**Porcentaje de comision para servicio vip para empleados */
            $porcen_vip_emp = Comision::where('aplicacion', 'servicio')
            ->where('beneficiario', 'empleado')
            ->first()
            ->porcentaje;

            /**Porcentaje de comision para servicio vip para gerentes */
            $porcen_vip_gte = Comision::where('aplicacion', 'vip')
            ->where('beneficiario', 'gerente')
            ->first()
            ->porcentaje;

            /**Porcentaje de comision para servicio adicionales para empleado */
            $porcen_adi_emp = Comision::where('aplicacion', 'servicio-adicional')
            ->where('beneficiario', 'empleado')
            ->first()
            ->porcentaje;

            //3.- Costo total de todos los servicios
            $costo_servicios = DetalleAsignacion::where('cod_asignacion', $cod_asignacion)
            ->where('sucursal_id', Auth::user()->sucursal_id)
            ->where('tipo', 'servicio')
            ->where('status', 2)
            ->sum('costo');

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

            $productos = DetalleAsignacion::where('cod_asignacion', $cod_asignacion)
                ->where('sucursal_id', Auth::user()->sucursal_id)
                ->where('tipo', 'producto')
                ->where('status', 2)
                ->get();

            $info_cliente_user = Disponible::where('cod_asignacion', $cod_asignacion)
                ->where('sucursal_id', Auth::user()->sucursal_id)
                ->where('status', 'cerrado')
                ->first();

            $servicio_id = Disponible::where('cod_asignacion', $cod_asignacion)
                ->where('sucursal_id', Auth::user()->sucursal_id)
                ->where('status', 'cerrado')
                ->first()
                ->servicio_id;

            //code...
            return $valores = [
                'costo_quiropedia_basica'   => $costo_quiropedia_basica,
                'porcen_vip_emp'            => $porcen_vip_emp,
                'porcen_vip_gte'            => $porcen_vip_gte,
                'costo_total_servicios'     => $costo_servicios,
                'porcen_adi_emp'            => $porcen_adi_emp,
                'porcen_producto_emp'       => $porComEmp,
                'porcen_producto_gte'       => $porComGte,
                'productos'                 => $productos,
                'info_cliente_user'         => $info_cliente_user,
                'servicio_id'               => $servicio_id
            ];

        } catch (\Throwable $th) {
            dd($th);
        }

    }

    static function calculo_vip($quirop_basica, $srvs_adicional, $porcen_vip_emp, $porcen_adi_emp, $porcen_vip_gte)
    {
        try {

            $porcen_quirop_basica = ($porcen_vip_emp * $quirop_basica) / 100;

            $porcen_servs_adicionales = ($porcen_adi_emp * $srvs_adicional) / 100;

            $total_servicio = $quirop_basica + $srvs_adicional;
            $porcen_gerente = ($porcen_vip_gte * $total_servicio) / 100;

            return $res = [
                'comision_total' => $porcen_quirop_basica + $porcen_servs_adicionales,
                'comision_gerente' => $porcen_gerente
            ];
                //code...
        } catch (\Throwable $th) {
            dd($th);
        }

    }

}
