<?php

namespace App\Filament\Resources\MetodoPagoResource\Pages;

use App\Filament\Resources\MetodoPagoResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateMetodoPago extends CreateRecord
{
    protected static string $resource = MetodoPagoResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Método de pago Registrado')
            ->body('El método de pago fue creado con exito.');
    }
}
