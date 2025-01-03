<?php

namespace App\Filament\Resources\ConsumoTecnicoResource\Pages;

use App\Filament\Resources\ConsumoTecnicoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditConsumoTecnico extends EditRecord
{
    protected static string $resource = ConsumoTecnicoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
