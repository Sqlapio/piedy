<?php

namespace App\Http\Controllers;

use App\Models\Comision;
use Illuminate\Http\Request;
use Laravel\Fortify\Http\Responses\LogoutResponse;

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

    public function destroy(Request $request)
    {
        /**
         * Lógica para colocar el usuario inactivo en base de datos
         */
 dd('aqui');
        $request->session()->invalidate();

        $request->session()->regenerateToken();

        $request->session()->flush();

        return app(LogoutResponse::class);
    }


}
