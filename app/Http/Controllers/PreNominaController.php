<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Models\Gasto;
use App\Models\Compra;
use App\Models\Reporte;
use App\Models\PreNomina;
use Illuminate\Http\Request;
use App\Models\NominaGeneral;
use App\Models\VentaProducto;
use App\Models\VentaServicio;
use App\Models\AnalisisReporte;
use Spatie\LaravelPdf\Facades\Pdf;
use Spatie\Browsershot\Browsershot;
use Spatie\LaravelPdf\Enums\Format;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;

class PreNominaController extends Controller
{
    static function calculo_pre_nomina($fecha_ini, $fecha_fin, $rol_id, $sucursal_id, $cod_nomina, $nomina_general_id)
    {

        try {

            //busco todos los usuarios con el rol_id y sucursal_id
            $empleados = User::where('rol_id', $rol_id)->where('sucursal_id', $sucursal_id)->where('status', 1)->get();

            //Roles de quiropedia y manicure
            if ($rol_id == 1 || $rol_id == 2) {
                //Hacemos un foreach para calcular la nomina
                foreach ($empleados as $item) {

                    $preNomina = new PreNomina();

                    $preNomina->nomina_general_id = $nomina_general_id;
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
                    $preNomina->cod_nomina = $cod_nomina;
                    $preNomina->save();

                    //Log
                    LogController::log(Auth::user()->id, 'calculo pre-nomina', 'realizo el calculo de nomina del ' . $fecha_ini . ' al ' . $fecha_fin . ' en la sucursal: ' . $sucursal_id, $response = null);
                }
            }

            //Rol para los Gerentes de Tienda
            if ($rol_id == 3 || $rol_id == 7) {
                //Hacemos un foreach para calcular la nomina
                foreach ($empleados as $item) {

                    $preNomina = new PreNomina();

                    $preNomina->nomina_general_id = $nomina_general_id;
                    $preNomina->user_id = $item->id;
                    // dump($item->id);
                    $preNomina->rol_id = $item->rol_id;
                    $preNomina->sucursal_id = $item->sucursal_id;

                    //Total de servicio realizados
                    $preNomina->total_servicios = VentaServicio::where('responsable_id', $item->id)
                        ->where('sucursal_id', $sucursal_id)
                        ->where('comision_gerente', '!=', 0)
                        ->whereBetween('created_at', [$fecha_ini . ' 07:00:00.000', $fecha_fin . ' 23:59:59.000'])
                        ->count();
                    // dump($preNomina->total_servicios);

                    //Total productos vendidos
                    $preNomina->total_productos = VentaProducto::where('gerente_id', $item->id)
                        ->where('sucursal_id', $sucursal_id)
                        ->where('empleado_id', null)
                        ->whereBetween('created_at', [$fecha_ini . ' 07:00:00.000', $fecha_fin . ' 23:59:59.000'])
                        ->count();
                    // dump($preNomina->total_productos);

                    /**
                     * CALCULO PARA LOS SERVICIOS REALIZADOS
                     * ------------------------------------------------------------------------------------- 
                     */

                    //Comisiones en dolares (USD) de los servicios realizados
                    $preNomina->comision_usd = VentaServicio::where('responsable_id', $item->id)
                        ->where('sucursal_id', $sucursal_id)
                        ->whereBetween('created_at', [$fecha_ini . ' 07:00:00.000', $fecha_fin . ' 23:59:59.000'])
                        ->sum('comision_gerente');
                    // dump($preNomina->comision_usd);


                    //Comisiones en Bolivares (BS) de los servicios realizados
                    $preNomina->comision_bsd = 0.00;

                    /**FIN DE CALCULO PARA LOS SERVICIOS REALIZADOS------------------------------------------*/

                    /**
                     * CALCULO PARA LOS PRODUCTOS VENDIDOS
                     * --------------------------------------------------------------------------------------- 
                     */

                    //Comisiones en dolares (USD) de los productos vendidos
                    $preNomina->comision_prod = VentaProducto::where('gerente_id', $item->id)
                        ->where('sucursal_id', $sucursal_id)
                        ->whereBetween('created_at', [$fecha_ini . ' 07:00:00.000', $fecha_fin . ' 23:59:59.000'])
                        ->sum('comision_gerente');

                    /**FIN DE CALCULO PARA LOS PRODUCTOS VENDIDOS--------------------------------------------*/

                    /**
                     * CALCULO PARA LAS PROPINAS
                     * --------------------------------------------------------------------------------------- 
                     */

                    //Propinas en Dolares (USD) de los servicios realizados
                    $preNomina->propinas_usd = 0.00;

                    //Propinas en Bolivares (BS) de los servicios realizados
                    $preNomina->propinas_bsd = 0.00;

                    /**FIN DE CALCULO PARA LAS PROPINAS------------------------------------------------------*/

                    $preNomina->fecha_ini = $fecha_ini;
                    $preNomina->fecha_fin = $fecha_fin;

                    /**
                     * CALCULO PARA LOS TOTALES
                     * --------------------------------------------------------------------------------------- 
                     */

                    $preNomina->total_usd = $preNomina->comision_usd + $preNomina->comision_prod;
                    $preNomina->total_bsd = 0.00;

                    /**FIN DE CALCULO PARA LOS TOTALES-------------------------------------------------------*/
                    // dd('alto');
                    $preNomina->cod_nomina = $cod_nomina;
                    $preNomina->save();

                    //Log
                    LogController::log(Auth::user()->id, 'calculo pre-nomina', 'realizo el calculo de nomina del ' . $fecha_ini . ' al ' . $fecha_fin . ' en la sucursal: ' . $sucursal_id, $response = null);
                }
            }

            return true;
        } catch (\Throwable $th) {
            LogController::log(Auth::user()->id, 'excepcion-PreNominaController(calculo_pre_nomina)', $th->getMessage(), $response = null);
            Notification::make()
                ->title('NOTIFICACIÓN')
                ->icon('heroicon-o-shield-check')
                ->iconColor('danger')
                ->color('danger')
                ->body($th->getMessage())
                ->send();
        }
    }

