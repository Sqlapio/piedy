<?php

namespace App\Filament\Supervisor\Resources\CierreDiarioResource\Pages;

use App\Filament\Supervisor\Resources\CierreDiarioResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCierreDiario extends EditRecord
{
    protected static string $resource = CierreDiarioResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
