<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\ClienteOnline;
use App\Models\Comision;
use App\Models\DetalleAsignacion;
use App\Models\DetalleAsignacionOnline;
use App\Models\Disponible;
use App\Models\DisponibleOnline;
use App\Models\TasaBcv;
use App\Models\VentaServicio;
use App\Models\VentaServicioOnline;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
            /** BD LOCAL */
            $tasa_bcvs = TasaBcv::first();

            /** DB AWS */
            DB::connection('mysql_online')->table('tasa_bcvs')
            ->where('id', 1)
                ->update([
                    'tasa'  => $tasa_bcvs->tasa,
                    'fecha' => $tasa_bcvs->fecha
                ]);
        }

        if($tabla = 'clientes')
        {
            /** BD LOCAL */
            $clientes = Cliente::where('sincronizado', 'false')->get();

            /** DB AWS */
            foreach($clientes as $item)
            {
                $cliente_online = new ClienteOnline();
                $cliente_online->nombre      = strtoupper($item->nombre);
                $cliente_online->apellido    = strtoupper($item->apellido);
                $cliente_online->email       = $item->email;
                $cliente_online->telefono    = $item->telefono;
                $cliente_online->user_id     = $item->responsable;
                $cliente_online->responsable = $item->responsable;
                $cliente_online->created_at = $item->created_at;
                $cliente_online->updated_at = $item->updated_at;
                $cliente_online->save();

                /** Actualizo el valor sincronizado a true en DB LOCAL */
                Cliente::where('id', $item->id)->update([
                    'sincronizado' => 'true'
                ]);

            }
        }

        if($tabla = 'venta_servicios')
        {
            /** BD LOCAL */
            $venta_servicios = VentaServicio::where('sincronizado', 'false')->where('facturado','true')->get();

            /** DB AWS */
            foreach($venta_servicios as $item)
            {
                /** Obtengo el ultimo registro para evitar fallas al
                 * registrar la informacion y mantener el correlativo
                 * de registros intacto
                 */
                $id = DB::connection('mysql_online')
                    ->table('venta_servicios')
                    ->orderBy('id', 'desc')->first();

                $venta_servicio_online = new VentaServicioOnline();
                $venta_servicio_online->id                 = $id->id + 1;
                $venta_servicio_online->cod_asignacion     = $item->cod_asignacion;
                $venta_servicio_online->cliente            = $item->cliente;
                $venta_servicio_online->cliente_id         = $item->cliente_id;
                $venta_servicio_online->empleado           = $item->empleado;
                $venta_servicio_online->empleado_id        = $item->empleado_id;
                $venta_servicio_online->fecha_venta        = $item->fecha_venta;
                $venta_servicio_online->total_USD          = $item->total_USD;
                $venta_servicio_online->pago_usd           = $item->pago_usd;
                $venta_servicio_online->pago_bsd           = $item->pago_bsd;
                $venta_servicio_online->propina_usd        = $item->propina_usd;
                $venta_servicio_online->propina_bsd        = $item->propina_bsd;
                $venta_servicio_online->comision_empleado  = $item->comision_empleado;
                $venta_servicio_online->comision_gerente   = $item->comision_gerente;
                $venta_servicio_online->created_at         = $item->created_at;
                $venta_servicio_online->updated_at         = $item->updated_at;
                $venta_servicio_online->responsable        = Auth::user()->name;
                $venta_servicio_online->save();

                /** Actualizo el valor sincronizado a true en DB LOCAL */
                VentaServicio::where('id', $item->id)->update([
                    'sincronizado' => 'true'
                ]);

            }
        }
    }




}
