<?php

namespace App\Filament\Resources\VentaServicioResource\Pages;

use Filament\Actions;
use App\Models\Cliente;
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
        $hasta = date('Y-m').'-15 23:59:59';

        $desde_II = date('Y-m').'-16 00:00:00';
        if(date('m') == 2){
            $hasta_II = date('Y-m').'-28 23:59:59';
        }else{
            $hasta_II = date('Y-m').'-31 23:59:59';
        }
        // $hasta_II = date('Y-m').'-28 23:00:00';

        $desde_mes = date('Y-m').'-01 00:00:00';
        $hasta_mes = date('Y-m').'-31 23:00:00';

        return [

            'Todo' => ListRecords\Tab::make('Todo')->query(fn ($query) => $query->orderBy('created_at', 'desc')),
            
            'Hoy' => Tab::make()
                ->query(fn ($query) => $query->whereDate('created_at', now()->toDateString()))
                ->badge(VentaServicio::query()->whereBetween('created_at', [now()->startOfDay(), now()->endOfDay()])->groupBy('cliente_id')->get()->count()),
                
            'Mes'    => Tab::make()
                ->query(fn ($query)     => $query->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()]))
                ->badge(VentaServicio::query()->whereBetween('created_at',[now()->startOfMonth(), now()->endOfMonth()])->groupBy('cliente_id')->get()->count()),
            
            // date('M'). ' 01 al 15'        => Tab::make()
            //     ->query(fn ($query) => $query->whereBetween('created_at', [$desde, $hasta]))
            //     ->badge(VentaServicio::query()->whereBetween('created_at',[$desde, $hasta])->groupBy('cliente_id')->get()->count()),
            
            // date('m') == 02 ? date('M') . ' 16 al 28' : date('M') . ' 16 al 31'        => Tab::make()
            //     ->query(fn ($query) => $query->whereBetween('created_at', [$desde_II, $hasta_II]))
            //     ->badge(VentaServicio::query()->whereBetween('created_at',[$desde_II, $hasta_II])->groupBy('cliente_id')->get()->count()),
        ];
    }
}