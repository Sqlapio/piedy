<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\ClienteOnline;
use App\Models\Comision;
use App\Models\DetalleAsignacion;
use App\Models\DetalleAsignacionOnline;
use App\Models\Disponible;
use App\Models\DisponibleOnline;
use App\Models\FacturaMultiple;
use App\Models\FacturaMultipleOnline;
use App\Models\Gasto;
use App\Models\GastoOnline;
use App\Models\TasaBcv;
use App\Models\User;
use App\Models\UserOnLine;
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

        if($tabla = 'clientes')
        {
            /** BD LOCAL */
            $clientes = Cliente::where('sincronizado', 'false')->get();

            /** DB AWS */
            foreach($clientes as $item)
            {
                /** Obtengo el ultimo registro para evitar fallas al
                 * registrar la informacion y mantener el correlativo
                 * de registros intacto
                 */
                $id = DB::connection('mysql_online')
                    ->table('clientes')
                    ->orderBy('id', 'desc')->first();

                $cliente_online = new ClienteOnline();
                $cliente_online->id          = $id->id + 1;
                $cliente_online->nombre      = strtoupper($item->nombre);
                $cliente_online->apellido    = strtoupper($item->apellido);
                $cliente_online->email       = $item->email;
                $cliente_online->telefono    = $item->telefono;
                $cliente_online->user_id     = $item->user_id;
                $cliente_online->responsable = $item->responsable;
                $cliente_online->created_at  = $item->created_at;
                $cliente_online->updated_at  = $item->updated_at;
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
                $venta_servicio_online->metodo_pago        = $item->metodo_pago;
                $venta_servicio_online->referencia         = $item->referencia;
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

        if($tabla = 'factura_multiples')
        {
            /** BD LOCAL */
            $factura_multiples = FacturaMultiple::where('sincronizado', 'false')->get();

            /** DB AWS */
            foreach($factura_multiples as $item)
            {
                /** Obtengo el ultimo registro para evitar fallas al
                 * registrar la informacion y mantener el correlativo
                 * de registros intacto
                 */
                $id = DB::connection('mysql_online')
                    ->table('factura_multiples')
                    ->orderBy('id', 'desc')->first();

                $factura_multiples_online = new FacturaMultipleOnline();
                $factura_multiples_online->id                 = $id == null ? 1 : $id->id + 1;
                $factura_multiples_online->cod_asignacion     = $item->cod_asignacion;
                $factura_multiples_online->responsable        = $item->responsable;
                $factura_multiples_online->metodo_pago        = $item->metodo_pago;
                $factura_multiples_online->referencia         = $item->referencia;
                $factura_multiples_online->fecha_venta        = $item->fecha_venta;
                $factura_multiples_online->total_usd          = $item->total_USD;
                $factura_multiples_online->pago_usd           = $item->pago_usd;
                $factura_multiples_online->pago_bsd           = $item->pago_bsd;
                $factura_multiples_online->created_at         = $item->created_at;
                $factura_multiples_online->updated_at         = $item->updated_at;
                $factura_multiples_online->save();

                /** Actualizo el valor sincronizado a true en DB LOCAL */
                FacturaMultiple::where('id', $item->id)->update([
                    'sincronizado' => 'true'
                ]);

            }
        }

        if($tabla = 'gastos')
        {
            /** BD LOCAL */
            $gastos = Gasto::where('sincronizado', 'false')->get();

            /** DB AWS */
            foreach($gastos as $item)
            {
                /** Obtengo el ultimo registro para evitar fallas al
                 * registrar la informacion y mantener el correlativo
                 * de registros intacto
                 */
                $id = DB::connection('mysql_online')
                    ->table('gastos')
                    ->orderBy('id', 'desc')->first();

                $gastos_online = new GastoOnline();
                $gastos_online->id           = $id->id + 1;
                $gastos_online->descripcion  = $item->descripcion;
                $gastos_online->monto_usd    = $item->monto_usd;
                $gastos_online->monto_bsd    = $item->monto_bsd;
                $gastos_online->forma_pago   = $item->forma_pago;
                $gastos_online->referencia   = $item->referencia;
                $gastos_online->fecha        = $item->fecha;
                $gastos_online->responsable  = $item->responsable;
                $gastos_online->created_at   = $item->created_at;
                $gastos_online->updated_at   = $item->updated_at;
                $gastos_online->save();

                /** Actualizo el valor sincronizado a true en DB LOCAL */
                Gasto::where('id', $item->id)->update([
                    'sincronizado' => 'true'
                ]);

            }
        }

        if($tabla = 'users')
        {
            $users_online = DB::connection('mysql_online')->table('users')->where('sincronizado', 'false')->get();

            /** DB AWS */
            foreach($users_online as $item)
            {
                $user = new User();
                $user->name             = $item->name;
                $user->tipo_servicio_id = $item->tipo_servicio_id;
                $user->email            = $item->email;
                $user->telefono         = $item->telefono;
                $user->password         = $item->password;
                $user->tipo_usuario     = $item->tipo_usuario;
                $user->area_trabajo     = $item->area_trabajo;
                $user->sincronizado     = 'true';
                $user->created_at       = $item->created_at;
                $user->updated_at       = $item->updated_at;
                $user->save();

                /** Actualizo el valor sincronizado a true en DB LOCAL */
                DB::connection('mysql_online')->table('users')->where('id', $item->id)
                ->update([
                    'sincronizado' => 'true'
                ]);

            }
        }

        if($tabla = 'comisions')
        {
            $users_online = DB::connection('mysql_online')->table('users')->where('sincronizado', 'false')->get();

            /** DB AWS */
            foreach($users_online as $item)
            {
                $user = new User();
                $user->name             = $item->name;
                $user->tipo_servicio_id = $item->tipo_servicio_id;
                $user->email            = $item->email;
                $user->telefono         = $item->telefono;
                $user->password         = $item->password;
                $user->tipo_usuario     = $item->tipo_usuario;
                $user->area_trabajo     = $item->area_trabajo;
                $user->sincronizado     = 'true';
                $user->created_at       = $item->created_at;
                $user->updated_at       = $item->updated_at;
                $user->save();

                /** Actualizo el valor sincronizado a true en DB LOCAL */
                DB::connection('mysql_online')->table('users')->where('id', $item->id)
                ->update([
                    'sincronizado' => 'true'
                ]);

            }
        }
    }




}
