<?php

namespace App\Filament\Resources\DetalleAsignacionResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use App\Filament\Resources\DetalleAsignacionResource;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class ListDetalleAsignacions extends ListRecords
{

    use ExposesTableToWidgets;
    use InteractsWithPageFilters;
    
    protected static string $resource = DetalleAsignacionResource::class;

    //label
    protected static ?string $title = 'Detalle de Asignacion de Servicios/Productos';

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            DetalleAsignacionResource\Widgets\QuiropediaStats::class,
            DetalleAsignacionResource\Widgets\QuiropediaDosStats::class,

        ];
    }
}