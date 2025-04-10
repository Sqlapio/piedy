<?php

namespace App\Filament\Resources\AuditoriaInventarioResource\Pages;

use App\Filament\Resources\AuditoriaInventarioResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAuditoriaInventario extends EditRecord
{
    protected static string $resource = AuditoriaInventarioResource::class;

    protected static ?string $title = 'Detalle de Auditoria de Inventario';
    

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