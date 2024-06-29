<?php

namespace App\Filament\Widgets;

use App\Models\Cliente;
use App\Models\Producto;
use App\Models\VentaServicio;
use App\Models\Disponible;
use App\Models\Frecuencia;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;


class StatsOverviewRealTimeRowTwo extends BaseWidget
{
    // protected static ?string $pollingInterval = '10s';

    protected function getStats(): array
    {
        return [

            Stat::make('Quiropedistas Activos', Disponible::where('area_trabajo', 'quiropedia')
                    ->where('status', 'activo')
                    ->whereBetween('created_at', [date('Y-m-d').' 00:00:00.000', date('Y-m-d').' 23:59:59.000'])
                    ->count())
                ->description('Quiropedistas activos con cliente')
                ->descriptionIcon('heroicon-s-users')
                ->color('success')
                ->chart([7, 2, 10, 3, 15, 4, 17]),
            Stat::make('Manicuristas Activas', Disponible::where('area_trabajo', 'manicure')
                    ->where('status', 'activo')
                    ->whereBetween('created_at', [date('Y-m-d').' 00:00:00.000', date('Y-m-d').' 23:59:59.000'])
                    ->count())
                ->description('Manicuristas activas con cliente')
                ->descriptionIcon('heroicon-s-users')
                ->color('info')
                ->chart([7, 2, 10, 3, 15, 4, 17]),
        ];
    }

    public function getColumns(): int
    {
        return 2;
    }
}
