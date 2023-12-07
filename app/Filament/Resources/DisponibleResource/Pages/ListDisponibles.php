<?php

namespace App\Filament\Resources\DisponibleResource\Pages;

use App\Filament\Resources\DisponibleResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ListRecords\Tab;

class ListDisponibles extends ListRecords
{
    protected static string $resource = DisponibleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'Todo' => Tab::make(),
            'Activo' => Tab::make()->query(fn ($query) => $query->where('status', 'activo')),
            'Por facturar' => Tab::make()->query(fn ($query) => $query->where('status', 'por facturar')),
            'Facturado' => Tab::make()->query(fn ($query) => $query->where('status', 'facturado')),
            'Anulado' => Tab::make()->query(fn ($query) => $query->where('status', 'anulado')),
        ];
    }
}
