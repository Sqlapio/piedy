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

class QuiropediaStats extends BaseWidget
{
    use InteractsWithPageTable;

    protected function getTablePage(): string
    {
        return ListDetalleAsignacions::class;
    }

    protected function getStats(): array
    {
        return [

            Stat::make('QUIROPEDIA EXPRESS', $this->getPageTableQuery()->where('tipo', 'servicio')->where('servicio_id', 5)->where('status', 2)->count())
                ->description('Total realizadas')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('primary')
                ->extraAttributes(['class' => 'col-span-2 row-span-1 rounded-md text-center border-4 border-[#3ec7d28a]']),

            Stat::make('QUIROPEDIA MEDICA', $this->getPageTableQuery()->where('tipo', 'servicio')->where('servicio_id', 6)->where('status', 2)->count())
                ->description('Total realizadas')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('info')
                ->extraAttributes(['class' => 'col-span-2 row-span-1 rounded-md text-center border-4 border-[#7B9AA6]']),

            Stat::make('QUIROPEDIA AVANZADA VIP', $this->getPageTableQuery()->where('tipo', 'servicio')->where('servicio_id', 2)->where('status', 2)->count())
                ->description('Total realizadas')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('success')
                ->extraAttributes(['class' => 'col-span-2 row-span-1 rounded-md text-center border-4 border-[#bf9c999e]']),

            Stat::make('QUIROPEDIA REJUVENECEDORA', $this->getPageTableQuery()->where('tipo', 'servicio')->where('servicio_id', 3)->where('status', 2)->count())
                ->description('Total realizadas')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('warning')
                ->extraAttributes(['class' => 'col-span-2 row-span-1 rounded-md text-center border-4 border-[#9bad699e]']),

        ];
    }
}