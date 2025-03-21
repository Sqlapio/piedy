<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Cliente;
use App\Models\TasaBcv;
use App\Models\Disponible;
use App\Models\Frecuencia;
use Illuminate\Http\Request;
use App\Models\VentaProducto;
use App\Models\VentaServicio;
use App\Models\DetalleAsignacion;
use App\Models\ConfiguracionNomina;
use Barryvdh\Debugbar\Facades\Debugbar;

class StatController extends Controller
{
    /**
     * Grupo de funcion para sl calculo de los stat de servicios
     * ----------------------------------------------------------
     */
    static function servicios_facturados($start, $end, $sucursal_id)
    {
        // dd($start, $end);
        try {

            //code...
            
                $rangeStartDate = $start == null ? now()->startOfDay() : $start;
                $rangeEndDate = $end == null ? now()->endOfDay() : $end;

                // dd($rangeStartDate, $rangeEndDate);
                $servicios_hoy = DetalleAsignacion::whereBetween('created_at', [$rangeStartDate, $rangeEndDate])
                ->where('status', 2)
                ->where('tipo', 'servicio')
                ->count();
                // dd($servicios_hoy);

                return $result = [
                    'servicios_hoy' => $servicios_hoy,
                ];

        } catch (\Throwable $th) {
            dd($th);
        }
    }

