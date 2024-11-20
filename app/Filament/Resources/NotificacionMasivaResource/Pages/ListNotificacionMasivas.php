<?php

namespace App\Filament\Resources\NotificacionMasivaResource\Pages;

use App\Filament\Resources\NotificacionMasivaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListNotificacionMasivas extends ListRecords
{
    protected static string $resource = NotificacionMasivaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
