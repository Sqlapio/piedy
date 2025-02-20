<?php

namespace App\Filament\Resources\LibroCompraResource\Pages;

use App\Filament\Resources\LibroCompraResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLibroCompras extends ListRecords
{
    protected static string $resource = LibroCompraResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
