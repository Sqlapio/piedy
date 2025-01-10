<?php

namespace App\Filament\Widgets;

use App\Models\Frecuencia;
use App\Models\VentaServicio;
use App\Models\VentaProducto;

// use Carbon\Carbon;
use Illuminate\Support\Carbon;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Illuminate\Support\Facades\DB;

class ProductosDashChart extends ChartWidget
{
    use InteractsWithPageFilters;

    protected static ?string $heading = 'Productos';

    protected static ?string $maxHeight = '190px';

    protected function getData(): array
    {

        $data = DB::table('venta_productos')
        ->select(DB::raw('COUNT(producto_id) as venta, producto_id, productos.descripcion as descripcion'))
        ->join('productos', 'venta_productos.producto_id', '=', 'productos.id')
        ->groupBy('producto_id')
        ->get();

        return [
           'datasets' => [
                    [
                        'label' => 'Average de Productos',
                        'data' => $data->map(fn ($data) => $data->venta),
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
                            '#56737f'],
                        // 'borderColor' => '#22c55e',
                        // 'fill' => true,
                    ],
                ],
                'labels' => ($data->map(fn ($data) => $data->descripcion)),
        ];

    }

    public function getDescription(): ?string
    {
        return 'Productos por la cantidad de ventas';
    }

    protected static ?array $options = [
        'scales' => [
            'x' => [
                'display' => false,
            ],
            'y' => [
                'display' => false,
            ],
        ],
        'plugins' => [
            'legend' => [
                'position' => 'left',
                'align' => 'start',
            ],
        ],
    ];   // protected function getOptions(): RawJs

    protected function getType(): string
    {
        return 'pie';
    }
}
