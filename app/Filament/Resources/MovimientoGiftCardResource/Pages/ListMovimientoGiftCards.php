<?php

namespace App\Filament\Resources\MovimientoGiftCardResource\Pages;

use App\Filament\Resources\MovimientoGiftCardResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMovimientoGiftCards extends ListRecords
{
    protected static string $resource = MovimientoGiftCardResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
