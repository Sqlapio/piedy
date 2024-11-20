<?php

namespace App\Filament\Resources\NotificacionMasivaResource\Pages;

use App\Filament\Resources\NotificacionMasivaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditNotificacionMasiva extends EditRecord
{
    protected static string $resource = NotificacionMasivaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
