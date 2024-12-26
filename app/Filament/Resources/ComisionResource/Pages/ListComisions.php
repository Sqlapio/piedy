<?php

namespace App\Filament\Resources\ComisionResource\Pages;

use App\Filament\Resources\ComisionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListComisions extends ListRecords
{
    protected static string $resource = ComisionResource::class;

    protected ?string $heading = 'Modulo de Comisiones';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}