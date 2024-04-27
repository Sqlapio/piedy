<?php

namespace App\Filament\Resources\DetalleAsignacionResource\Pages;

use App\Filament\Resources\DetalleAsignacionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDetalleAsignacion extends EditRecord
{
    protected static string $resource = DetalleAsignacionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
