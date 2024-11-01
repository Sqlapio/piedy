<?php

namespace App\Filament\Resources\MetodoPrepagoResource\Pages;

use App\Filament\Resources\MetodoPrepagoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMetodoPrepago extends EditRecord
{
    protected static string $resource = MetodoPrepagoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
