<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Disponible;
use App\Models\Frecuencia;
use Illuminate\Http\Request;
use App\Models\VentaProducto;
use App\Models\VentaServicio;
use App\Models\DetalleAsignacion;

class StatController extends Controller
{
    /**
     * Grupo de funcion para sl calculo de los stat de servicios
     * ----------------------------------------------------------
     */
    static function servicios_facturados() {
        try {
            
            //code...
            $rangeStartDate = now()->startOfDay();
            $rangeEndDate = now()->endOfDay();
            $servicios_hoy = VentaServicio::whereBetween('created_at', [$rangeStartDate, $rangeEndDate])->count();

            //Caculo del porcentaje de servicios facturados comparado con el dia anterior
            $rangeStartDate = now()->subDay()->startOfDay();
            $rangeEndDate = now()->subDay()->endOfDay();

            $servicios_ayer = VentaServicio::whereBetween('created_at', [$rangeStartDate, $rangeEndDate])->count();

            if ($servicios_hoy > $servicios_ayer) {
                $porcentaje = ($servicios_ayer * 100) / $servicios_hoy;
                $porcentaje = number_format($porcentaje, 2);
                $icon = 'heroicon-m-arrow-trending-up';
                $color = 'success';
            }

            if ($servicios_hoy < $servicios_ayer) {
                $porcentaje = ($servicios_hoy * 100) / $servicios_ayer;
                $porcentaje = number_format($porcentaje, 2);
                $icon = 'heroicon-m-arrow-trending-down';
                $color = 'danger';
            }

            if ($servicios_hoy == $servicios_ayer) {
                $porcentaje = ($servicios_ayer * 100) / $servicios_hoy;
                $porcentaje = number_format($porcentaje, 2);
                $icon = 'heroicon-c-arrow-long-right';
                $color = 'warning';
            }
            
            $result = [
                'servicios_hoy' => $servicios_hoy,
                'porcentaje' => $porcentaje,
                'icon' => $icon,
                'color' => $color
            ];
            
            return $result;
            
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    static function total_servicios_usd()
    {
        try {

            //code...
            $rangeStartDate = now()->startOfDay();
            $rangeEndDate = now()->endOfDay();
            
            $total_hoy = VentaServicio::whereBetween('created_at', [$rangeStartDate, $rangeEndDate])->sum('total_USD');
            
            if($total_hoy > 1000)   {
                $total_hoy_div = round($total_hoy) / 1000;
                $letra = 'K';
            }elseif ($total_hoy > 1000000) {
                $total_hoy_div = round($total_hoy) / 1000000;
                $letra = 'M';
            }else{
                $total_hoy_div = round($total_hoy);
                $letra = '';
            }

            //Caculo del porcentaje de servicios facturados comparado con el dia anterior
            $rangeStartDate = now()->subDay()->startOfDay();
            $rangeEndDate = now()->subDay()->endOfDay();

            $total_ayer = VentaServicio::whereBetween('created_at', [$rangeStartDate, $rangeEndDate])->sum('total_USD');

            if ($total_hoy > $total_ayer) {
                $porcentaje = ($total_ayer * 100) / $total_hoy;
                $porcentaje = number_format($porcentaje, 2);
                $icon = 'heroicon-m-arrow-trending-up';
                $color = 'success';
            }

            if ($total_hoy < $total_ayer) {
                $porcentaje = ($total_hoy * 100) / $total_ayer;
                $porcentaje = number_format($porcentaje, 2);
                $icon = 'heroicon-m-arrow-trending-down';
                $color = 'danger';
            }

            if ($total_hoy == $total_ayer) {
                $porcentaje = ($total_ayer * 100) / $total_hoy;
                $porcentaje = number_format($porcentaje, 2);
                $icon = 'heroicon-c-arrow-long-right';
                $color = 'warning';
            }

            $result = [
                'total_hoy' => $total_hoy_div,
                'porcentaje' => $porcentaje,
                'icon' => $icon,
                'color' => $color,
                'letra' => $letra
            ];

            return $result;
            
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    static function promedio_servicio_cliente()
    {
        try {

            //code...
            $rangeStartDate = now()->startOfDay();
            $rangeEndDate = now()->endOfDay();

            //Servicios y clientes para hoy
            $nro_servicios_hoy = DetalleAsignacion::whereBetween('created_at', [$rangeStartDate, $rangeEndDate])->count();
            $clientes_hoy = Disponible::whereBetween('created_at', [$rangeStartDate, $rangeEndDate])
            // ->groupBy('cliente_id')
            ->count();

            if($clientes_hoy == 0){
                $promedio_hoy = 0;
                
            }else{
                $promedio_hoy = $nro_servicios_hoy / $clientes_hoy;


                //Fechas de Ayer
                $rangeStartDate = now()->subDay()->startOfDay();
                $rangeEndDate = now()->subDay()->endOfDay();

                //Servicios y clientes para Ayer
                $nro_servicios_ayer = DetalleAsignacion::whereBetween('created_at', [$rangeStartDate, $rangeEndDate])->count();
                $clientes_ayer = Disponible::whereBetween('created_at', [$rangeStartDate, $rangeEndDate])
                    // ->groupBy('cliente_id')
                    ->count();

                $promedio_ayer = $nro_servicios_ayer / $clientes_ayer;

                if ($promedio_hoy > $promedio_ayer) {
                    $porcentaje = ($promedio_ayer * 100) / $promedio_hoy;
                    $porcentaje = number_format($porcentaje, 2);
                    $icon = 'heroicon-m-arrow-trending-up';
                    $color = 'success';
                }

                if ($promedio_hoy < $promedio_ayer) {
                    $porcentaje = ($promedio_hoy * 100) / $promedio_ayer;
                    $porcentaje = number_format($porcentaje, 2);
                    $icon = 'heroicon-m-arrow-trending-down';
                    $color = 'danger';
                }

                if ($promedio_hoy == $promedio_ayer) {
                    if ($promedio_hoy == 0 && $promedio_ayer == 0) {
                        $porcentaje = 0;
                        $icon = 'heroicon-c-arrow-long-right';
                        $color = 'danger';
                    } else {
                        $porcentaje = ($promedio_ayer * 100) / $promedio_hoy;
                        $porcentaje = number_format($porcentaje, 2);
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
    static function productos_facturados()
    {
        try {

            //code...
            $rangeStartDate = now()->startOfDay();
            $rangeEndDate = now()->endOfDay();
            $productos_hoy = VentaProducto::whereBetween('created_at', [$rangeStartDate, $rangeEndDate])->count();

            //Caculo del porcentaje de productos facturados comparado con el dia anterior
            $rangeStartDate = now()->subDay()->startOfDay();
            $rangeEndDate = now()->subDay()->endOfDay();

            $productos_ayer = VentaProducto::whereBetween('created_at', [$rangeStartDate, $rangeEndDate])->count();

            if ($productos_hoy > $productos_ayer) {
                $porcentaje = ($productos_ayer * 100) / $productos_hoy;
                $porcentaje = number_format($porcentaje, 2);
                $icon = 'heroicon-m-arrow-trending-up';
                $color = 'success';
            }

            if ($productos_hoy < $productos_ayer) {
                $porcentaje = ($productos_hoy * 100) / $productos_ayer;
                $porcentaje = number_format($porcentaje, 2);
                $icon = 'heroicon-m-arrow-trending-down';
                $color = 'danger';
            }

            if ($productos_hoy == $productos_ayer) {
                if($productos_hoy == 0 && $productos_ayer == 0) {
                    $porcentaje = 0;
                    $icon = 'heroicon-c-arrow-long-right';
                    $color = 'danger';
                    
                }else{
                    $porcentaje = ($productos_ayer * 100) / $productos_hoy;
                    $porcentaje = number_format($porcentaje, 2);
                    $icon = 'heroicon-c-arrow-long-right';
                    $color = 'warning';
                    
                }
            }

            $result = [
                'productos_hoy' => $productos_hoy,
                'porcentaje' => $porcentaje,
                'icon' => $icon,
                'color' => $color
            ];

            return $result;
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    static function total_productos_usd()
    {
        try {

            //code...
            $rangeStartDate = now()->startOfDay();
            $rangeEndDate = now()->endOfDay();

            $total_productos_hoy = VentaProducto::whereBetween('created_at', [$rangeStartDate, $rangeEndDate])->sum('total_venta');

            if ($total_productos_hoy > 1000) {
                // dd(ctype_digit($total_productos_hoy));
                // dd($total_productos_hoy, round($total_productos_hoy));
                $total_productos_hoy_div = round($total_productos_hoy) / 1000;
                $letra = 'K';
            } elseif ($total_productos_hoy > 1000000) {
                $total_productos_hoy_div = round($total_productos_hoy) / 1000000;
                $letra = 'M';
            } else {
                $total_productos_hoy_div = round($total_productos_hoy);
                $letra = '';
            }

            //Caculo del porcentaje de servicios facturados comparado con el dia anterior
            $rangeStartDate = now()->subDay()->startOfDay();
            $rangeEndDate = now()->subDay()->endOfDay();

            $total_productos_ayer = VentaProducto::whereBetween('created_at', [$rangeStartDate, $rangeEndDate])->sum('total_venta');

            if ($total_productos_hoy > $total_productos_ayer) {
                $porcentaje = ($total_productos_ayer * 100) / $total_productos_hoy;
                $porcentaje = number_format($porcentaje, 2);
                $icon = 'heroicon-m-arrow-trending-up';
                $color = 'success';
            }

            if ($total_productos_hoy < $total_productos_ayer) {
                $porcentaje = ($total_productos_hoy * 100) / $total_productos_ayer;
                $porcentaje = number_format($porcentaje, 2);
                $icon = 'heroicon-m-arrow-trending-down';
                $color = 'danger';
            }

            if ($total_productos_hoy == $total_productos_ayer) {
                if ($total_productos_hoy == 0 && $total_productos_ayer == 0) {
                    $porcentaje = 0;
                    $icon = 'heroicon-c-arrow-long-right';
                    $color = 'danger';
                    
                }else {
                    $porcentaje = ($total_productos_ayer * 100) / $total_productos_hoy;
                    $porcentaje = number_format($porcentaje, 2);
                    $icon = 'heroicon-c-arrow-long-right';
                    $color = 'warning';
                    
                }
                
            }

            $result = [
                'total_productos_hoy' => $total_productos_hoy_div,
                'porcentaje' => $porcentaje,
                'icon' => $icon,
                'color' => $color,
                'letra' => $letra
            ];

            return $result;
            
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    static function promedio_productos_cliente()
    {
        try {

            //code...
            $rangeStartDate = now()->startOfDay();
            $rangeEndDate = now()->endOfDay();

            //Servicios y clientes para hoy
            $nro_productos_hoy = VentaProducto::whereBetween('created_at', [$rangeStartDate, $rangeEndDate])->count('cantidad');
            $clientes_hoy = VentaProducto::whereBetween('created_at', [$rangeStartDate, $rangeEndDate])->count('cliente_id');
            

            if($clientes_hoy == 0){
                $promedio_hoy = 0;
                
            }else{

                $promedio_hoy = $nro_productos_hoy / $clientes_hoy;

                //Fechas de Ayer
                $rangeStartDate = now()->subDay()->startOfDay();
                $rangeEndDate = now()->subDay()->endOfDay();

                //Servicios y clientes para Ayer
                $nro_productos_ayer = VentaProducto::whereBetween('created_at', [$rangeStartDate, $rangeEndDate])->count('cantidad');
                $clientes_ayer = VentaProducto::whereBetween('created_at', [$rangeStartDate, $rangeEndDate])->count('cliente_id');
                $promedio_ayer = $nro_productos_ayer / $clientes_ayer;


                if ($promedio_hoy > $promedio_ayer) {
                    $porcentaje = ($promedio_ayer * 100) / $promedio_hoy;
                    $porcentaje = number_format($porcentaje, 2);
                    $icon = 'heroicon-m-arrow-trending-up';
                    $color = 'success';
                }

                if ($promedio_hoy < $promedio_ayer) {
                    $porcentaje = ($promedio_hoy * 100) / $promedio_ayer;
                    $porcentaje = number_format($porcentaje, 2);
                    $icon = 'heroicon-m-arrow-trending-down';
                    $color = 'danger';
                }

                if ($promedio_hoy == $promedio_ayer) {
                    $porcentaje = ($promedio_ayer * 100) / $promedio_hoy;
                    $porcentaje = number_format($porcentaje, 2);
                    $icon = 'heroicon-c-arrow-long-right';
                    $color = 'warning';
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
    static function clientes_atendidos()
    {
        try {

            //code...
            $rangeStartDate = now()->startOfDay();
            $rangeEndDate = now()->endOfDay();
            $clientes_atendidos_hoy = Disponible::whereBetween('created_at', [$rangeStartDate, $rangeEndDate])
            ->groupBy('cliente_id')
            ->count();

            //Caculo del porcentaje de productos facturados comparado con el dia anterior
            $rangeStartDate = now()->subDay()->startOfDay();
            $rangeEndDate = now()->subDay()->endOfDay();

            $clientes_atendidos_ayer = Disponible::whereBetween('created_at', [$rangeStartDate, $rangeEndDate])
            ->groupBy('cliente_id')
            ->count();

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

    static function clientes_nuevos()
    {
        try {

            //code...
            $rangeStartDate = now()->startOfDay();
            $rangeEndDate = now()->endOfDay();

            $total_clientes_hoy = Frecuencia::whereBetween('created_at', [$rangeStartDate, $rangeEndDate])->count();

            //Caculo del porcentaje de servicios facturados comparado con el dia anterior
            $rangeStartDate = now()->subDay()->startOfDay();
            $rangeEndDate = now()->subDay()->endOfDay();

            $total_clientes_ayer = Frecuencia::whereBetween('created_at', [$rangeStartDate, $rangeEndDate])->count();

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

    static function clientes_recurrentes()
    {
        try {

            //code...
            $rangeStartDate = now()->startOfDay();
            $rangeEndDate   = now()->endOfDay();

            //clientes para hoy
            $recurrentes_hoy  = Cliente::where('visitas', '>=', 2)->whereBetween('created_at', [$rangeStartDate, $rangeEndDate])->count();

            //Fechas de Ayer
            $rangeStartDate = now()->subMonth()->startOfDay();
            $rangeEndDate   = now()->subMonth()->endOfDay();

            //clientes para Ayer
            $recurrentes_ayer = Cliente::where('visitas', '>=', 2)->whereBetween('created_at', [$rangeStartDate, $rangeEndDate])->count();

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