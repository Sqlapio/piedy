<?php

namespace App\Filament\Resources\AsignarProductoResource\Pages;

use App\Filament\Resources\AsignarProductoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAsignarProducto extends EditRecord
{
    protected static string $resource = AsignarProductoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
