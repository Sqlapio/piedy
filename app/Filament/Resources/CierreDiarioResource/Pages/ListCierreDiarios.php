<?php

namespace App\Filament\Resources\CierreDiarioResource\Pages;

use App\Filament\Resources\CierreDiarioResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCierreDiarios extends ListRecords
{
    protected static string $resource = CierreDiarioResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}
