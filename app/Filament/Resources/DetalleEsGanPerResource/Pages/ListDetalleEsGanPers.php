<?php

namespace App\Filament\Resources\DetalleEsGanPerResource\Pages;

use App\Filament\Resources\DetalleEsGanPerResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDetalleEsGanPers extends ListRecords
{
    protected static string $resource = DetalleEsGanPerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
