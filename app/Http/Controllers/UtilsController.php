<?php

namespace App\Http\Controllers;

use App\Models\Comision;
use App\Models\TasaBcv;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

    static function sincronizacion($tabla)
    {
        if($tabla = 'tasa_bcvs')
        {
            $tasa_bcvs = TasaBcv::first();
            /** Actualizo DB online */
            DB::connection('mysql_online')->table('tasa_bcvs')
            ->where('id', 1)
                ->update([
                    'tasa'  => $tasa_bcvs->tasa,
                    'fecha' => $tasa_bcvs->fecha
                ]);
        }

        if($tabla = 'clientes')
        {
            dd('clientes');
            $tasa_bcvs = TasaBcv::first();
            /** Actualizo DB online */
            DB::connection('mysql_online')->table('tasa_bcvs')
            ->where('id', 1)
                ->update([
                    'tasa'  => $tasa_bcvs->tasa,
                    'fecha' => $tasa_bcvs->fecha
                ]);
        }
    }




}
