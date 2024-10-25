<?php

namespace App\Filament\Resources\InventarioSucursalResource\Pages;

use App\Filament\Resources\InventarioSucursalResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInventarioSucursal extends EditRecord
{
    protected static string $resource = InventarioSucursalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
