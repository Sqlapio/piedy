<?php

namespace App\Filament\Resources\PonderacionResource\Pages;

use App\Filament\Resources\PonderacionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPonderacions extends ListRecords
{
    protected static string $resource = PonderacionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
