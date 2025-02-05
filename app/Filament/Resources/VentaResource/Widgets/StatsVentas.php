<?php

namespace App\Filament\Resources\VentaResource\Widgets;

use App\Models\Venta;
use App\Models\Cliente;
use App\Models\Producto;
use App\Models\Disponible;
use App\Models\VentaServicio;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use App\Filament\Resources\VentaResource\Pages\ListVentas;
use App\Filament\Resources\VentaServicioResource\Pages\ListVentaServicios;

class StatsVentas extends BaseWidget
{
    use InteractsWithPageTable;

    protected function getTablePage(): string
    {
        return ListVentas::class;
    }


    protected function getStats(): array
    {

        return [
            Stat::make('TOTAL VENTA', '$' . $this->getPageTableQuery()->sum('total_venta'))
                ->description('Total de pagos en Dolares')
                ->descriptionIcon('heroicon-m-arrow-trending-down')
                ->extraAttributes(['class' => 'text-center col-span-1 row-span-1'])
                ->color('warning'),
            // Stat::make('TOTAL PRODUCTOS', '$' . $this->getPageTableQuery()->sum('total_venta'))
            //     ->description('Total de pagos en Bolívares')
            //     ->descriptionIcon('heroicon-m-arrow-trending-down')
            //     ->extraAttributes(['class' => 'text-center col-span-1 row-span-1'])
            //     ->color('primary'),
            // Stat::make('TOTAL DE VENTAS', '$' .  Venta::sum('total_venta'))
            //     ->description('Total neto de ventas')
            //     ->descriptionIcon('heroicon-m-arrow-trending-down')
            //     ->extraAttributes(['class' => 'text-center col-span-2 row-span-1'])
            //     ->color('success'),
        ];
    }

    public function getColumns(): int
    {
        return 1;
    }
}