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

            Stat::make('TOTAL PRODUCTOS VENDIDOS', $this->getPageTableQuery()->sum('cantidad'))
            ->description('Total de productos vendidos')
            ->descriptionIcon('heroicon-m-user-group')
            ->color('primary')
            ->extraAttributes(['class' => 'col-span-1 row-span-1 rounded-md text-center border-4 border-[#bf9c999e]']),

            Stat::make('TOTAL VENTA($)', '$' . $this->getPageTableQuery()->sum('total_venta'))
            ->description('Total de productos vendidos')
            ->descriptionIcon('heroicon-m-user-group')
            ->color('primary')
            ->extraAttributes(['class' => 'col-span-1 row-span-1 rounded-md text-center border-4 border-[#7B9AA6]']),

            Stat::make('COMISIONES POR TECNICO', '$'. $this->getPageTableQuery()->sum('comision_empleado'))
            ->description('Total de comisiones por tecnico')
            ->descriptionIcon('heroicon-m-user-group')
            ->color('info')
            ->extraAttributes(['class' => 'col-span-1 row-span-1 rounded-md text-center border-4 border-[#3ec7d28a]' ]),

            Stat::make('COMISIONES POR GERENTE', '$' . $this->getPageTableQuery()->sum('comision_gerente'))
            ->description('Total de comisiones por gerente')
            ->descriptionIcon('heroicon-m-currency-dollar')
            ->color('success')
            ->extraAttributes(['class' => 'col-span-1 row-span-1 rounded-md text-center border-4 border-[#9bad699e]']),

        ];
    }

    public function getColumns(): int
    {
        return 4;
    }
}
