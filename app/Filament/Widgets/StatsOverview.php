<?php

namespace App\Filament\Widgets;

use App\Models\Cliente;
use App\Models\Producto;
use App\Models\VentaServicio;
use App\Models\Disponible;
use App\Models\Frecuencia;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;


class StatsOverview extends BaseWidget
{
    // protected static ?string $pollingInterval = '10s';


    protected function getStats(): array
    {
        return [

            Stat::make('Ventas($)', '$ '.number_format(VentaServicio::sum('total_USD'), 2, '.', ','))
                ->description('Neto de ventas en Dolares')
                ->descriptionIcon('heroicon-m-presentation-chart-line')
                ->color('success')
                ->chart([7, 2, 10, 3, 15, 4, 17]),
            Stat::make('Ventas(Bs.)', 'Bs. '.number_format(VentaServicio::sum('pago_bsd'), 2, ',', '.'))
                ->description('Neto de ventas en Bolívares')
                ->descriptionIcon('heroicon-m-presentation-chart-line')
                ->color('info')
                ->chart([7, 2, 10, 3, 15, 4, 17]),
            Stat::make('Total Servicios', Disponible::count())
                ->description('Total de servicios realizados')
                ->descriptionIcon('heroicon-s-users')
                ->color('gray')
                ->chart([7, 2, 10, 3, 15, 4, 17]),
            Stat::make('Productos', Producto::count())
                ->description('Total de Productos en inventario')
                ->descriptionIcon('heroicon-s-users')
                ->color('warning')
                ->chart([7, 2, 10, 3, 15, 4, 17]),
        ];
    }

    public function getColumns(): int
    {
        return 4;
    }
}
