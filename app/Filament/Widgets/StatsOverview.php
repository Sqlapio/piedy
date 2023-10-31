<?php

namespace App\Filament\Widgets;

use App\Models\Cliente;
use App\Models\Producto;
use App\Models\VentaServicio;
use App\Models\Disponible;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    // protected static ?string $pollingInterval = '10s';

    protected function getStats(): array
    {
        return [
            Stat::make('Total Ventas', '$'.VentaServicio::sum('total'))
                ->description('Total neto de ventas')
                ->descriptionIcon('heroicon-m-presentation-chart-line')
                ->color('success')
                ->chart([7, 2, 10, 3, 15, 4, 17]),
            Stat::make('Total Clientes', Cliente::count())
                ->description('Clientes registrados')
                ->descriptionIcon('heroicon-m-arrow-trending-down')
                ->color('success')
                ->chart([7, 2, 10, 3, 15, 4, 17]),
            Stat::make('Total Productos', Producto::count())
                ->description('Productos registrados')
                ->descriptionIcon('heroicon-s-users')
                ->color('success')
                ->chart([7, 2, 10, 3, 15, 4, 17]),
            Stat::make('Cabinas', Disponible::count())
                ->description('Cantidad de cubiculos en eso')
                ->descriptionIcon('heroicon-s-users')
                ->color('success')
                ->chart([7, 2, 10, 3, 15, 4, 17]),
        ];
    }
}
