<?php

namespace App\Filament\Resources\VentaServicioResource\Widgets;

use App\Models\Cliente;
use App\Models\Disponible;
use App\Models\Producto;
use App\Models\VentaServicio;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class VentaServicioStats extends BaseWidget
{
    use InteractsWithPageTable;

    protected static ?string $pollingInterval = null;

    protected function getTablePage(): string
    {
        return VentaServicio::class;
    }

    protected function getStats(): array
    {
        $data = Trend::model(VentaServicio::class)
            ->between(
                start: now()->subYear(),
                end: now(),
            )
            ->perMonth()
            ->count();

        return [
            // Stat::make('Total Ventas', '$'.$this->getPageTableQuery()->count())

            //     ->description('Total neto de ventas')
            //     ->descriptionIcon('heroicon-m-presentation-chart-line')
            //     ->color('success')
            //     ->chart(
            //         $data
            //             ->map(fn (TrendValue $value) => $value->aggregate)
            //             ->toArray()
            //     ),
            // Stat::make('Total pagos($)', '$'.VentaServicio::sum('pago_usd'))
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
}
