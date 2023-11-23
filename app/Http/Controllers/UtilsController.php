<?php

namespace App\Http\Controllers;

use App\Models\Comision;
use App\Models\Promocion;
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

    static function get_image_promocion($cod_promocion)
    {
        $image = Promocion::where('cod_promocion', $cod_promocion)->first()->image;
        return $image;
    }


}
