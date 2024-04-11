<?php

namespace App\Http\Controllers;

use App\Models\Comision;
use App\Models\Servicio;
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

        $comision_1 = Comision::where('aplicacion', 'servicio')->where('beneficiario', 'empleado')->first()->porcentaje;
        $porcentaje_qiro_basica = ($comision_1 * $costo) / 100;

        $comision_2 = Comision::where('aplicacion', 'servicio_vip')->where('beneficiario', 'empleado')->first()->porcentaje;
        $restante = ($comision_2 * ($total_venta - $costo)) / 100;

        $comi_emp_vip = $porcentaje_qiro_basica + $restante;

        return $comi_emp_vip;

    }

    static function comision_gerente_srvvip($total_venta)
    {
        /**Optengo el costo de la quiropedia basica */
        $costo = Servicio::where('cod_servicio', 'Sco-21760')->first()->costo;

        $comision = Comision::where('aplicacion', 'servicio_vip')->where('beneficiario', 'gerente')->first()->porcentaje;
        $restante = ($comision * ($total_venta - $costo)) / 100;

        $comi_gte = $restante;

        return $comi_gte;

    }


}
