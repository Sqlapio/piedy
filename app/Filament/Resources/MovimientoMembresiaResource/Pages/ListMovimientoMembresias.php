<?php

namespace App\Filament\Resources\MovimientoMembresiaResource\Pages;

use App\Filament\Resources\MovimientoMembresiaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMovimientoMembresias extends ListRecords
{
    protected static string $resource = MovimientoMembresiaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
