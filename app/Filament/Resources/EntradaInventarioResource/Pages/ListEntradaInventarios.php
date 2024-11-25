<?php

namespace App\Filament\Resources\EntradaInventarioResource\Pages;

use App\Filament\Resources\EntradaInventarioResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEntradaInventarios extends ListRecords
{
    protected static string $resource = EntradaInventarioResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}