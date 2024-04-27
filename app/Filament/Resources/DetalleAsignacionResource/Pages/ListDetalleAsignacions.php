<?php

namespace App\Filament\Resources\DetalleAsignacionResource\Pages;

use App\Filament\Resources\DetalleAsignacionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDetalleAsignacions extends ListRecords
{
    protected static string $resource = DetalleAsignacionResource::class;

    protected ?string $heading = 'Dashboard Empleados';
    // protected ?string $subheading = 'Lista de Empleados';

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}
