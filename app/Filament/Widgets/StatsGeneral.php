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

    protected int | string | array $columnSpan = '1';

    protected function getStats(): array
    {

        $servicios              = StatController::servicios_facturados();
        $servicios_usd          = StatController::total_servicios_usd();
        $promedio               = StatController::promedio_servicio_cliente();

        $productos              = StatController::productos_facturados();
        $productos_usd          = StatController::total_productos_usd();
        $promedio_prod          = StatController::promedio_productos_cliente();

        $clientes_atendidos     = StatController::clientes_atendidos();
        $clientes_nuevos        = StatController::clientes_nuevos();
        $clientes_recurrentes   = StatController::clientes_recurrentes();

        return [

            /**
             * GRUPO 1 SERVICIOS:
             * -----------
             */

            //Stat Servicios -----------------------------------------------------------------------------------------------
            //--------------------------------------------------------------------------------------------------------------
            Stat::make('SERVICIOS', $servicios['servicios_hoy'])
                ->description(round($servicios['porcentaje']) . '%')
                ->descriptionIcon($servicios['icon'])
                ->color($servicios['color'])
                ->extraAttributes(['class' => 'col-span-1 row-span-1 rounded-md text-center border-4 border-[#3ec7d28a]']),

            Stat::make('TOTAL SERVICIOS($)', $servicios_usd['total_hoy'] . $servicios_usd['letra'])
                ->description(round($servicios_usd['porcentaje']) . '%')
                ->descriptionIcon($servicios_usd['icon'])
                ->color($servicios_usd['color'])
                ->extraAttributes(['class' => 'col-span-1 row-span-1 rounded-md text-center border-4 border-[#3ec7d28a]']),

            Stat::make('PROMEDIO SERVICIO/CLIENTE', number_format($promedio['promedio_hoy'], 1))
                ->description(round($promedio['porcentaje']) . '%')
                ->descriptionIcon($promedio['icon'])
                ->color($promedio['color'])
                ->extraAttributes(['class' => 'col-span-1 row-span-1 rounded-md text-center border-4 border-[#3ec7d28a]']),


            //Stat Productos -----------------------------------------------------------------------------------------------
            //--------------------------------------------------------------------------------------------------------------
            Stat::make('PRODUCTOS VENDIDOS', $productos['productos_hoy'])
                ->description(round($productos['porcentaje']) . '%')
                ->descriptionIcon($productos['icon'])
                ->color($productos['color'])
                ->extraAttributes(['class' => 'col-span-2 row-span-1 rounded-md text-center border-4 border-[#7B9AA6]']),

            Stat::make('TOTAL PRODUCTOS($)', $productos_usd['total_productos_hoy'] . $productos_usd['letra'])
                ->description(round($productos_usd['porcentaje']) . '%')
                ->descriptionIcon($productos_usd['icon'])
                ->color($productos_usd['color'])
                ->extraAttributes(['class' => 'col-span-1 row-span-1 rounded-md text-center border-4 border-[#7B9AA6]']),

            // Stat::make('PROMEDIO PRODUCTO/CLIENTE', number_format($promedio_prod['promedio_hoy'], 1))
            //     ->description(round($promedio_prod['porcentaje']) . '%')
            //     ->descriptionIcon($promedio_prod['icon'])
            //     ->color($promedio_prod['color'])
            //     ->extraAttributes(['class' => 'col-span-1 row-span-1 rounded-md text-center border-4 border-[#7B9AA6]']),


            //Stat Clientes -----------------------------------------------------------------------------------------------
            //-------------------------------------------------------------------------------------------------------------
            Stat::make('CLIENTES ATENDIDOS', $clientes_atendidos['clientes_atendidos_hoy'])
                ->description(round($clientes_atendidos['porcentaje']) . '%')
                ->descriptionIcon($clientes_atendidos['icon'])
                ->color($clientes_atendidos['color'])
                ->extraAttributes(['class' => 'col-span-1 row-span-1 rounded-md text-center border-4 border-[#bf9c999e]']),

            Stat::make('CLIENTES NUEVOS', $clientes_nuevos['total_clientes_hoy'])
                ->description(round($clientes_nuevos['porcentaje']) . '%')
                ->descriptionIcon($clientes_nuevos['icon'])
                ->color($clientes_nuevos['color'])
                ->extraAttributes(['class' => 'col-span-1 row-span-1 rounded-md text-center border-4 border-[#bf9c999e]']),

            Stat::make('CLIENTES RECURRENTES', $clientes_recurrentes['recurrentes_hoy'])
                ->description(round($clientes_recurrentes['porcentaje']) . '%')
                ->descriptionIcon($clientes_recurrentes['icon'])
                ->color($clientes_recurrentes['color'])
                ->extraAttributes(['class' => 'col-span-1 row-span-1 rounded-md text-center border-4 border-[#bf9c999e]']),
        ];
    }

    public function getColumns(): int
    {
        return 3;
    }
}
