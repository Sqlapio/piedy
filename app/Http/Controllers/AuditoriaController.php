<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Auditoria;
use App\Models\Consumible;
use App\Models\Inventario;
use Illuminate\Http\Request;
use App\Models\InventarioSucursal;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AuditoriaController extends Controller
{
    //
    public static function crear_asiento($fecha_ini, $fecha_fin, $cod_nomina, $sucursal_id) {

        try {

            $cod_auditoria = 'AUD-' . rand(11111, 99999);

            //Seleccionamos los productos para ser auditados
            //Son los que estan ubicados en la tabla de asignar_productos
            $productos = DB::table('asignar_productos')
                ->select('producto_id as producto_id', DB::raw('sum(servicios_facturados) as cantidad_servicios'))
                ->whereBetween('created_at', [$fecha_ini.' 00:00:00', $fecha_fin.' 23:59:59'])
                ->where('sucursal_id', $sucursal_id)
                ->groupBy('producto_id')
                ->get();

            // dd($productos, count($productos));

            for ($i = 0; $i < count($productos); $i++) {

                $producto = Producto::find($productos[$i]->producto_id);
                // dd($producto);

                //Calculamos el cantidad general de solicitud del producto en la tabla de detalle requisiciones
                $cantidad_solicitada = DB::table('detalle_requisicions')
                    ->select(DB::raw('sum(cantidad) as cantidad'), DB::raw('sum(sub_total) as sub_total_gasto'))
                    ->whereBetween('created_at', [$fecha_ini . ' 00:00:00', $fecha_fin . ' 23:59:59'])
                    ->where('producto_id', $productos[$i]->producto_id)
                    ->get()
                    ->toArray();


                $existencia_sucursal    = InventarioSucursal::where('sucursal_id', $sucursal_id)->where('producto_id', $productos[$i]->producto_id)->first();
                $existencia_central     = Inventario::where('producto_id', $productos[$i]->producto_id)->first()->cantidad;

                $cantidad_solicitada_req    = $cantidad_solicitada[0]->cantidad == null ? 0 : $cantidad_solicitada[0]->cantidad;
                $gasto_total_usd            = $cantidad_solicitada[0]->sub_total_gasto == null ? 0 : $cantidad_solicitada[0]->sub_total_gasto;

                // $producto_consumible = Consumible::where('producto_id', $productos[$i]->producto_id)->first();

                // if ($producto_consumible == null) {
                //     $producto_consumible = 0;
                // }

                //creamos el asiento en la tabla de auditoria
                $asiento = new Auditoria();
                $asiento->cod_auditoria         = $cod_auditoria;
                $asiento->cod_nomina            = $cod_nomina;
                $asiento->fecha_ini             = $fecha_ini;
                $asiento->fecha_fin             = $fecha_fin;
                $asiento->sucursal_id           = $sucursal_id;
                $asiento->producto_id           = $productos[$i]->producto_id;
                $asiento->contenido_neto        = $producto->contenido_neto;
                $asiento->unidad                = $producto->unidad;
                $asiento->cantidad_solicitada   = $cantidad_solicitada_req;
                $asiento->gasto_total_usd       = $gasto_total_usd;
                $asiento->consumo_por_servicios = $producto->uso_promedio_serv;
                $asiento->servicios_realizados  = $productos[$i]->cantidad_servicios;
                $asiento->existencia_sucursal   = $existencia_sucursal;
                $asiento->existencia_central    = $existencia_central;
                $asiento->responsable           = Auth::user()->name;
                $asiento->save();
            }

            return true;
            
        } catch (\Throwable $th) {
            dd($th);
        }

    }
}