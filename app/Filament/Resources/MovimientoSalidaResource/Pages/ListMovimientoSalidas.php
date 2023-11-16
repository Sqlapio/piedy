<?php

namespace App\Filament\Resources\MovimientoSalidaResource\Pages;

use App\Filament\Resources\MovimientoSalidaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMovimientoSalidas extends ListRecords
{
    protected static string $resource = MovimientoSalidaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
