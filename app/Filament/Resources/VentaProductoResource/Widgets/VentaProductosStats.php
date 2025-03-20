<?php

namespace App\Filament\Resources\VentaProductoResource\Widgets;

use App\Filament\Resources\VentaProductoResource\Pages\ListVentaProductos;
use App\Models\VentaProducto;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Flowframe\Trend\Trend;

class VentaProductosStats extends BaseWidget
{
    use InteractsWithPageTable;

    protected static ?string $pollingInterval = null;

    protected function getTablePage(): string
    {
        return ListVentaProductos::class;
    }

    protected function getStats(): array
    {

        return [

            Stat::make('CANTIDAD DE PRODUCTOS VENDIDOS', $this->getPageTableQuery()->sum('cantidad'))
            ->description('Cantidad Total de productos vendidos')
            ->descriptionIcon('heroicon-m-user-group')
            ->color('primary')
            ->extraAttributes(['class' => 'col-span-2 row-span-1 rounded-md text-center border-4 border-[#bf9c999e]']),

            Stat::make('TOTAL VENTA($)', '$' . $this->getPageTableQuery()->sum('total_venta'))
            ->description('Total de productos vendidos')
            ->descriptionIcon('heroicon-m-user-group')
            ->color('primary')
            ->extraAttributes(['class' => 'col-span-2 row-span-1 rounded-md text-center border-4 border-[#7B9AA6]']),

        ];
    }

    public function getColumns(): int
    {
        return 2;
    }
}
