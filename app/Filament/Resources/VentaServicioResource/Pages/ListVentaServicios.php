<?php

namespace App\Filament\Resources\VentaServicioResource\Pages;

use App\Filament\Resources\VentaServicioResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

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
}
