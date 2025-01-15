<?php

namespace App\Filament\Resources\EstadoGananciaPerdidaResource\Pages;

use App\Filament\Resources\EstadoGananciaPerdidaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEstadoGananciaPerdidas extends ListRecords
{
    protected static string $resource = EstadoGananciaPerdidaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
