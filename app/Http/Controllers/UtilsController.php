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

    static function agenda($mes, $opcion)
    {
        if($opcion == 'semana'){
            $inicio = now()->startOfWeek()->month($mes);
            $fin = now()->endOfWeek();
        }
        if($opcion == 'mes'){
            $inicio = now()->startOfMonth()->month($mes);
            $fin = now()->endOfMonth()->month($mes);
        }
        if($opcion == 'dia'){
            $inicio = now()->startOfDay()->month($mes);
            $fin = now()->endOfDay()->month($mes);
        }

        $data = Trend::model(Cita::class)
            ->between(
                $inicio,
                $fin,
            )
            ->perDay()
            ->count();
        $array = $data->map(fn (TrendValue $value) => Carbon::parse($value->date)->format('Y-m-d'))->toArray();

        return $array;
    }

    static function comiEmple_ventaProducto($costo)
    {
        $comision = Comision::where('aplicacion', 'producto')
        ->where('beneficiario', 'empleado')
        ->where('sucursal_id', Auth::user()->sucursal_id)
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
        ->where('sucursal_id', Auth::user()->sucursal_id)
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
        ->where('sucursal_id', Auth::user()->sucursal_id)
        ->where('accion', 'directa')
        ->first()
        ->porcentaje;

        $calculo = ($comision * $costo) / 100;

        return $calculo;
    }

    static function info($cod_asignacion)
    {
        try {

            //Total de la venta
            //El query traera la venta total, sumatoria entre los productos y servicios asociados a la venta
            $total_venta = Disponible::where('cod_asignacion', $cod_asignacion)
            ->where('sucursal_id', Auth::user()->sucursal_id)
            ->where('status', 'cerrado')
            ->sum('venta_total');

            //Costo de la Quiropedia Basica
            $costo_quiropedia_basica = Servicio::where('sucursal_id', Auth::user()->sucursal_id)
            ->where('descripcion', 'Quiropedia Basica')
            ->where('rol_id', 2)
            ->where('categoria', 'principal')
            ->first()->costo;

            /**Porcentaje de comision para servicio vip para empleados */
            $porcen_vip_emp = Comision::where('aplicacion', 'servicio')
            ->where('beneficiario', 'empleado')
            ->where('sucursal_id', Auth::user()->sucursal_id)
            ->first()
            ->porcentaje;

            /**Porcentaje de comision para servicio vip para gerentes */
            $porcen_vip_gte = Comision::where('aplicacion', 'vip')
            ->where('beneficiario', 'gerente')
            ->where('sucursal_id', Auth::user()->sucursal_id)
            ->first()
            ->porcentaje;

            /**Porcentaje de comision para servicio adicionales para empleado */
            $porcen_adi_emp = Comision::where('aplicacion', 'servicio-adicional')
            ->where('beneficiario', 'empleado')
            ->where('sucursal_id', Auth::user()->sucursal_id)
            ->first()
            ->porcentaje;

            //3.- Costo total de todos los servicios asociados a la venta (solo los servicios)
            $costo_servicios = Disponible::where('cod_asignacion', $cod_asignacion)
            ->where('sucursal_id', Auth::user()->sucursal_id)
            ->where('status', 'cerrado')
            // ->get();
            // dd($costo_servicios);
            ->sum('acu_servicios');

            //Comision Empleado 10%
            $porComEmp = Comision::where('aplicacion', 'producto')
                ->where('beneficiario', 'empleado')
                ->where('sucursal_id', Auth::user()->sucursal_id)
                ->where('accion', 'directa')
                ->where('status', '1')->first()
                ->porcentaje;

            //Comision Empleado 5%
            $porComGte = Comision::where('aplicacion', 'producto')
                ->where('beneficiario', 'gerente')
                ->where('sucursal_id', Auth::user()->sucursal_id)
                ->where('accion', 'indirecta')
                ->where('status', '1')->first()
                ->porcentaje;

            //Productos asignados en la venta
            //El query traera todos los producto asociados a la venta
            $productos = DetalleAsignacion::where('cod_asignacion', $cod_asignacion)
                ->where('sucursal_id', Auth::user()->sucursal_id)
                ->where('tipo', 'producto')
                ->where('status', 2)
                ->get();

            //Informacion para obtener los id del cliente y del empleado involucrados en la venta
            $info_cliente_user = Disponible::where('cod_asignacion', $cod_asignacion)
                ->where('sucursal_id', Auth::user()->sucursal_id)
                ->where('status', 'cerrado')
                ->first();

            //Id del primer servicio asignado
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
                'servicio_id'               => $servicio_id,
                'total_venta'               => $total_venta
            ];

        } catch (\Throwable $th) {
            Notification::make()
            ->title('Notificacion: UtilsController::info()')
            ->icon('heroicon-o-shield-check')
            ->iconColor('danger')
            ->body($th->getMessage())
            ->send();
        }

    }

    /**
     * Restriccion para evitar que exitan dos servicios vip en la facturacion
     */
    static function restriccion_serv_vip($cod_asignacion)
    {

        try {

            $count_serv_vip = DetalleAsignacion::where('cod_asignacion', $cod_asignacion)
            ->where('sucursal_id', Auth::user()->sucursal_id)
            ->where('serv_asignacion', 'vip')
            ->get();

            if(count($count_serv_vip) > 1)
            {
                return true;
            }else{
                return false;
            }

            //code...
        } catch (\Throwable $th) {
            Notification::make()
            ->title('Notificacion: UtilsController::restriccion_serv_vip()')
            ->icon('heroicon-o-shield-check')
            ->iconColor('danger')
            ->body($th->getMessage())
            ->send();
        }

    }

    /**
     * Formulas para el calculo de comisiones
     * cuando son servcios VIP
     */
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
            Notification::make()
            ->title('Notificacion: UtilsController::calculo_vip()')
            ->icon('heroicon-o-shield-check')
            ->iconColor('danger')
            ->body($th->getMessage())
            ->send();
        }

    }

    static function calculo_vip_bsd($quirop_basica, $srvs_adicional, $porcen_vip_emp, $porcen_adi_emp, $porcen_vip_gte)
    {

        try {

            $tasa_bcv = TasaBcv::all()->first()->tasa;

            $porcen_quirop_basica = ($porcen_vip_emp * $quirop_basica) / 100;

            $porcen_servs_adicionales = ($porcen_adi_emp * $srvs_adicional) / 100;

            $total_servicio = $quirop_basica + $srvs_adicional;
            $porcen_gerente = ($porcen_vip_gte * $total_servicio) / 100;

            return $res = [
                'comision_total' => ($porcen_quirop_basica + $porcen_servs_adicionales) * $tasa_bcv,
                'comision_gerente' => $porcen_gerente
            ];
                //code...
        } catch (\Throwable $th) {
            Notification::make()
            ->title('Notificacion: UtilsController::calculo_vip_bsd()')
            ->icon('heroicon-o-shield-check')
            ->iconColor('danger')
            ->body($th->getMessage())
            ->send();
        }

    }

    static function calculo_vip_multiple($monto_usb, $monto_bsd, $total_venta, $quirop_basica, $srvs_adicional, $porcen_vip_emp, $porcen_adi_emp, $porcen_vip_gte)
    {

        try {

            $tasa_bcv = TasaBcv::all()->first()->tasa;

            //1.- Calculo de los porcentajes de representacion
            //% quiropedia basica
            $por_quirop_basica = ($quirop_basica * 100) / $total_venta;

            //% servicios adicionales
            $por_serv_adicionales = ($srvs_adicional * 100) / $total_venta;

            //2.- Calculo de los porcentajes sobre el monto pagado por el cliente
            //Calculo para el pago el dolares
            $calculoUno = ($por_quirop_basica * $monto_usb) / 100;
            $calculoDos = ($por_serv_adicionales * $monto_usb) / 100;

            //Calculo para el pago el bolivares
            $calculoTres = ($por_quirop_basica * $monto_bsd) / 100;
            $calculoCuatro = ($por_serv_adicionales * $monto_bsd) / 100;

            //3.- Calculo de las comisiones para cada monto pagado por el cliente
            //Calculo para el pago el dolares (40% para el empleado)
            $comision_dolares_uno = ($porcen_vip_emp * $calculoUno) / 100;

            //Calculo para el pago el dolares (10% para el empleado)
            $comision_dolares_dos = ($porcen_adi_emp * $calculoDos) / 100;

            //3.- Calculo de las comisiones para cada monto pagado por el cliente
            //Calculo para el pago el bolivares (40% para el empleado)
            $comision_bolivares_uno = ($porcen_vip_emp * $calculoTres) / 100;


            //Calculo para el pago el bolivares (10% para el empleado)
            $comision_bolivares_dos = ($porcen_adi_emp * $calculoCuatro) / 100;


            //4.- Calculo de comisones totales
            $comision_total_dolares = $comision_dolares_uno + ($comision_bolivares_uno / $tasa_bcv);
            $comision_total_bolivares = ($comision_dolares_dos / $tasa_bcv) + $comision_bolivares_dos;

            $total_servicio = $quirop_basica + $srvs_adicional;
            $comision_gerente = ($porcen_vip_gte * $total_servicio) / 100;


            return $res = [
                'comision_dolares'      => $comision_total_dolares,
                'comision_bolivares'    => $comision_total_bolivares,
                'comision_gerente'      => $comision_gerente
            ];
                //code...
        } catch (\Throwable $th) {
            Notification::make()
            ->title('Notificacion: UtilsController::calculo_vip_multiple()')
            ->icon('heroicon-o-shield-check')
            ->iconColor('danger')
            ->body($th->getMessage())
            ->send();
        }

    }

    /**
     * Formulas para el calculo de comisiones
     * cuando son servcios generales
     */
    static function calculo_general($total_servicios, $porcen_comision_emp)
    {
        try {

            $comision = ($porcen_comision_emp * $total_servicios) / 100;

            return $res = [
                'comision_total' => $comision,
                'comision_gerente' => 0.00
            ];

                //code...
        } catch (\Throwable $th) {
            Notification::make()
            ->title('Notificacion: UtilsController::calculo_general()')
            ->icon('heroicon-o-shield-check')
            ->iconColor('danger')
            ->body($th->getMessage())
            ->send();
        }

    }

    static function calculo_general_bsd($porcen_comision_emp, $costo_total_servicios)
    {
        try {

            $tasa_bcv = TasaBcv::all()->first()->tasa;

            $costo_srv_bolivares = $costo_total_servicios * $tasa_bcv;

            $comision_bolibares = ($porcen_comision_emp * $costo_srv_bolivares) / 100;

            return $res = [
                'comision_total' => $comision_bolibares,
                'comision_gerente' => 0.00
            ];

                //code...
        } catch (\Throwable $th) {
            Notification::make()
            ->title('Notificacion: UtilsController::calculo_general_bsd()')
            ->icon('heroicon-o-shield-check')
            ->iconColor('danger')
            ->body($th->getMessage())
            ->send();
        }

    }

    static function calculo_general_multiple($monto_usb, $monto_bsd, $total_venta, $porcen_vip_emp, $costo_total_servicios)
    {
        try {

            //Porcentaje de representacion del total de servicios
            $f1 = ($costo_total_servicios * 100) / $total_venta;

            $f2 = ($f1 * $monto_usb) / 100;

            $f3 = ($f1 * $monto_bsd) / 100;

            $comision_dolares = ($porcen_vip_emp * $f2) / 100;

            $comision_bolivares = ($porcen_vip_emp * $f3) / 100;

            return $res = [
                'comision_dolares'      => $comision_dolares,
                'comision_bolivares'    => $comision_bolivares,
                'comision_gerente'      => 0.00
            ];

                //code...
        } catch (\Throwable $th) {
            Notification::make()
            ->title('Notificacion: UtilsController::calculo_general_multiple()')
            ->icon('heroicon-o-shield-check')
            ->iconColor('danger')
            ->body($th->getMessage())
            ->send();
        }

    }

    static function array_servicios($cod_asignacion) {
        
        try {

            //Array de servicios
            $array_servicios = [];
            
            //Servicios realizados por el tecnico
            $servicios = DetalleAsignacion::where('cod_asignacion', $cod_asignacion)
            ->where('sucursal_id', Auth::user()->sucursal_id)
            ->where('status', 2)
            ->get();

            for($i = 0; $i < count($servicios); $i++)
            {
                $descrip_serv = Servicio::where('id', $servicios[$i]->servicio_id)->first()->descripcion;
                array_push($array_servicios, $descrip_serv);
            }

            return json_encode($array_servicios);
            
        } catch (\Throwable $th) {
            Notification::make()
            ->title('Notificacion: UtilsController::array_servicios()')
            ->icon('heroicon-o-shield-check')
            ->iconColor('danger')
            ->body($th->getMessage())
            ->send();
        }

    }

}