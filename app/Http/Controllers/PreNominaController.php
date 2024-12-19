<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\PreNomina;
use Illuminate\Http\Request;
use App\Models\VentaProducto;
use App\Models\VentaServicio;
use Illuminate\Support\Facades\Auth;
use Spatie\LaravelPdf\Facades\Pdf;
use Spatie\LaravelPdf\Enums\Format;
use Spatie\Browsershot\Browsershot;

class PreNominaController extends Controller
{
    static function calculo_pre_nomina($fecha_ini, $fecha_fin, $rol_id, $sucursal_id) {
        // dump($fecha_ini, $fecha_fin, $rol_id, $sucursal_id, $fecha_ini . ' 07:00:00.000', $fecha_fin . ' 23:59:59.000');

        //busco todos los usuarios con el rol_id y sucursal_id
        $empleados = User::where('rol_id', $rol_id)->where('sucursal_id', $sucursal_id)->where('status', 1)->get();
        // dump($empleados);

        //Hacemos un foreach para calcular la nomina
        foreach ($empleados as $item) {

            $preNomina = new PreNomina();

            $preNomina->user_id = $item->id;
            // dump($item->id);
            $preNomina->rol_id = $item->rol_id;
            $preNomina->sucursal_id = $item->sucursal_id;

            //Total de servicio realizados
            $preNomina->total_servicios = VentaServicio::where('empleado_id', $item->id)
            ->where('sucursal_id', $sucursal_id)
            ->whereBetween('created_at', [$fecha_ini . ' 07:00:00.000', $fecha_fin . ' 23:59:59.000'])
            ->count();
            // dump($preNomina->total_servicios);

            //Total productos vendidos
            $preNomina->total_productos = VentaProducto::where('empleado_id', $item->id)
            ->where('sucursal_id', $sucursal_id)
            ->whereBetween('created_at', [$fecha_ini . ' 07:00:00.000', $fecha_fin . ' 23:59:59.000'])
            ->count();
            // dump($preNomina->total_productos);

            /**
             * CALCULO PARA LOS SERVICIOS REALIZADOS
             * ------------------------------------------------------------------------------------- 
             */
            
            //Comisiones en dolares (USD) de los servicios realizados
            $preNomina->comision_usd = VentaServicio::where('empleado_id', $item->id)
            ->where('sucursal_id', $sucursal_id)
            ->whereBetween('created_at', [$fecha_ini . ' 07:00:00.000', $fecha_fin . ' 23:59:59.000'])
            ->sum('comision_dolares');
            // dump($preNomina->comision_usd);
            

            //Comisiones en Bolivares (BS) de los servicios realizados
            $preNomina->comision_bsd = VentaServicio::where('empleado_id', $item->id)
            ->where('sucursal_id', $sucursal_id)
            ->whereBetween('created_at', [$fecha_ini . ' 07:00:00.000', $fecha_fin . ' 23:59:59.000'])
            ->sum('comision_bolivares');
            
            /**FIN DE CALCULO PARA LOS SERVICIOS REALIZADOS------------------------------------------*/

            /**
             * CALCULO PARA LOS PRODUCTOS VENDIDOS
             * --------------------------------------------------------------------------------------- 
             */
            
            //Comisiones en dolares (USD) de los productos vendidos
            $preNomina->comision_prod = VentaProducto::where('empleado_id', $item->id)
            ->where('sucursal_id', $sucursal_id)
            ->whereBetween('created_at', [$fecha_ini . ' 07:00:00.000', $fecha_fin . ' 23:59:59.000'])
            ->sum('comision_empleado');
            
            /**FIN DE CALCULO PARA LOS PRODUCTOS VENDIDOS--------------------------------------------*/

            /**
             * CALCULO PARA LAS PROPINAS
             * --------------------------------------------------------------------------------------- 
             */

            //Propinas en Dolares (USD) de los servicios realizados
            $preNomina->propinas_usd = VentaServicio::where('empleado_id', $item->id)
            ->where('sucursal_id', $sucursal_id)
            ->whereBetween('created_at', [$fecha_ini . ' 07:00:00.000', $fecha_fin . ' 23:59:59.000'])
            ->sum('propina_usd');

            //Propinas en Bolivares (BS) de los servicios realizados
            $preNomina->propinas_bsd = VentaServicio::where('empleado_id', $item->id)
            ->where('sucursal_id', $sucursal_id)
            ->whereBetween('created_at', [$fecha_ini . ' 07:00:00.000', $fecha_fin . ' 23:59:59.000'])
            ->sum('propina_bsd');

            /**FIN DE CALCULO PARA LAS PROPINAS------------------------------------------------------*/

            $preNomina->fecha_ini = $fecha_ini;
            $preNomina->fecha_fin = $fecha_fin;

            /**
             * CALCULO PARA LOS TOTALES
             * --------------------------------------------------------------------------------------- 
             */
            
            $preNomina->total_usd = $preNomina->comision_usd + $preNomina->comision_prod + $preNomina->propinas_usd;
            $preNomina->total_bsd = $preNomina->comision_bsd + $preNomina->propinas_bsd;

            /**FIN DE CALCULO PARA LOS TOTALES-------------------------------------------------------*/
            // dd('alto');
            $preNomina->save();
        }

        LogController::log(Auth::user()->id, 'calculo pre-nomina', 'realizo el pre calculo de nomina del ' . $fecha_ini . ' al ' . $fecha_fin . ' en la sucursal: ' . $sucursal_id, $response = null);
    }

    static function reporteNomina($record) {
        
        try {
            // dd($record->user->name);
            $random = rand('11111', '99999');
            $pdf = 'prueba.pdf';


            pdf::view(
                'pdf.reporte',
                [
                    'cedula'                => $record->user->cedula,
                    'rango'                 => $record->fecha_ini . ' ' . $record->fecha_fin,
                    'nombre'                => $record->user->name,
                    'total_servicios'       => $record->total_servicios,
                    'total_productos'       => $record->total_productos,
                    'total_comi_ventaprod'  => $record->comision_prod,
                    'propinas_bsd'          => $record->propinas_bsd,
                    'propinas_usd'          => $record->propinas_usd,
                    'comision_bsd'          => $record->comision_bsd,
                    'comision_usd'          => $record->comision_usd,
                    'pro_dura_servicios'    => '0.00',
                    'total_dolares'         => $record->comision_usd + $record->comision_prod + $record->propinas_usd,
                    'dias_trabajados'       => '10',
                    'total_bolivares'       => $record->comision_bsd + $record->propinas_bsd,
                    'nro_reporte'           => $random,
                    'area_trabajo'          => $record->rol->descripcion,
                    'total_mem_atendidas'   => 0,
                    'total_comi_mem_atendidas' => 0,
                ]
            )
                // ->withBrowsershot(function (Browsershot $browsershot) {
                //     // $browsershot->setNodeBinary(env('NODE')); //location of node
                //     // $browsershot->setNpmBinary(env('NPM'));
                //     // $browsershot->setChromePath(env('CHROMIUM'));
                // })
                ->format(Format::Letter)
                ->margins(5, 0, 18, 0)
                ->footerView('pdf.footer')
                ->save($pdf);
            //code...
        } catch (\Throwable $th) {
            dd($th);
        }
        
    }
}