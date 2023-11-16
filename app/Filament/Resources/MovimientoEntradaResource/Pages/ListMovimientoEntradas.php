<?php

namespace App\Filament\Resources\MovimientoEntradaResource\Pages;

use App\Filament\Resources\MovimientoEntradaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMovimientoEntradas extends ListRecords
{
    protected static string $resource = MovimientoEntradaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
