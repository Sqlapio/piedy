<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Comision;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

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


}
