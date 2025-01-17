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

class ServiciosDashChart extends ChartWidget
{
    use InteractsWithPageFilters;

    protected static ?string $heading = 'Servicios';

    protected static ?string $maxHeight = '192px';

    protected function getData(): array
    {

        $data = DB::table('detalle_asignacions')
            ->select(DB::raw('COUNT(servicio_id) as venta, servicio_id, servicios.nombre_corto as descripcion', 'created_at'))
            ->join('servicios', 'detalle_asignacions.servicio_id', '=', 'servicios.id')
            ->whereBetween('detalle_asignacions.created_at', [now()->startOfDay(), now()->endOfDay()])
            ->groupBy('servicio_id')
            ->orderBy('venta', 'desc')
            ->take(5)
            ->get();


        $totalVentas = $data->sum('venta');
        $percentages = $data->map(fn($item) => round(($item->venta / $totalVentas) * 100, 2));

        $labels = $data->map(fn($data) => $data->descripcion);

        // $shortenedLabels = $labels->map(function ($label) {
        //     return substr($label, 0, 10) . (strlen($label) > 13 ? '...' : '');
        // });

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
                    'borderColor' => '#ffff',
                    // 'fill' => true,
                ],

            ],
            'labels' => $labels,
        ];
    }

    protected static ?array $options = [
        'scales' => [
            'x' => [
                'display' => true,

                'ticks' => [
                    'stepSize' => 1
                ],
            ],
            'y' => [
                'display' => true,
            ],
        ],
        'indexAxis' => 'y',
        'plugins' => [
            'legend' => [
                'display' => false,
            ]
            ],
        'elements' => [
            'bar' => [
                'barThickness' => 30, 
            ]
        ]
    ];


    public function getDescription(): ?string
    {
        return 'Servicios por la cantidad de ventas';
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
