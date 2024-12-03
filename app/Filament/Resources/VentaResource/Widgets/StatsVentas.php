<?php

namespace App\Filament\Resources\VentaResource\Widgets;

use App\Models\Venta;
use App\Models\Cliente;
use App\Models\Producto;
use App\Models\Disponible;
use App\Models\VentaServicio;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class StatsVentas extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Ventas en Divisas', '$'.Venta::sum('total_venta'))
                ->description('Total neto de ventas')
                ->descriptionIcon('heroicon-m-presentation-chart-line')
                ->color('success')
                ->chart([7, 2, 10, 3, 15, 4, 17]),
            // Stat::make('Total Ventas por Servicios', '$'.VentaServicio::sum('pago_usd'))
            //     ->description('Total de pagos en Dolares')
            //     ->descriptionIcon('heroicon-m-arrow-trending-down')
            //     ->color('warning')
            //     ->chart([7, 2, 10, 3, 15, 4, 17]),
            // Stat::make('Total pagos(Bs)', 'Bs'.VentaServicio::sum('pago_bsd'))
            //     ->description('Total de pagos en Bolívares')
            //     ->descriptionIcon('heroicon-s-users')
            //     ->color('primary')
            //     ->chart([7, 2, 1, 1, 15, 4, 2]),
        ];
    }

    public function getColumns(): int
    {
        return 2;
    }
}