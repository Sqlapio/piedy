<?php

namespace App\Filament\Resources\ComisionResource\Pages;

use App\Filament\Resources\ComisionResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditComision extends EditRecord
{
    protected static string $resource = ComisionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
        ->success()
        ->title('Comisión editada')
        ->body('La comisión fue editada con exito.');
    }

}
