<?php

namespace App\Filament\Resources\AsignarProductoResource\Pages;

use App\Filament\Resources\AsignarProductoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAsignarProductos extends ListRecords
{
    protected static string $resource = AsignarProductoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
