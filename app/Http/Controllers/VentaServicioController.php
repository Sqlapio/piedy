<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\VentaServicio;
use Illuminate\Http\Request;

class VentaServicioController extends Controller
{
    static function venta_servicio_usd($cod_asignacion, $costo_total_servicios, $comision_total, $comision_gerente){
        dd($cod_asignacion, $costo_total_servicios, $comision_total, $comision_gerente);
        try {

            $facturar = new VentaServicio();
            $facturar->cod_asignacion           = $cod_asignacion;
            $facturar->metodo_pago              = 'Efectivo Usd';
            $facturar->total_USD                = $costo_total_servicios;
            $facturar->comision_dolares         = $comision_total;
            $facturar->comision_gerente         = $comision_gerente;
            // $facturar->save();

        } catch (\Throwable $th) {
            dd($th);
        }

    }
}
