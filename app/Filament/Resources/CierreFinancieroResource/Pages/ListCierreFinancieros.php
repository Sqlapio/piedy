<?php

namespace App\Filament\Resources\CierreFinancieroResource\Pages;

use App\Filament\Resources\CierreFinancieroResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCierreFinancieros extends ListRecords
{
    protected static string $resource = CierreFinancieroResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
