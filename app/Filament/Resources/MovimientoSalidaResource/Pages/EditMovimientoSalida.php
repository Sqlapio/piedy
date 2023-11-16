<?php

namespace App\Filament\Resources\MovimientoSalidaResource\Pages;

use App\Filament\Resources\MovimientoSalidaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMovimientoSalida extends EditRecord
{
    protected static string $resource = MovimientoSalidaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
