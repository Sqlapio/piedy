<?php

namespace App\Filament\Resources\ConsumoTecnicoResource\Pages;

use App\Filament\Resources\ConsumoTecnicoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListConsumoTecnicos extends ListRecords
{
    protected static string $resource = ConsumoTecnicoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
