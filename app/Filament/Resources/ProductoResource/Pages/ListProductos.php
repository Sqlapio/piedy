<?php

namespace App\Filament\Resources\ProductoResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\ProductoResource;
use App\Models\Producto;
use Filament\Resources\Pages\ListRecords\Tab;

class ListProductos extends ListRecords
{
    protected static string $resource = ProductoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {

        return [

            'Todo' => ListRecords\Tab::make('Todo')->query(fn($query) => $query->orderBy('created_at', 'desc')),
            'Consumo-interno' => Tab::make()
                ->query(fn($query) => $query->where('uso', 'consumo-interno'))
                ->badge(Producto::query()->where('uso', 'consumo-interno')->count()),
            'Venta' => Tab::make()
                ->query(fn($query) => $query->where('uso', 'venta'))
                ->badge(Producto::query()->where('uso', 'venta')->count()),
        ];
    }
}