    static function reporteNomina($record)
    {

        try {

            // $random = rand('111111', '999999');
            $pdf = str_replace(' ', '-', $record->user->name) . '-' . $record->cod_nomina . '.pdf';

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
                    'nro_reporte'           => $record->cod_nomina,
                    'area_trabajo'          => $record->rol->descripcion,
                    'total_mem_atendidas'   => 0,
                    'total_comi_mem_atendidas' => 0,
                ]
            )
            ->withBrowsershot(function (Browsershot $browsershot) {
                $browsershot->setNodeBinary(env('NODE')); //location of node
                $browsershot->setNpmBinary(env('NPM'));
                $browsershot->setChromePath(env('CHROMIUM'));
            })
            ->format(Format::Letter)
            ->margins(5, 0, 18, 0)
            ->footerView('pdf.footer')
            ->save($pdf);

            /**Guardo el reporte en la tabla de reportes para tener el historico */
            $reporte = new Reporte();
            $reporte->cod_nomina = $record->cod_nomina;
            $reporte->nomina_general_id = $record->nomina_general_id;
            $reporte->user_id = $record->user->id;
            $reporte->cod_reporte = $record->cod_nomina;
            $reporte->fecha_ini = $record->fecha_ini;
            $reporte->fecha_fin = $record->fecha_fin;
            $reporte->descripcion = $pdf;
            $reporte->tipo = $record->rol->descripcion;
            $reporte->responsable = Auth::user()->name;
            $reporte->sucursal_id = $record->sucursal_id;
            $reporte->save();

            if ($reporte->save()) {
                return true;
            }

