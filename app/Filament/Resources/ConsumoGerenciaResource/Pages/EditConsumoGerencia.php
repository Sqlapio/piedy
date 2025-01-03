<?php

namespace App\Filament\Resources\ConsumoGerenciaResource\Pages;

use App\Filament\Resources\ConsumoGerenciaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditConsumoGerencia extends EditRecord
{
    protected static string $resource = ConsumoGerenciaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
