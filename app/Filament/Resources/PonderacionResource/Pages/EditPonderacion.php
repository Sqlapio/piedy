<?php

namespace App\Filament\Resources\PonderacionResource\Pages;

use App\Filament\Resources\PonderacionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPonderacion extends EditRecord
{
    protected static string $resource = PonderacionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
