<?php

namespace App\Filament\Resources\ConsumibleResource\Pages;

use App\Filament\Resources\ConsumibleResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListConsumibles extends ListRecords
{
    protected static string $resource = ConsumibleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
