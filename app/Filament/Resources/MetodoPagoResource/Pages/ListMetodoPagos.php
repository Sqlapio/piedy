<?php

namespace App\Filament\Resources\MetodoPagoResource\Pages;

use App\Filament\Resources\MetodoPagoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMetodoPagos extends ListRecords
{
    protected static string $resource = MetodoPagoResource::class;

    protected ?string $heading = 'Metodos de Pago';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}