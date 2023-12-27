<?php

namespace App\Filament\Resources\VentaServicioResource\Pages;

use App\Filament\Resources\VentaServicioResource;
use App\Models\VentaServicio;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ListRecords\Tab;
use Filament\Pages\Concerns\ExposesTableToWidgets;
class ListVentaServicios extends ListRecords
{
    use ExposesTableToWidgets;

    protected ?string $heading = 'Dashboard Ventas';

    protected static string $resource = VentaServicioResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            VentaServicioResource\Widgets\VentaServicioStats::class,
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

            'Todo' => ListRecords\Tab::make('Todo'),
            'Hoy' => Tab::make()
                ->query(fn ($query) => $query->whereDate('created_at', now()->toDateString()))
                ->badge(VentaServicio::query()->whereDate('created_at', now()->toDateString())->count()),
            'Quincena 01/15' => Tab::make()
                ->query(fn ($query) => $query->whereBetween('created_at', [$desde, $hasta]))
                ->badge(VentaServicio::query()->whereBetween('created_at',[$desde, $hasta])->count()),
            'Quincena 16/30' => Tab::make()
                ->query(fn ($query) => $query->whereBetween('created_at', [$desde_II, $hasta_II]))
                ->badge(VentaServicio::query()->whereBetween('created_at',[$desde_II, $hasta_II])->count()),
            'Mensual' => Tab::make()
                ->query(fn ($query) => $query->whereBetween('created_at', [$desde_mes, $hasta_mes]))
                ->badge(VentaServicio::query()->whereBetween('created_at',[$desde_mes, $hasta_mes])->count()),
        ];
    }
}
