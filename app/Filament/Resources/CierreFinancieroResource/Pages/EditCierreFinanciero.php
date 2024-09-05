<?php

namespace App\Filament\Resources\CierreFinancieroResource\Pages;

use App\Filament\Resources\CierreFinancieroResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCierreFinanciero extends EditRecord
{
    protected static string $resource = CierreFinancieroResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
