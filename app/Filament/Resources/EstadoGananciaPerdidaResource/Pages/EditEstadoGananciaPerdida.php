<?php

namespace App\Filament\Resources\EstadoGananciaPerdidaResource\Pages;

use App\Filament\Resources\EstadoGananciaPerdidaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEstadoGananciaPerdida extends EditRecord
{
    protected static string $resource = EstadoGananciaPerdidaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
