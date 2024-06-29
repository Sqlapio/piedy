<?php

namespace App\Filament\Widgets;

use App\Models\Cliente;
use App\Models\Producto;
use App\Models\VentaServicio;
use App\Models\Disponible;
use App\Models\Frecuencia;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;


class StatsOverviewDos extends BaseWidget
{

    protected function getStats(): array
    {
        return [

            Stat::make('Clientes Registrados', Cliente::count())
                ->description('Total de Clientes registrados')
                ->descriptionIcon('heroicon-m-arrow-trending-down')
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
        return 2;
    }
}
