<?php

namespace App\Filament\Resources\MetodoPrepagoResource\Pages;

use App\Filament\Resources\MetodoPrepagoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMetodoPrepagos extends ListRecords
{
    protected static string $resource = MetodoPrepagoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
