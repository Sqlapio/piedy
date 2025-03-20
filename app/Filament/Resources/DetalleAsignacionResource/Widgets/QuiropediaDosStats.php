<?php

namespace App\Filament\Resources\DetalleAsignacionResource\Widgets;

use Flowframe\Trend\Trend;
use App\Models\VentaServicio;
use App\Models\DetalleAsignacion;
use Illuminate\Support\Facades\DB;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use App\Filament\Resources\VentaServicioResource\Pages\ListVentaServicios;
use App\Filament\Resources\DetalleAsignacionResource\Pages\ListDetalleAsignacions;

class QuiropediaDosStats extends BaseWidget
{
    use InteractsWithPageTable;

    protected function getTablePage(): string
    {
        return ListDetalleAsignacions::class;
    }

    protected function getStats(): array
    {
        return [

            Stat::make('QUIROPEDIA MEDICA', $this->getPageTableQuery()->where('tipo', 'servicio')->where('servicio_id', 6)->where('status', 2)->count())
                ->description('Total realizadas')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('info')
                ->extraAttributes(['class' => 'col-span-4 row-span-1 rounded-md text-center border-4 bg-99a9bf']),

            Stat::make('QUIROPEDIA PIE DIABETICO', $this->getPageTableQuery()->where('tipo', 'servicio')->where('servicio_id', 7)->where('status', 2)->count())
                ->description('Total realizadas')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('info')
                ->extraAttributes(['class' => 'col-span-4 row-span-1 rounded-md text-center border-4 bg-99bfaf']),

            Stat::make('QUIROPEDIA PARA NIÑOS', $this->getPageTableQuery()->where('tipo', 'servicio')->where('servicio_id', 8)->where('status', 2)->count())
                ->description('Total realizadas')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('success')
                ->extraAttributes(['class' => 'col-span-4 row-span-1 rounded-md text-center border-4 bg-9c99bf']),

        ];
    }

    //getColumns()
    public function getColumns(): int
    {
        return 3;
    }



}
