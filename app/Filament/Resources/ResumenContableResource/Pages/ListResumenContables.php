<?php

namespace App\Filament\Resources\ResumenContableResource\Pages;

use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\ResumenContableResource;

class ListResumenContables extends ListRecords
{
    protected static string $resource = ResumenContableResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
            // Action::make('update')
            // ->label('Actualizar Registros')
            // ->color('success')
            // ->requiresConfirmation()
            // ->action(function () {
            //     $compras = Compra::
            // }),
        ];
    }
}