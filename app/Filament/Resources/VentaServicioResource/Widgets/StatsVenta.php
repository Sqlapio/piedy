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

class StatsVenta extends BaseWidget
{
    use InteractsWithPageTable;

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
            Stat::make('Total Ventas', '$'.$this->getPageTableQuery()->count())

                ->description('Total neto de ventas')
                ->descriptionIcon('heroicon-m-presentation-chart-line')
                ->color('success')
                ->chart(
                    $data
                        ->map(fn (TrendValue $value) => $value->aggregate)
                        ->toArray()
                ),
        ];
    }
}
