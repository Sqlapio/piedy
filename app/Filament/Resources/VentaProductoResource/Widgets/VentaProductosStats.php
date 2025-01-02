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
            ->color('primary'),

            Stat::make('TOTAL VENTA($)', '$' . $this->getPageTableQuery()->sum('total_venta'))
            ->description('Total de productos vendidos')
            ->descriptionIcon('heroicon-m-user-group')
            ->color('primary'),

            Stat::make('COMISIONES POR TECNICO', '$'. $this->getPageTableQuery()->sum('comision_empleado'))
            ->description('Total de comisiones por tecnico')
            ->descriptionIcon('heroicon-m-user-group')
            ->color('info'),

            Stat::make('COMISIONES POR GERENTE', '$' . $this->getPageTableQuery()->sum('comision_gerente'))
            ->description('Total de comisiones por gerente')
            ->descriptionIcon('heroicon-m-currency-dollar')
            ->color('success'),

            // Stat::make('PRODUCTO MAS VENDIDO', $this->getPageTableQuery()->max('cantidad'))
            // ->description('Total de comisiones por gerente')
            // ->descriptionIcon('heroicon-m-currency-dollar')
            // ->color('success'),

            // Stat::make('PRODUCTO MENOS VENDIDO ', $this->getPageTableQuery()->min('cantidad'))
            // ->description('Total de comisiones por gerente')
            // ->descriptionIcon('heroicon-m-currency-dollar')
            // ->color('success'),

        ];
    }

    public function getColumns(): int
    {
        return 2;
    }
}