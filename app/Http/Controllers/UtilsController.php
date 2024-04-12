<?php

namespace App\Http\Controllers;

use App\Models\Comision;
use App\Models\Servicio;
use App\Models\TasaBcv;
use Illuminate\Http\Request;

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

    static function cal_comision_empleado($total_venta)
    {
        $porcentaje = Comision::where('aplicacion', 'servicio')
        ->where('beneficiario', 'empleado')
        ->first()
        ->porcentaje;

        $calculo = ($total_venta * $porcentaje) / 100;

        return $calculo;
    }

    static function comision_empleado_srvvip($total_venta)
    {
        /**Optengo el costo de la quiropedia basica */
        $costo = Servicio::where('cod_servicio', 'Sco-21760')->first()->costo;

        /**Calculo la comision del empleado 40% */
        $comision_1 = Comision::where('aplicacion', 'servicio')->where('beneficiario', 'empleado')->first()->porcentaje;
        $porcentaje_qiro_basica = ($comision_1 * $costo) / 100;

        /**Calculo la comision adicional del empleado 10%  */
        $comision_2 = Comision::where('aplicacion', 'servicio_vip')->where('beneficiario', 'empleado')->first()->porcentaje;
        $restante = ($comision_2 * ($total_venta - $costo)) / 100;

        $comi_emp_vip = $porcentaje_qiro_basica + $restante;

        return $comi_emp_vip;

    }

    static function comision_gerente_srvvip($total_venta)
    {

        /**Optengo el costo de la quiropedia basica */
        $costo = Servicio::where('cod_servicio', 'Sco-21760')->first()->costo;

        /**Calculo la comision del gerente 10% */
        $comision = Comision::where('aplicacion', 'servicio_vip')->where('beneficiario', 'gerente')->first()->porcentaje;
        $restante = ($comision * ($total_venta - $costo)) / 100;

        $comi_gte = $restante;

        return $comi_gte;

    }

    static function comision_empleado_srvvip_bs($_costosrv)
    {
        $tasa_bcv = TasaBcv::where('id', 1)->first()->tasa;

        /**Optengo el costo de la quiropedia basica */
        $costo = Servicio::where('cod_servicio', 'Sco-21760')->first()->costo;

        // $costo_bs = $costo * $tasa_bcv;

        /**Calculo la comision del empleado 40% */
        $comision_1 = Comision::where('aplicacion', 'servicio')->where('beneficiario', 'empleado')->first()->porcentaje;
        $porcentaje_qiro_basica = ($comision_1 * $costo) / 100;
        $total_comi1_bs = $porcentaje_qiro_basica * $tasa_bcv;

        /**Calculo la comision adicional del empleado 10%  */
        $comision_2 = Comision::where('aplicacion', 'servicio_vip')->where('beneficiario', 'empleado')->first()->porcentaje;
        $restante = ($comision_2 * ($_costosrv - $costo)) / 100;
        $total_comi2_bs = $restante * $tasa_bcv;

        $comi_emp_vip = $total_comi1_bs + $total_comi2_bs;

        return $comi_emp_vip;

    }

    static function comision_gerente_srvvip_bs($_costosrv)
    {
        $tasa_bcv = TasaBcv::where('id', 1)->first()->tasa;

        /**Optengo el costo de la quiropedia basica */
        $costo = Servicio::where('cod_servicio', 'Sco-21760')->first()->costo;

        /**Calculo la comision del gerente 10% */
        $comision = Comision::where('aplicacion', 'servicio_vip')->where('beneficiario', 'gerente')->first()->porcentaje;
        $restante = ($comision * ($_costosrv - $costo)) / 100;
        $comi_gte = $restante * $tasa_bcv;

        return $comi_gte;

    }


}
