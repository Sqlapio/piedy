<?php

namespace App\Filament\Resources\MovimientoEntradaResource\Pages;

use App\Filament\Resources\MovimientoEntradaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMovimientoEntrada extends EditRecord
{
    protected static string $resource = MovimientoEntradaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