    static function total_servicios_usd($start, $end, $sucursal_id)
    {
        try {

                $tasa = TasaBcv::all()->first()->tasa;
                $porcen_depreciacion = ConfiguracionNomina::all()->first()->porcen_depreciacion;

                $rangeStartDate = $start == null ? now()->startOfDay() : $start;
                $rangeEndDate = $end == null ? now()->endOfDay() : $end;
                $total_hoy_usd = VentaServicio::whereBetween('created_at', [$rangeStartDate, $rangeEndDate])->sum('pago_usd');
                $total_hoy_bsd = VentaServicio::whereBetween('created_at', [$rangeStartDate, $rangeEndDate])->sum('pago_bsd');
                

                //CALCULO DE LA DEPRECIACION
                //--------------------------------------------------------------------------------
                $depreciacion_bsd = ($total_hoy_bsd * $porcen_depreciacion) / 100;
                // Debugbar::info($depreciacion_bsd);

                $depreciacion_a_usd = $depreciacion_bsd / $tasa;
                // Debugbar::info($depreciacion_a_usd);

                $total_hoy = $total_hoy_usd + $depreciacion_a_usd;
                //--------------------------------------------------------------------------------


                //SERVICIOS FATURADOS ANUAL HASTA LA FECHA ACTUAL
                //-------------------------------------------------------------------------------------------------
                $rangeStartDate = now()->subMonth()->startOfYear();
                $rangeEndDate = now()->subMonth()->endOfYear();
                $total_anual_usd = VentaServicio::whereBetween('created_at', [$rangeStartDate, $rangeEndDate])->sum('pago_usd');
                $total_anual_bsd = VentaServicio::whereBetween('created_at', [$rangeStartDate, $rangeEndDate])->sum('pago_bsd');

                $depreciacion_anual_bsd = ($total_anual_bsd * $porcen_depreciacion) / 100;
                // Debugbar::info($depreciacion_anual_bsd);

                $depreciacion_anual_a_usd = $depreciacion_anual_bsd / $tasa;
                // Debugbar::info($depreciacion_anual_a_usd);

                $total_anual = $total_anual_usd + $depreciacion_anual_a_usd;
                // Debugbar::info($total_anual_usd, $total_anual_bsd);
                //-------------------------------------------------------------------------------------------------


                //CALCULO DEL PROMEDIO DE SERVICIO HASTA EL DIA ACTUAL
                //-------------------------------------------------------------------------------------------------
                $porcentaje = $total_hoy * 100 / $total_anual;
                if ($porcentaje > 50) {
                    $icon   = 'heroicon-m-arrow-trending-up';
                    $color = 'success';
                }
                if ($porcentaje < 50) {
                    $color = 'danger';
                    $icon = 'heroicon-m-arrow-trending-down';
                }
                // Debugbar::info($porcentaje, $promedio_hoy);
                //--------------------------------------------------------------------------------------------------

                return $result = [
                    'total_hoy' => $total_hoy,
                    'porcentaje' => $porcentaje,
                    'icon' => $icon,
                    'color' => $color,
                    // 'letra' => $letra
                ];


        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    static function promedio_servicio_cliente($start, $end, $sucursal_id)
    {
        try {

            $rangeStartDate = $start == null ? now()->startOfDay() : $start;
            $rangeEndDate = $end == null ? now()->endOfDay() : $end;

            //Servicios y clientes para hoy
            $nro_servicios_hoy  = DetalleAsignacion::whereBetween('created_at', [$rangeStartDate, $rangeEndDate])
            ->where('status', 2)
            ->where('tipo', 'servicio')
            ->count();

            $clientes_hoy = VentaServicio::whereBetween('created_at', [$rangeStartDate, $rangeEndDate])->groupBy('cliente_id')->get();
            $clientes_hoy = count($clientes_hoy);

            //SERVICIOS y CLIENTES FATURADOS DEL AÑO EN CURSO
            //-------------------------------------------------------------------------------------------------
            $rangeStartDate = now()->subMonth()->startOfYear();
            $rangeEndDate = now()->subMonth()->endOfYear();
            $nro_servicios_anual = DetalleAsignacion::whereBetween('created_at', [$rangeStartDate, $rangeEndDate])
                ->where('status', 2)
                ->where('tipo', 'servicio')
                ->count();
            $clientes_anual = count(VentaServicio::whereBetween('created_at', [$rangeStartDate, $rangeEndDate])->groupBy('cliente_id')->get());
            // $clientes_anual = count($clientes_hoy);
            Debugbar::info($clientes_anual, $nro_servicios_anual);
            //--------------------------------------------------------------------------------------------------

            if ($clientes_hoy == 0) {
                $promedio_hoy = 0;
            } else {

                $promedio_hoy = $nro_servicios_hoy / $clientes_hoy;
                $promedio_anual = $nro_servicios_anual / $clientes_anual;

                //CALCULO DEL PROMEDIO DE SERVICIO HASTA EL DIA ACTUAL
                //-------------------------------------------------------------------------------------------------
                if ($promedio_anual < 1) {
                    $porcentaje = $promedio_hoy * 100 / $promedio_anual;
                    if ($promedio_hoy > 50) {
                        $icon   = 'heroicon-m-arrow-trending-up';
                        $color = 'success';
                    }
                    if ($promedio_hoy < 50) {
                        $color = 'danger';
                        $icon = 'heroicon-m-arrow-trending-down';
                    }
                } else {
                    $porcentaje = 0;
                    $icon   = 'heroicon-s-shield-exclamation';
                    $color = 'warning';
                }
                //--------------------------------------------------------------------------------------------------
            }

            // Debugbar::info($promedio_hoy, $promedio_anual);

           
            
            

            return $result = [
                'promedio_hoy' => $promedio_hoy,
                'porcentaje' => $porcentaje ?? 0,
                'icon' => $icon ?? 'heroicon-s-shield-exclamation',
                'color' => $color ?? 'warning',
            ];


        } catch (\Throwable $th) {
            dd($th);
        }
    }

    static function promedio_servicio_anual($start, $end, $sucursal_id)
    {
        try {

            //SERVICIOS FATURADOS ANUAL HASTA LA FECHA ACTUAL
            //-------------------------------------------------------------------------------------------------
            $rangeStartDate = now()->subMonth()->startOfYear();
            $rangeEndDate = now()->subMonth()->endOfYear();
            $servicios_anual = DetalleAsignacion::whereBetween('created_at', [$rangeStartDate, $rangeEndDate])
                ->where('status', 2)
                ->where('tipo', 'servicio')
                ->count();
            // Debugbar::info($servicios_anual);
            //-------------------------------------------------------------------------------------------------
            

            //NUMERO DE DIAS TRANSCURRIDOS
            //-----------------------------------------------------------------------------------------
            $total_dias_transcurridos =  Carbon::now()->diffInDays(now()->subMonth()->startOfYear());
            // Debugbar::info($total_dias_transcurridos);
            //-----------------------------------------------------------------------------------------
            

            //SERVICIOS FATURADOS DEL DIA EN CURSO
            //-------------------------------------------------------------------------------------------------
            $rangeStartDate = $start == null ? now()->startOfDay() : $start;
            $rangeEndDate   = $end == null ? now()->endOfDay() : $end;
            $servicios_hoy = DetalleAsignacion::whereBetween('created_at', [$rangeStartDate, $rangeEndDate])
                ->where('status', 2)
                ->where('tipo', 'servicio')
                ->count();
            // Debugbar::info($servicios_hoy);
            //--------------------------------------------------------------------------------------------------


            //CALCULO DEL PROMEDIO DE SERVICIO HASTA EL DIA ACTUAL
            //-------------------------------------------------------------------------------------------------
            $promedio_hoy = $servicios_hoy / $total_dias_transcurridos;
            Debugbar::info($promedio_hoy, round($promedio_hoy));

            $porcentaje = $promedio_hoy * 100 / $servicios_anual;
            if($promedio_hoy > 50){
                $icon   = 'heroicon-m-arrow-trending-up';
                $color = 'success';
            }
            if ($promedio_hoy < 50) {
                $color = 'danger';
                $icon = 'heroicon-m-arrow-trending-down';
            }
            // Debugbar::info($porcentaje, $promedio_hoy);
            //--------------------------------------------------------------------------------------------------

            return $result = [
                'porcentaje' => $porcentaje ?? 0,
                'icon' => $icon ?? 'heroicon-s-shield-exclamation',
                'color' => $color ?? 'warning',
            ];

            
        } catch (\Throwable $th) {
            dd($th);
        }
    }
    /**Fin---------------------------------------------------- 
     * -------------------------------------------------------
     */



    /**
     * Grupo de funcion para sl calculo de los stat de productos
     * ----------------------------------------------------------
     */
    static function productos_facturados($start, $end, $sucursal_id)
    {
        try {

            //PRODUCTOS FATURADOS ANUAL HASTA LA FECHA ACTUAL
            //-------------------------------------------------------------------------------------------------
            $rangeStartDate = now()->subMonth()->startOfYear();
            $rangeEndDate = now()->subMonth()->endOfYear();
            $productos_anual = VentaProducto::whereBetween('created_at', [$rangeStartDate, $rangeEndDate])->sum('cantidad');
            Debugbar::info($productos_anual);
            //-------------------------------------------------------------------------------------------------

            $rangeStartDate = $start == null ? now()->startOfDay() : $start;
            $rangeEndDate = $end == null ? now()->endOfDay() : $end;
            
            $productos_hoy = VentaProducto::whereBetween('created_at', [$rangeStartDate, $rangeEndDate])->sum('cantidad');

            //PRODUCTOS FATURADOS ANUAL HASTA LA FECHA ACTUAL
            //-------------------------------------------------------------------------------------------------
            $rangeStartDate = now()->subMonth()->startOfYear();
            $rangeEndDate = now()->subMonth()->endOfYear();
            $productos_anual = VentaProducto::whereBetween('created_at', [$rangeStartDate, $rangeEndDate])->sum('cantidad');
            // Debugbar::info($servicios_anual);
            //-------------------------------------------------------------------------------------------------


            //NUMERO DE DIAS TRANSCURRIDOS
            //-----------------------------------------------------------------------------------------
            $total_dias_transcurridos =  Carbon::now()->diffInDays(now()->subMonth()->startOfYear());
            // Debugbar::info($total_dias_transcurridos);
            //-----------------------------------------------------------------------------------------


            //CALCULO DEL PROMEDIO DE SERVICIO HASTA EL DIA ACTUAL
            //-------------------------------------------------------------------------------------------------
            $promedio_hoy = $productos_hoy / $total_dias_transcurridos;
            Debugbar::info($promedio_hoy, round($promedio_hoy));

            $porcentaje = $promedio_hoy * 100 / $productos_anual;
            if ($promedio_hoy > 50) {
                $icon   = 'heroicon-m-arrow-trending-up';
                $color = 'success';
            }
            if ($promedio_hoy < 50) {
                $color = 'danger';
                $icon = 'heroicon-m-arrow-trending-down';
            }

            return $result = [
                'productos_hoy' => $productos_hoy,
                'porcentaje' => $porcentaje,
                'icon' => $icon,
                'color' => $color
            ];


        } catch (\Throwable $th) {
            dd($th);
        }
    }

    static function total_productos_usd($start, $end, $sucursal_id)
    {
        try {

            $tasa = TasaBcv::all()->first()->tasa;
            $porcen_depreciacion = ConfiguracionNomina::all()->first()->porcen_depreciacion;

            $rangeStartDate = $start == null ? now()->startOfDay() : $start;
            $rangeEndDate = $end == null ? now()->endOfDay() : $end;

            $total_productos_hoy_usd = VentaProducto::whereBetween('created_at', [$rangeStartDate, $rangeEndDate])->sum('montoUSD');
            $total_productos_hoy_bsd = VentaProducto::whereBetween('created_at', [$rangeStartDate, $rangeEndDate])->sum('montoBSD');

            //CALCULO DE LA DEPRECIACION
            //--------------------------------------------------------------------------------
            $depreciacion_bsd = ($total_productos_hoy_bsd * $porcen_depreciacion) / 100;
            // Debugbar::info($depreciacion_bsd);

            $depreciacion_a_usd = $depreciacion_bsd / $tasa;
            // Debugbar::info($depreciacion_a_usd);

            $total_hoy = $total_productos_hoy_usd + $depreciacion_a_usd;
            //--------------------------------------------------------------------------------


            //SERVICIOS FATURADOS ANUAL HASTA LA FECHA ACTUAL
            //-------------------------------------------------------------------------------------------------
            $rangeStartDate = now()->subMonth()->startOfYear();
            $rangeEndDate = now()->subMonth()->endOfYear();
            $total_productos_anual_usd = VentaProducto::whereBetween('created_at', [$rangeStartDate, $rangeEndDate])->sum('montoUSD');
            $total_productos_anual_bsd = VentaProducto::whereBetween('created_at', [$rangeStartDate, $rangeEndDate])->sum('montoBSD');

            $depreciacion_anual_bsd = ($total_productos_anual_bsd * $porcen_depreciacion) / 100;
            // Debugbar::info($depreciacion_anual_bsd);

            $depreciacion_anual_a_usd = $depreciacion_anual_bsd / $tasa;
            // Debugbar::info($depreciacion_anual_a_usd);

            $total_anual = $total_productos_anual_usd + $depreciacion_anual_a_usd;
            // Debugbar::info($total_anual_usd, $total_anual_bsd);
            //-------------------------------------------------------------------------------------------------


            //CALCULO DEL PROMEDIO DE SERVICIO HASTA EL DIA ACTUAL
            //-------------------------------------------------------------------------------------------------
            $porcentaje = $total_hoy * 100 / $total_anual;
            if ($porcentaje > 50) {
                $icon   = 'heroicon-m-arrow-trending-up';
                $color = 'success';
            }
            if ($porcentaje < 50) {
                $color = 'danger';
                $icon = 'heroicon-m-arrow-trending-down';
            }
            // Debugbar::info($porcentaje, $promedio_hoy);
            //--------------------------------------------------------------------------------------------------

            return $result = [
                'total_productos_hoy' => $total_hoy,
                'porcentaje' => $porcentaje,
                'icon' => $icon,
                'color' => $color,
            ];


        } catch (\Throwable $th) {
            dd($th);
        }
    }

    static function promedio_productos_cliente($start, $end, $sucursal_id)
    {
        try {

            //code...
            // $rangeStartDate = now()->startOfDay();
            // $rangeEndDate = now()->endOfDay();

            $rangeStartDate = $start == null ? now()->startOfDay() : $start;
            $rangeEndDate = $end == null ? now()->endOfDay() : $end;

            //Servicios y clientes para hoy
            $nro_productos_hoy = VentaProducto::whereBetween('created_at', [$rangeStartDate, $rangeEndDate])->sum('cantidad');
            $clientes_hoy = VentaServicio::whereBetween('created_at', [$rangeStartDate, $rangeEndDate])
                ->groupBy('cliente_id')
                ->get();

            $clientes_hoy = count($clientes_hoy);

            if ($clientes_hoy == 0) {
                $promedio_hoy = 0;
            } else {

                $promedio_hoy = $nro_productos_hoy / $clientes_hoy;
                // dd($promedio_hoy);

                //Fechas de Ayer
                $rangeStartDate = now()->subMonth()->startOfDay();
                $rangeEndDate = now()->subMonth()->endOfDay();

                //Servicios y clientes para Ayer
                $nro_productos_ayer = VentaProducto::whereBetween('created_at', [$rangeStartDate, $rangeEndDate])->count('cantidad');
                $clientes_ayer = VentaServicio::whereBetween('created_at', [$rangeStartDate, $rangeEndDate])
                ->groupBy('cliente_id')
                ->get();

                $clientes_ayer = count($clientes_ayer);
                
                if($clientes_ayer == 0) {
                    $promedio_ayer = 0;
                }else {
                    
                    $promedio_ayer = $nro_productos_ayer / $clientes_ayer;
                    if ($promedio_hoy > $promedio_ayer) {
                        if ($promedio_hoy == 0 && $promedio_ayer == 0) {
                            $porcentaje = 0;
                        }else {
                            $porcentaje = ($promedio_ayer * 100) / $promedio_hoy;
                            $porcentaje = number_format($porcentaje, 2);
                            
                        }
                        $icon = 'heroicon-m-arrow-trending-up';
                        $color = 'success';
                    }

                    if ($promedio_hoy < $promedio_ayer) {
                        if ($promedio_hoy == 0 && $promedio_ayer == 0) {
                            $porcentaje = 0;
                        }else {
                            $porcentaje = ($promedio_hoy * 100) / $promedio_ayer;
                            $porcentaje = number_format($porcentaje, 2);
                            
                        }
                        $icon = 'heroicon-m-arrow-trending-down';
                        $color = 'danger';
                    }

                    if ($promedio_hoy == $promedio_ayer) {
                        if ($promedio_hoy == 0 && $promedio_ayer == 0) {
                            $porcentaje = 0;
                        }else {
                            $porcentaje = ($promedio_ayer * 100) / $promedio_hoy;
                            $porcentaje = number_format($porcentaje, 2);
                            
                        }
                        $icon = 'heroicon-c-arrow-long-right';
                        $color = 'warning';
                    }
                    
                }

                
            }

            $result = [
                'promedio_hoy' => $promedio_hoy,
                'porcentaje' => $porcentaje ?? 0,
                'icon' => isset($icon) ? $icon : 'heroicon-s-shield-exclamation',
                'color' => isset($color) ? $color : 'warning', //$color,

            ];

            return $result;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
    /**Fin---------------------------------------------------- 
     * -------------------------------------------------------
     */


    /**
     * Grupo de funcion para sl calculo de los clientes
     * ----------------------------------------------------------
     */
    static function clientes_atendidos($start, $end, $sucursal_id)
    {
        try {

            //code...
            // $rangeStartDate = now()->startOfDay();
            // $rangeEndDate = now()->endOfDay();

            $rangeStartDate = $start == null ? now()->startOfDay() : $start;
            $rangeEndDate = $end == null ? now()->endOfDay() : $end;

            
            $clientes_atendidos_hoy = VentaServicio::whereBetween('created_at', [$rangeStartDate, $rangeEndDate])
                ->groupBy('cliente_id')
                ->get();
                // dd($clientes_atendidos_hoy);
            $clientes_atendidos_hoy = count($clientes_atendidos_hoy);

            //Caculo del porcentaje de productos facturados comparado con el dia anterior
            $rangeStartDate = now()->subMonth()->startOfDay();
            $rangeEndDate = now()->subMonth()->endOfDay();

            $clientes_atendidos_ayer = VentaServicio::whereBetween('created_at', [$rangeStartDate, $rangeEndDate])
                ->groupBy('cliente_id')
                ->get();
            $clientes_atendidos_ayer = count($clientes_atendidos_ayer);

            if ($clientes_atendidos_hoy > $clientes_atendidos_ayer) {
                $porcentaje = ($clientes_atendidos_ayer * 100) / $clientes_atendidos_hoy;
                $porcentaje = number_format($porcentaje, 2);
                $icon = 'heroicon-m-arrow-trending-up';
                $color = 'success';
            }

            if ($clientes_atendidos_hoy < $clientes_atendidos_ayer) {
                $porcentaje = ($clientes_atendidos_hoy * 100) / $clientes_atendidos_ayer;
                $porcentaje = number_format($porcentaje, 2);
                $icon = 'heroicon-m-arrow-trending-down';
                $color = 'danger';
            }

            if ($clientes_atendidos_hoy == $clientes_atendidos_ayer) {
                if ($clientes_atendidos_hoy == 0 && $clientes_atendidos_ayer == 0) {
                    $porcentaje = 0;
                    $icon = 'heroicon-c-arrow-long-right';
                    $color = 'danger';
                } else {
                    $porcentaje = ($clientes_atendidos_ayer * 100) / $clientes_atendidos_hoy;
                    $porcentaje = number_format($porcentaje, 2);
                    $icon = 'heroicon-c-arrow-long-right';
                    $color = 'warning';
                }
            }

            $result = [
                'clientes_atendidos_hoy' => $clientes_atendidos_hoy,
                'porcentaje' => $porcentaje,
                'icon' => $icon,
                'color' => $color
            ];

            return $result;
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    static function clientes_nuevos($start, $end, $sucursal_id)
    {
        try {

            //code...
            // $rangeStartDate = now()->startOfDay();
            // $rangeEndDate = now()->endOfDay();

            $rangeStartDate = $start == null ? now()->startOfDay() : $start;
            $rangeEndDate = $end == null ? now()->endOfDay() : $end;

            $clientes_nuevos_hoy = [];

            //clientes para hoy
            $nuevos_hoy  = VentaServicio::whereBetween('created_at', [$rangeStartDate, $rangeEndDate])->groupBy('cliente_id')->get();
            for ($i = 0; $i < count($nuevos_hoy); $i++) {

                $nuevos_hoy[$i] = $nuevos_hoy[$i]->cliente_id;
                $cliente = Cliente::where('id', $nuevos_hoy[$i])->first();
                if ($cliente->visitas == 1) {
                    array_push($clientes_nuevos_hoy, $cliente->id);
                }
            }


            //Fechas de Ayer
            $rangeStartDate = now()->subMonth()->startOfDay();
            $rangeEndDate   = now()->subMonth()->endOfDay();

            $clientes_nuevos_ayer = [];

            //Cliente recurrente ayer
            $nuevos_ayer  = VentaServicio::whereBetween('created_at', [$rangeStartDate, $rangeEndDate])->groupBy('cliente_id')->get();
            for ($i = 0; $i < count($nuevos_ayer); $i++) {

                $nuevos_ayer[$i] = $nuevos_ayer[$i]->cliente_id;
                $cliente = Cliente::where('id', $nuevos_ayer[$i])->first();
                if ($cliente->visitas == 1) {
                    array_push($clientes_nuevos_ayer, $cliente->id);
                }
            }

            $total_clientes_hoy = count($clientes_nuevos_hoy);
            $total_clientes_ayer = count($clientes_nuevos_ayer);

            if ($total_clientes_hoy > $total_clientes_ayer) {
                $porcentaje = ($total_clientes_ayer * 100) / $total_clientes_hoy;
                $porcentaje = number_format($porcentaje, 2);
                $icon = 'heroicon-m-arrow-trending-up';
                $color = 'success';
            }

            if ($total_clientes_hoy < $total_clientes_ayer) {
                $porcentaje = ($total_clientes_hoy * 100) / $total_clientes_ayer;
                $porcentaje = number_format($porcentaje, 2);
                $icon = 'heroicon-m-arrow-trending-down';
                $color = 'danger';
            }

            if ($total_clientes_hoy == $total_clientes_ayer) {
                if ($total_clientes_hoy == 0 && $total_clientes_ayer == 0) {
                    $porcentaje = 0;
                    $icon = 'heroicon-c-arrow-long-right';
                    $color = 'danger';
                } else {
                    $porcentaje = ($total_clientes_ayer * 100) / $total_clientes_hoy;
                    $porcentaje = number_format($porcentaje, 2);
                    $icon = 'heroicon-c-arrow-long-right';
                    $color = 'warning';
                }
            }

            $result = [
                'total_clientes_hoy' => $total_clientes_hoy,
                'porcentaje' => $porcentaje,
                'icon' => $icon,
                'color' => $color,
            ];

            return $result;
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    static function clientes_recurrentes($start, $end, $sucursal_id)
    {
        try {

            //code...
            // $rangeStartDate = now()->startOfDay();
            // $rangeEndDate = now()->endOfDay();

            $rangeStartDate = $start == null ? now()->startOfDay() : $start;
            $rangeEndDate = $end == null ? now()->endOfDay() : $end;

            $clientes_recurrentes_hoy = [];

            //clientes para hoy
            $recurrentes_hoy  = VentaServicio::whereBetween('created_at', [$rangeStartDate, $rangeEndDate])->groupBy('cliente_id')->get();
            for ($i = 0; $i < count($recurrentes_hoy); $i++) {
                
                $recurrentes_hoy[$i] = $recurrentes_hoy[$i]->cliente_id;
                $cliente = Cliente::where('id', $recurrentes_hoy[$i])->first();
                if($cliente->visitas > 1){
                    array_push($clientes_recurrentes_hoy, $cliente->id);
                }
            }


            //Fechas de Ayer
            $rangeStartDate = now()->subMonth()->startOfDay();
            $rangeEndDate   = now()->subMonth()->endOfDay();

            $clientes_recurrentes_ayer = [];

            //Cliente recurrente ayer
            $recurrentes_ayer  = VentaServicio::whereBetween('created_at', [$rangeStartDate, $rangeEndDate])->groupBy('cliente_id')->get();
            for ($i = 0; $i < count($recurrentes_ayer); $i++) {
                
                $recurrentes_ayer[$i] = $recurrentes_ayer[$i]->cliente_id;
                $cliente = Cliente::where('id', $recurrentes_ayer[$i])->first();
                if ($cliente->visitas > 1) {
                    array_push($clientes_recurrentes_ayer, $cliente->id);
                }

            }

            $recurrentes_hoy = count($clientes_recurrentes_hoy);
            $recurrentes_ayer = count($clientes_recurrentes_ayer);


            if ($recurrentes_hoy > $recurrentes_ayer) {
                $porcentaje = ($recurrentes_ayer * 100) / $recurrentes_hoy;
                $porcentaje = number_format($porcentaje, 2);
                $icon       = 'heroicon-m-arrow-trending-up';
                $color      = 'success';
            }

            if ($recurrentes_hoy < $recurrentes_ayer) {
                $porcentaje = ($recurrentes_hoy * 100) / $recurrentes_ayer;
                $porcentaje = number_format($porcentaje, 2);
                $icon       = 'heroicon-m-arrow-trending-down';
                $color      = 'danger';
            }

            if ($recurrentes_hoy == $recurrentes_ayer) {
                if ($recurrentes_hoy == 0 && $recurrentes_ayer == 0) {
                    $porcentaje = 0;
                    $icon = 'heroicon-c-arrow-long-right';
                    $color = 'danger';
                } else {
                    $porcentaje = ($recurrentes_ayer * 100) / $recurrentes_hoy;
                    $porcentaje = number_format($porcentaje, 2);
                    $icon       = 'heroicon-c-arrow-long-right';
                    $color      = 'warning';
                }
            }

            $result = [
                'recurrentes_hoy'   => $recurrentes_hoy,
                'porcentaje'        => $porcentaje,
                'icon'              => $icon,
                'color'             => $color,
            ];

            return $result;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
    /**Fin---------------------------------------------------- 
     * -------------------------------------------------------
     */

    /**
     * Grupo de funcion para sl calculo de los clientes
     * ----------------------------------------------------------
     */
    static function quiropedista_activo($start, $end, $sucursal_id)
    {
        try {

            $rangeStartDate = $start == null ? now()->startOfDay() : $start;
            $rangeEndDate = $end == null ? now()->endOfDay() : $end;

            $disponible = Disponible::whereBetween('created_at', [$rangeStartDate, $rangeEndDate])->groupBy('empleado_id')->where('status','activo')->get()->toArray();
            // dd($disponible);
            $count_disponible = [];

            for ($i = 0; $i < count($disponible); $i++) {
                $user = User::where('id', $disponible[$i]['empleado_id'])->first()->rol_id;
                if(isset($user) and $user == 2){
                    array_push($count_disponible, $user);
                }else{
                    break;
                }
                
            }

            $total = count($count_disponible);

            $result = [
                'total' => $total,
            ];

            return $result;
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    static function manicurista_activo($start, $end, $sucursal_id)
    {
        try {

            $rangeStartDate = $start == null ? now()->startOfDay() : $start;
            $rangeEndDate = $end == null ? now()->endOfDay() : $end;

            $disponible = Disponible::whereBetween('created_at', [$rangeStartDate, $rangeEndDate])->groupBy('empleado_id')->where('status', 'activo')->get()->toArray();

            $count_disponible = [];

            for ($i = 0; $i < count($disponible); $i++) {
                $user = User::where('id', $disponible[$i]['empleado_id'])->first()->rol_id;
                if (isset($user) and $user == 1) {
                    array_push($count_disponible, $user);
                } else {
                    break;
                }
            }

            $total = count($count_disponible);

            $result = [
                'total' => $total,
            ];

            return $result;
            
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    static function total_servicios_activos($start, $end, $sucursal_id)
    {
        try {

            //code...
            // $rangeStartDate = now()->startOfDay();
            // $rangeEndDate = now()->endOfDay();

            $rangeStartDate = $start == null ? now()->startOfDay() : $start;
            $rangeEndDate = $end == null ? now()->endOfDay() : $end;

            $clientes_recurrentes_hoy = [];

            //clientes para hoy
            $recurrentes_hoy  = VentaServicio::whereBetween('created_at', [$rangeStartDate, $rangeEndDate])->groupBy('cliente_id')->get();
            for ($i = 0; $i < count($recurrentes_hoy); $i++) {

                $recurrentes_hoy[$i] = $recurrentes_hoy[$i]->cliente_id;
                $cliente = Cliente::where('id', $recurrentes_hoy[$i])->first();
                if ($cliente->visitas > 1) {
                    array_push($clientes_recurrentes_hoy, $cliente->id);
                }
            }


            //Fechas de Ayer
            $rangeStartDate = now()->subMonth()->startOfDay();
            $rangeEndDate   = now()->subMonth()->endOfDay();

            $clientes_recurrentes_ayer = [];

            //Cliente recurrente ayer
            $recurrentes_ayer  = VentaServicio::whereBetween('created_at', [$rangeStartDate, $rangeEndDate])->groupBy('cliente_id')->get();
            for ($i = 0; $i < count($recurrentes_ayer); $i++) {

                $recurrentes_ayer[$i] = $recurrentes_ayer[$i]->cliente_id;
                $cliente = Cliente::where('id', $recurrentes_ayer[$i])->first();
                if ($cliente->visitas > 1) {
                    array_push($clientes_recurrentes_ayer, $cliente->id);
                }
            }

            $recurrentes_hoy = count($clientes_recurrentes_hoy);
            $recurrentes_ayer = count($clientes_recurrentes_ayer);


            if ($recurrentes_hoy > $recurrentes_ayer) {
                $porcentaje = ($recurrentes_ayer * 100) / $recurrentes_hoy;
                $porcentaje = number_format($porcentaje, 2);
                $icon       = 'heroicon-m-arrow-trending-up';
                $color      = 'success';
            }

            if ($recurrentes_hoy < $recurrentes_ayer) {
                $porcentaje = ($recurrentes_hoy * 100) / $recurrentes_ayer;
                $porcentaje = number_format($porcentaje, 2);
                $icon       = 'heroicon-m-arrow-trending-down';
                $color      = 'danger';
            }

            if ($recurrentes_hoy == $recurrentes_ayer) {
                if ($recurrentes_hoy == 0 && $recurrentes_ayer == 0) {
                    $porcentaje = 0;
                    $icon = 'heroicon-c-arrow-long-right';
                    $color = 'danger';
                } else {
                    $porcentaje = ($recurrentes_ayer * 100) / $recurrentes_hoy;
                    $porcentaje = number_format($porcentaje, 2);
                    $icon       = 'heroicon-c-arrow-long-right';
                    $color      = 'warning';
                }
            }

            $result = [
                'recurrentes_hoy'   => $recurrentes_hoy,
                'porcentaje'        => $porcentaje,
                'icon'              => $icon,
                'color'             => $color,
            ];

            return $result;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
    /**Fin---------------------------------------------------- 
     * -------------------------------------------------------
     */
}