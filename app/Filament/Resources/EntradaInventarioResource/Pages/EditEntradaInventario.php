<?php

namespace App\Filament\Resources\EntradaInventarioResource\Pages;

use App\Filament\Resources\EntradaInventarioResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEntradaInventario extends EditRecord
{
    protected static string $resource = EntradaInventarioResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
