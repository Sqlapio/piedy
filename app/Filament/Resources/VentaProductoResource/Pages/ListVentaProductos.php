<?php

namespace App\Filament\Resources\VentaProductoResource\Pages;

use Filament\Actions;
use App\Models\VentaProducto;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ListRecords\Tab;
use App\Filament\Resources\VentaProductoResource;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class ListVentaProductos extends ListRecords
{
    use ExposesTableToWidgets;
    use InteractsWithPageFilters;

    protected ?string $heading = 'Venta Productos';
    
    protected static string $resource = VentaProductoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }

    protected function getFooterWidgets(): array
    {
        // dd($this->tableFilters);
        return [
            VentaProductoResource\Widgets\VentaProductosStats::class,
            VentaProductoResource\Widgets\ChartAverage::make([
                'filters_pages_resources' => $this->tableFilters['created_at'],
            ]),
            
        ];
    }

    public function getTabs(): array
    {

        return [

            'Todo' => ListRecords\Tab::make('Todo')->query(fn($query) => $query->orderBy('created_at', 'desc'))->badge(VentaProducto::query()->whereBetween('created_at', [now()->startOfYear(), now()->endOfYear()])->sum('cantidad')),
            'Hoy' => Tab::make()
                ->query(fn($query) => $query->whereDate('created_at', now()->toDateString()))
                ->badge(VentaProducto::query()->whereDate('created_at', now()->toDateString())->sum('cantidad')),
            'Nro. de Ventas del Mes' => Tab::make()
                ->query(fn($query) => $query->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()]))
                ->badge(VentaProducto::query()->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])->sum('cantidad')),
        ];
    }
}