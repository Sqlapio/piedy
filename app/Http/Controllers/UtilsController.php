<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Comision;
use App\Models\Servicio;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
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

    static function cal_comision_empleado($valorUno, $valorDos, $tipoSrv, $total_vista)
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

            if($tipoSrv != 'vip'){
                /**Calculo de la comision en dolares */
                $comision_usd = (floatval($valorUno) * $porcentaje) / 100;

                /**Calculo de la comision en bolivares */
                $comision_bsd = (floatval(Str::replace(',', '.', (Str::replace('.', '', $valorDos)))) * $porcentaje) / 100;

                /**Array de comisiones */
                $array_comisiones = [$comision_usd,$comision_bsd];

                return $array_comisiones;
            }

            if($tipoSrv == 'vip'){

                /**1.- Busco el valor de la quiropedia basica */
                $quiroBasica = Servicio::where('descripcion', 'Quiropedia Basica')->first()->costo;

                /**Calculo la resta entre el total de la vista(el costo del servicio) - el costo de la quiropedia basica
                 * para obtener el costo de los productos adicionales
                 */
                $costoProAdi = $total_vista - $quiroBasica;

                /**Como el pago se puede realizar de dos formas, las porciones del pago seran tratadas por porcentaje */


                /**Calculo de la comision en dolares */
                $comision_usd = (floatval($valorUno) * $porcentaje) / 100;

                /**Calculo de la comision en bolivares */
                $comision_bsd = (floatval(Str::replace(',', '.', (Str::replace('.', '', $valorDos)))) * $porcentaje) / 100;

                /**Array de comisiones */
                $array_comisiones = [$comision_usd,$comision_bsd];

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


}
