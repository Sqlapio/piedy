<?php

namespace App\Filament\Resources\DetalleEsGanPerResource\Pages;

use Filament\Actions;
use Filament\Actions\Action;
use App\Models\DetalleEsGanPer;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\DetalleEsGanPerResource;

class ListDetalleEsGanPers extends ListRecords
{
    protected static string $resource = DetalleEsGanPerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make('Crear Asiento')
                ->label('Crear Asiento')
                ->color('success'),
            
            Action::make('Cerrar Asiento')
                ->label('Cerrar Asiento')
                ->requiresConfirmation()
                ->color('danger')
                ->action(function () {
                    $detalles = DetalleEsGanPer::all()->last();
                    $detalles->status = 'cerrado';
                    $detalles->save();
                })
        ];
    }
}