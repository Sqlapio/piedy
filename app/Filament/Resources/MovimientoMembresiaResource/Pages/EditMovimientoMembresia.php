<?php

namespace App\Filament\Resources\MovimientoMembresiaResource\Pages;

use App\Filament\Resources\MovimientoMembresiaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMovimientoMembresia extends EditRecord
{
    protected static string $resource = MovimientoMembresiaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
