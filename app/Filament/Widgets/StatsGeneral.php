<?php

namespace App\Filament\Widgets;

use App\Models\Cita;
use App\Models\Gasto;
use App\Models\Venta;
use App\Models\Cliente;
use App\Models\TasaBcv;
use App\Models\Producto;
use App\Models\Disponible;
use App\Models\Frecuencia;
use App\Models\VentaProducto;
use App\Models\VentaServicio;
use App\Models\DetalleAsignacion;
use App\Http\Controllers\StatController;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;


class StatsGeneral extends BaseWidget
{
    use InteractsWithPageFilters;

    protected static ?int $sort = 1;

    // protected int | string | array $columnSpan = '1';

    protected function getStats(): array
    {

        $start = $this->filters['startDate'] == null ? now()->startOfDay() : $this->filters['startDate'].' 05:00:00';
        $end = $this->filters['endDate'] == null ? now()->endOfDay() : $this->filters['endDate'].' 23:59:59';
        $sucursal_id = $this->filters['sucursal_id'] == null ? null : $this->filters['sucursal_id'];

        $servicios              = StatController::servicios_facturados($start, $end, $sucursal_id =  null);
        $servicios_usd          = StatController::total_servicios_usd($start, $end, $sucursal_id =  null);
        $promedio               = StatController::promedio_servicio_cliente($start, $end, $sucursal_id =  null);
        $promedio_anual         = StatController::promedio_servicio_anual($start, $end, $sucursal_id =  null);

        $productos              = StatController::productos_facturados($start, $end, $sucursal_id =  null);
        $productos_usd          = StatController::total_productos_usd($start, $end, $sucursal_id =  null);
        $promedio_prod          = StatController::promedio_productos_cliente($start, $end, $sucursal_id =  null);

        $quiropedistas          = StatController::quiropedista_activo($start, $end, $sucursal_id =  null);
        $manicuristas           = StatController::manicurista_activo($start, $end, $sucursal_id =  null);

        return [

            /**
             * GRUPO 1 SERVICIOS:
             * -----------
             */

            //Stat Servicios -----------------------------------------------------------------------------------------------
            //--------------------------------------------------------------------------------------------------------------
            Stat::make('SERVICIOS', $servicios['servicios_hoy'])
                ->description(number_format($promedio_anual['porcentaje'], 2) . '% ')
                ->descriptionIcon($promedio_anual['icon'])
                ->color($promedio_anual['color'])
                ->extraAttributes(['class' => 'col-span-1 row-span-1 rounded-md text-center border-4 border-[#3ec7d28a] content-center']),

            Stat::make('TOTAL SERVICIOS EN DOLARES($)', number_format($servicios_usd['total_hoy'], 2))
                ->description(round($servicios_usd['porcentaje']) . '%')
                ->descriptionIcon($servicios_usd['icon'])
                ->color($servicios_usd['color'])
                ->extraAttributes(['class' => 'col-span-2 row-span-1 rounded-md text-center border-4 border-[#3ec7d28a] content-center']),

            Stat::make('PROMEDIO SERVICIO/CLIENTE', round($promedio['promedio_hoy']))
                ->description(round($promedio['porcentaje']). '% ')
                ->descriptionIcon($promedio['icon'])
                ->color($promedio['color'])
                ->extraAttributes(['class' => 'col-span-1 row-span-1 rounded-md text-center border-4 border-[#3ec7d28a] content-center']),


            //Stat Productos -----------------------------------------------------------------------------------------------
            //--------------------------------------------------------------------------------------------------------------
            Stat::make('PRODUCTOS VENDIDOS', $productos['productos_hoy'])
                ->description(number_format($productos['porcentaje'], 2) . '%')
                ->descriptionIcon($productos['icon'])
                ->color($productos['color'])
                ->extraAttributes(['class' => 'col-span-1 row-span-1 rounded-md text-center border-4 border-[#7B9AA6] content-center']),

            Stat::make('TOTAL PRODUCTOS EN DOLARES($)', number_format($productos_usd['total_productos_hoy'], 2))
                ->description(round($productos_usd['porcentaje']) . '%')
                ->descriptionIcon($productos_usd['icon'])
                ->color($productos_usd['color'])
                ->extraAttributes(['class' => 'col-span-1 row-span-1 rounded-md text-center border-4 border-[#7B9AA6] content-center']),

            Stat::make('CHATBOTS ATENDIDOS', number_format($promedio_prod['promedio_hoy'], 1))
                ->description(round($promedio_prod['porcentaje']) . '%')
                ->descriptionIcon($promedio_prod['icon'])
                ->color($promedio_prod['color'])
                ->extraAttributes(['class' => 'col-span-1 row-span-1 rounded-md text-center border-4 border-[#7B9AA6] content-center']),

                //Stat Productos -----------------------------------------------------------------------------------------------
            //--------------------------------------------------------------------------------------------------------------
            Stat::make('QUROPEDISTAS', $quiropedistas['total'])
                ->description('ACTIVOS')
                // ->descriptionIcon($productos['icon'])
                ->color('success')
                ->extraAttributes(['class' => 'col-span-1 row-span-1 rounded-md text-center border-4 border-[#d7c2c0] content-center']),

            Stat::make('MANICURISTAS', $manicuristas['total'])
                ->description('ACTIVOS')
                // ->descriptionIcon($productos_usd['icon'])
                ->color('success')
                ->extraAttributes(['class' => 'col-span-1 row-span-1 rounded-md text-center border-4 border-[#d7c2c0] content-center']),

            // Stat::make('TOTAL', $quiropedistas['total'] + $manicuristas['total'])
            //     ->description('SERVICIOS ACTIVOS')
            //     // ->descriptionIcon($promedio_prod['icon'])
            //     ->color('success')
            //     ->extraAttributes(['class' => 'col-span-1 row-span-1 rounded-md text-center border-4 border-[#d7c2c0] content-center']),
        ];
    }

    protected int | string | array $columnSpan = [
        // 'xs' => 3,
        'sm' => 1,
        'md' => 1,
        'lg' => 1,
        'xl' => 1,
    ];
}