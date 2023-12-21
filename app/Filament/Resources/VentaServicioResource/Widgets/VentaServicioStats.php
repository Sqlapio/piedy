<?php

namespace App\Filament\Resources\VentaServicioResource\Widgets;

use App\Filament\Resources\VentaServicioResource;
use App\Filament\Resources\VentaServicioResource\Pages\ListVentaServicios;
use App\Models\Cliente;
use App\Models\Disponible;
use App\Models\Producto;
use App\Models\VentaServicio;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Illuminate\Support\Facades\DB;

class VentaServicioStats extends BaseWidget
{
    use InteractsWithPageTable;

    protected static ?string $pollingInterval = null;

    protected function getTablePage(): string
    {
        return ListVentaServicios::class;
    }

    protected function getStats(): array
    {
        $data = Trend::model(VentaServicio::class)
            ->between(
                start: now()->subYear(),
                end: now(),
            )
            ->perMonth()
            ->count('cliente');

        return [

            Stat::make('CLIENTES', $this->getPageTableQuery()->count('cliente'))
                ->description('Total de clientes atendidos')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('info')
                ->chart(
                    $data
                        ->map(fn (TrendValue $value) => $value->aggregate)
                        ->toArray()
                ),

            Stat::make('TOTAL USD($)', '$' . $this->getPageTableQuery()->sum('pago_usd'))
                ->description('Total neto de ventas en USD($)')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('success')
                ->chart(
                    $data
                        ->map(fn (TrendValue $value) => $value->aggregate)
                        ->toArray()
                ),
            Stat::make('TOTAL BS.', 'BS.' . $this->getPageTableQuery()->sum('pago_bsd'))
                ->description('Total neto de ventas en Bs')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('warning')
                ->chart(
                    $data
                        ->map(fn (TrendValue $value) => $value->aggregate)
                        ->toArray()
                ),
        ];
    }
}
