<?php

namespace App\Filament\Resources\VentaServicioResource\Widgets;

use Carbon\Carbon;
use App\Models\VentaProducto;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use App\Filament\Resources\VentaProductoResource\Pages\ListVentaProductos;
use App\Filament\Resources\VentaServicioResource\Pages\ListVentaServicios;

class VentaServiciosChart extends ChartWidget
{
    use InteractsWithPageFilters;

    public array $filters_pages_resources;

    protected static ?string $heading = 'Average de ventas';

    protected static ?string $maxHeight = '400px';

    protected int | string | array $columnSpan = 'full';

    protected function getTablePage(): string
    {
        return ListVentaServicios::class;
    }


    protected function getData(): array
    {
        // dd($this->filters_pages_resources);


        $desde = $this->filters_pages_resources['desde'] == null ? now()->startOfYear() :  $this->filters_pages_resources['desde'] . ' 00:00:00';
        $hasta = $this->filters_pages_resources['hasta'] == null ? now()->endOfYear() : $this->filters_pages_resources['hasta'] . ' 23:59:59';

        $data = DB::table('detalle_asignacions')
        ->select(DB::raw('COUNT(servicio_id) as venta, servicio_id, servicios.nombre_corto as descripcion', 'status', 'created_at', 'detalle_asignacions.tipo'))
        ->join('servicios', 'detalle_asignacions.servicio_id', '=', 'servicios.id')
        ->where('detalle_asignacions.servicio_id', '!=', null)
        ->where('detalle_asignacions.status', 2)
        ->where('detalle_asignacions.tipo', 'servicio')
        ->whereBetween('detalle_asignacions.created_at', [$desde, $hasta])
            ->groupBy('servicio_id')
            ->orderBy('venta', 'desc')
            ->get();

        $labels = $data->map(fn($data) => $data->descripcion);

        return [
            'datasets' => [
                [
                    'label' => '',
                    'data' => $data->map(fn($data) => $data->venta),
                    'backgroundColor' => [
                        '#a16d69',
                        '#99bcbf',
                        '#bf99a9',
                        '#bfaf99',
                        '#99a9bf',
                        '#99bfaf',
                        '#9c99bf',
                        '#99bf9c',
                        '#bf9c99',
                        '#bf99bc',
                        '#c7a8a5',
                        '#ab7e7a',
                        '#7ba69d',
                        '#7b9aa6',
                        '#a6877b',
                        '#7b85a6',
                        '#a69d7b',
                        '#a67b85',
                        '#9aa67b',
                        '#7ba687',
                        '#a67b9a',
                        '#56737f'
                    ],
                    'borderColor' => '#fff',
                    'fill' => true,
                ],

            ],
            'labels' => $labels,
        ];
    }

    protected static ?array $options = [
        'scales' => [
            'x' => [
                'display' => true,
            ],
            'y' => [
                'display' => true,
            ],
        ],
        'plugins' => [
            'legend' => [
                'display' => false,
            ]
        ],
    ];


    public function getDescription(): ?string
    {
        return 'Servicios vendidos';
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
