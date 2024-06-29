<?php

namespace App\Filament\Widgets;

use App\Models\Cliente;
use App\Models\Producto;
use App\Models\VentaServicio;
use App\Models\Disponible;
use App\Models\Frecuencia;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;


class StatsOverviewRealTime extends BaseWidget
{
    // protected static ?string $pollingInterval = '10s';

    protected function getStats(): array
    {
        return [

            Stat::make('Clientes Nuevos', Frecuencia::whereBetween('created_at', [date('Y-m-d').' 00:00:00.000', date('Y-m-d').' 23:59:59.000'])->count())
                ->description('Hoy '.date('d-m-Y'))
                ->descriptionIcon('heroicon-o-users')
                ->color('info')
                ->chart([7, 2, 10, 3, 15, 4, 17]),
            Stat::make('Servicios del día', VentaServicio::whereBetween('created_at', [date('Y-m-d').' 00:00:00.000', date('Y-m-d').' 23:59:59.000'])->count())
                ->description('Hoy '.date('d-m-Y'))
                ->description('Hoy '.date('d-m-Y'))
                ->descriptionIcon('heroicon-c-share')
                ->color('gray')
                ->chart([7, 2, 10, 3, 15, 4, 17]),
        ];
    }

    public function getColumns(): int
    {
        return 2;
    }
}
