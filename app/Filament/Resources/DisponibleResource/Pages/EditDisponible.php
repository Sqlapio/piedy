<?php

namespace App\Filament\Resources\DisponibleResource\Pages;

use App\Filament\Resources\DisponibleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDisponible extends EditRecord
{
    protected static string $resource = DisponibleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
