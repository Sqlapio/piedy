<?php

namespace App\Filament\Resources\PreNominaResource\Pages;

use App\Filament\Resources\PreNominaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPreNominas extends ListRecords
{
    protected static string $resource = PreNominaResource::class;

    protected ?string $heading = 'Modulo de Nomina';

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}