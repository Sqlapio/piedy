<?php

namespace App\Filament\Resources\VentaServicioResource\Pages;

use App\Filament\Resources\VentaServicioResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditVentaServicio extends EditRecord
{
    protected static string $resource = VentaServicioResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