            //code...
        } catch (\Throwable $th) {
            LogController::log(Auth::user()->id, 'excepcion-PreNominaController(reporteNomina)', $th->getMessage(), $response = null);
            Notification::make()
                ->title('NOTIFICACIÓN')
                ->icon('heroicon-o-shield-check')
                ->iconColor('danger')
                ->color('danger')
                ->body($th->getMessage())
                ->send();
        }
    }

    static function reporteMasivoNomina($records)
    {

        try {

            foreach ($records as $record) {

                // $random = rand('111111', '999999');
                $pdf = str_replace(' ', '-', $record->user->name) . '-' . $record->cod_nomina . '.pdf';

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
                        'nro_reporte'           => $record->cod_nomina,
                        'area_trabajo'          => $record->rol->descripcion,
                        'total_mem_atendidas'   => 0,
                        'total_comi_mem_atendidas' => 0,
                    ]
                )
                ->withBrowsershot(function (Browsershot $browsershot) {
                    $browsershot->setNodeBinary(env('NODE')); //location of node
                    $browsershot->setNpmBinary(env('NPM'));
                    $browsershot->setChromePath(env('CHROMIUM'));
                })
                ->format(Format::Letter)
                ->margins(5, 0, 18, 0)
                ->footerView('pdf.footer')
                ->save($pdf);

                /**Guardo el reporte en la tabla de reportes para tener el historico */
                $reporte = new Reporte();
                $reporte->cod_nomina = $record->cod_nomina;
                $reporte->nomina_general_id = $record->nomina_general_id;
                $reporte->user_id = $record->user->id;
                $reporte->cod_reporte = $record->cod_nomina;
                $reporte->fecha_ini = $record->fecha_ini;
                $reporte->fecha_fin = $record->fecha_fin;
                $reporte->descripcion = $pdf;
                $reporte->tipo = $record->rol->descripcion;
                $reporte->responsable = Auth::user()->name;
                $reporte->sucursal_id = $record->sucursal_id;
                $reporte->save();

                if ($reporte->save()) {
                    Notification::make()
                        ->title('NOTIFICACIÓN')
                        ->icon('heroicon-o-document-text')
                        ->iconColor('success')
                        ->color('success')
                        ->body('El reporte de: ' . $record->user->name . ' ha sido generado exitosamente')
                        ->send();
                }
            }

            //code...
        } catch (\Throwable $th) {
            LogController::log(Auth::user()->id, 'excepcion-PreNominaController(reporteMasivoNomina)', $th->getMessage(), $response = null);
            Notification::make()
                ->title('NOTIFICACIÓN')
                ->icon('heroicon-o-shield-check')
                ->iconColor('danger')
                ->color('danger')
                ->body($th->getMessage())
                ->persistent()
                ->send();
        }
    }

    static function reporteGeneral($records)
    {
        /**
         * Para ejecutar el reporte debemos validar
         * 1.- que la nomina este en estatus 8 (totalizada-cerrada)
         * 2.- en la tabla de detalle de nomina, todos los registros asociados a esta nomina deben estar en estatus 8 (totalizada-cerrada)
         */
        try {

            //Validamos 1
            $nomina = NominaGeneral::where('cod_nomina', $records->cod_nomina)->first();
            if ($nomina->status_id != 8) {
                throw new Exception("La nomina no esta en estatus totalizada-cerrada", 401);
            }

            //Validamos 2
            $detalle_nomina = PreNomina::where('cod_nomina', $records->cod_nomina)->get();
            if (count($detalle_nomina) > 0) {
                foreach ($detalle_nomina as $item) {
                    if ($item->status_id != 8) {
                        throw new Exception("Los registros asociados a esta nomina no estan en estatus totalizada-cerrada", 401);
                    }
                }
            }

            //creamos el asiento en la tabla de analisis_reportes
            $asiento = new AnalisisReporte();
            $asiento->cod_nomina = $records->cod_nomina;
            $asiento->nomina_general_id = $records->nomina_general_id;
            $asiento->sucursal_id = $records->sucursal_id;
            $asiento->fecha_calculo = date('d-m-Y');
            $asiento->fecha_ini = $records->fecha_ini;
            $asiento->fecha_fin = $records->fecha_fin;
            $asiento->gastos  = Gasto::whereBetween('created_at', [$records->fecha_ini.' 00:00:00', $records->fecha_fin.' 23:59:59'])->sum('conversion_a_usd');
            $asiento->compras = Compra::whereBetween('created_at', [$records->fecha_ini . ' 00:00:00', $records->fecha_fin . ' 23:59:59'])->sum('conversion_a_usd');
            $asiento->nomina_general_id = $records->nomina_general_id;
            $asiento->nomina_general_id = $records->nomina_general_id;
            $asiento->nomina_general_id = $records->nomina_general_id;
            $asiento->nomina_general_id = $records->nomina_general_id;
            $asiento->nomina_general_id = $records->nomina_general_id;
            $asiento->nomina_general_id = $records->nomina_general_id;
            $asiento->nomina_general_id = $records->nomina_general_id;
            $asiento->nomina_general_id = $records->nomina_general_id;
            $asiento->nomina_general_id = $records->nomina_general_id;
            
            


            //Generamos el reporte
            $reporte = new Reporte();
            $reporte->cod_nomina = $records->cod_nomina;
            $reporte->nomina_general_id = $records->nomina_general_id;
            $reporte->user_id = $records->user->id;
            $reporte->cod_reporte = $records->cod_nomina;
            $reporte->fecha_ini = $records->fecha_ini;
            $reporte->fecha_fin = $records->fecha_fin;
            $reporte->descripcion = $records->descripcion;
            $reporte->tipo = $records->rol->descripcion;
            $reporte->responsable = Auth::user()->name;
            $reporte->sucursal_id = $records->sucursal_id;
            $reporte->save();
            
            
            //code...
        } catch (\Throwable $th) {
            //throw $th;
        }
    
    }
}