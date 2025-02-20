<?php

namespace App\Filament\Resources\LibroCompraResource\Pages;

use App\Filament\Resources\LibroCompraResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLibroCompra extends EditRecord
{
    protected static string $resource = LibroCompraResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
