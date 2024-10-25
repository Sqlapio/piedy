<?php

namespace App\Filament\Resources\LogInventarioResource\Pages;

use App\Filament\Resources\LogInventarioResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLogInventarios extends ListRecords
{
    protected static string $resource = LogInventarioResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}
