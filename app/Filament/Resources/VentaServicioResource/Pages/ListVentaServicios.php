<?php

namespace App\Filament\Resources\VentaServicioResource\Pages;

use App\Filament\Resources\VentaServicioResource;
use App\Models\VentaServicio;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ListRecords\Tab;

class ListVentaServicios extends ListRecords
{
    protected static string $resource = VentaServicioResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            VentaServicioResource\Widgets\StatsVenta::class,
        ];
    }

    public function getTabs(): array
    {
        return [

            'Todo' => Tab::make(),
            'Hoy' => Tab::make()
                ->query(fn ($query) => $query->whereDate('created_at', now()->toDateString())),
                // ->badge(VentaServicio::query()->where('created_at', now()->toDateString())->count()),
            'Semanal' => Tab::make()
                ->query(fn ($query) => $query->whereDate('created_at', now()->subWeek())),
                // ->badge(VentaServicio::query()->where('created_at','<=', now()->subWeek())->count()),
            'Mensual' => Tab::make()
                ->query(fn ($query) => $query->whereDate('created_at', now()->subMonth())),
                // ->badge(VentaServicio::query()->where('created_at','<=', now()->subMonth())->count()),
        ];
    }
}
