<?php

namespace App\Filament\Resources\MovimientoInventarioSucursalResource\Pages;

use App\Filament\Resources\MovimientoInventarioSucursalResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMovimientoInventarioSucursal extends EditRecord
{
    protected static string $resource = MovimientoInventarioSucursalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
