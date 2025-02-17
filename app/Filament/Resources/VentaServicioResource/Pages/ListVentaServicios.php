<?php

namespace App\Filament\Resources\VentaServicioResource\Pages;

use Filament\Actions;
use App\Models\VentaServicio;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ListRecords\Tab;
use App\Filament\Resources\VentaServicioResource;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class ListVentaServicios extends ListRecords
{
    use ExposesTableToWidgets;
    use InteractsWithPageFilters;

    protected ?string $heading = 'Ventas Servicios';

    protected static string $resource = VentaServicioResource::class;

    protected int | string | array $columnSpan = [
        'md' => 2,
        'xl' => 3,
    ];

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            VentaServicioResource\Widgets\VentaServicioStats::class,
            VentaServicioResource\Widgets\VentaServicioComisionStats::class,
            VentaServicioResource\Widgets\VentaServiciosChart::make([
                'filters_pages_resources' => $this->tableFilters['created_at'],
            ]),
        ];
    }

    public function getTabs(): array
    {
        $desde = date('Y-m').'-01 00:00:00';
        $hasta = date('Y-m').'-15 23:00:00';

        $desde_II = date('Y-m').'-16 00:00:00';
        $hasta_II = date('Y-m').'-31 23:00:00';

        $desde_mes = date('Y-m').'-01 00:00:00';
        $hasta_mes = date('Y-m').'-31 23:00:00';

        return [

            'Todo' => ListRecords\Tab::make('Todo')->query(fn ($query) => $query->orderBy('created_at', 'desc')),
            'Hoy' => Tab::make()
                ->query(fn ($query) => $query->whereDate('created_at', now()->toDateString()))
                ->badge(VentaServicio::query()->whereDate('created_at', now()->toDateString())->count()),
            'Nro. de Ventas del Mes' => Tab::make()
                ->query(fn ($query) => $query->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()]))
                ->badge(VentaServicio::query()->whereBetween('created_at',[now()->startOfMonth(), now()->endOfMonth()])->count()),
            'Quincena 01/15' => Tab::make()
                ->query(fn ($query) => $query->whereBetween('created_at', [$desde, $hasta]))
                ->badge(VentaServicio::query()->whereBetween('created_at',[$desde, $hasta])->count()),
            'Quincena 16/30' => Tab::make()
                ->query(fn ($query) => $query->whereBetween('created_at', [$desde_II, $hasta_II]))
                ->badge(VentaServicio::query()->whereBetween('created_at',[$desde_II, $hasta_II])->count()),
        ];
    }
}