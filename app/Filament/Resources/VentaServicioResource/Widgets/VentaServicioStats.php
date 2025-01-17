<?php

namespace App\Filament\Resources\VentaServicioResource\Widgets;

use App\Filament\Resources\VentaServicioResource\Pages\ListVentaServicios;
use App\Models\VentaServicio;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Flowframe\Trend\Trend;

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
            ->count('cliente_id');

        return [

            Stat::make('TOTAL SERVICIOS', $this->getPageTableQuery()->count('cliente_id'))
                ->description('Total de servicios realizados')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('primary')
                ->extraAttributes(['class' => 'col-span-2 row-span-1 rounded-md text-center border-4 border-[#3ec7d28a]' ]),

            Stat::make('CLIENTES ATENDIDOS', $this->getPageTableQuery()->distinct()->count('cliente_id'))
                ->description('Total de clientes atendidos')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('info')
                ->extraAttributes(['class' => 'col-span-2 row-span-1 rounded-md text-center border-4 border-[#7B9AA6]']),

            Stat::make('TOTAL USD($)', '$' . $this->getPageTableQuery()->sum('pago_usd'))
                ->description('Pago total en USD($)')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('success')
                ->extraAttributes(['class' => 'col-span-2 row-span-1 rounded-md text-center border-4 border-[#bf9c999e]']),

            Stat::make('TOTAL BS.', 'BS.' . $this->getPageTableQuery()->sum('pago_bsd'))
                ->description('Pago total en Bs')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('warning')
                ->extraAttributes(['class' => 'col-span-2 row-span-1 rounded-md text-center border-4 border-[#9bad699e]']),

        ];
    }
}
