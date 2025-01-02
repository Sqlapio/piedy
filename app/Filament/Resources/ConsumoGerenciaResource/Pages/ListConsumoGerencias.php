<?php

namespace App\Filament\Resources\ConsumoGerenciaResource\Pages;

use App\Filament\Resources\ConsumoGerenciaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListConsumoGerencias extends ListRecords
{
    protected static string $resource = ConsumoGerenciaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
