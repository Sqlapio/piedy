<?php

namespace App\Filament\Resources\MovimientoInventarioSucursalResource\Pages;

use App\Filament\Resources\MovimientoInventarioSucursalResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMovimientoInventarioSucursals extends ListRecords
{
    protected ?string $heading = 'Movimiento de Inventario por Sucursales';
    
    protected static string $resource = MovimientoInventarioSucursalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}