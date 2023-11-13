<?php

namespace App\Filament\Resources\DisponibleResource\Pages;

use App\Filament\Resources\DisponibleResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDisponibles extends ListRecords
{
    protected static string $resource = DisponibleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}
