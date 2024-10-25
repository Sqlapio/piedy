<?php

namespace App\Filament\Resources\InventarioSucursalResource\Pages;

use App\Filament\Resources\InventarioSucursalResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInventarioSucursals extends ListRecords
{
    protected ?string $heading = 'Inventario Sucursales';

    protected static string $resource = InventarioSucursalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}
