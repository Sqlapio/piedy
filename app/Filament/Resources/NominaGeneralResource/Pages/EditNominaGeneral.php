<?php

namespace App\Filament\Resources\NominaGeneralResource\Pages;

use App\Filament\Resources\NominaGeneralResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditNominaGeneral extends EditRecord
{
    protected static string $resource = NominaGeneralResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\DeleteAction::make(),
        ];
    }

    protected function getFormActions(): array
    {
        return [
            // ...parent::getFormActions(),
            // Action::make('close')->action('saveAndClose'),
        ];
    }
}