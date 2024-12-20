<?php

namespace App\Filament\Resources\PreNominaResource\Pages;

use App\Filament\Resources\PreNominaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPreNomina extends EditRecord
{
    protected static string $resource = PreNominaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
