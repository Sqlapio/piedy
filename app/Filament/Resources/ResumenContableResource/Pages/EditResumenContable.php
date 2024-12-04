<?php

namespace App\Filament\Resources\ResumenContableResource\Pages;

use App\Filament\Resources\ResumenContableResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditResumenContable extends EditRecord
{
    protected static string $resource = ResumenContableResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
