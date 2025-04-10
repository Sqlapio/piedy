<?php

namespace App\Filament\Resources\AuditoriaInventarioResource\Pages;

use App\Filament\Resources\AuditoriaInventarioResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAuditoriaInventarios extends ListRecords
{
    protected static string $resource = AuditoriaInventarioResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}