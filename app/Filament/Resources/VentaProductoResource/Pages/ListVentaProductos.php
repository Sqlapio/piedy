<?php

namespace App\Filament\Resources\VentaProductoResource\Pages;

use App\Filament\Resources\VentaProductoResource;
use Filament\Actions;
use App\Models\VentaProducto;
use Filament\Resources\Pages\ListRecords;
use Filament\Pages\Concerns\ExposesTableToWidgets;

class ListVentaProductos extends ListRecords
{
    use ExposesTableToWidgets;

    protected ?string $heading = 'Modulo Venta Productos';
    protected static string $resource = VentaProductoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            VentaProductoResource\Widgets\VentaProductosStats::class,
        ];
    }
}