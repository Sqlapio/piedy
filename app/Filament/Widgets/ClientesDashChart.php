<?php

namespace App\Filament\Widgets;

use App\Models\Cita;
use App\Models\Frecuencia;
use Flowframe\Trend\Trend;

// use Carbon\Carbon;
use App\Models\VentaProducto;
use App\Models\VentaServicio;
use Illuminate\Support\Carbon;
use Flowframe\Trend\TrendValue;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class ClientesDashChart extends ChartWidget
{
    use InteractsWithPageFilters;

    protected static ?string $heading = 'Clientes';

    protected static ?string $maxHeight = '190px';

    protected function getData(): array
    {

        // $data = DB::table('venta_productos')
        // ->select(DB::raw('COUNT(producto_id) as venta, producto_id, productos.descripcion as descripcion'))
        // ->join('productos', 'venta_productos.producto_id', '=', 'productos.id')
        // ->groupBy('producto_id')
        // ->get();

        $rangeStartDate = now()->startOfDay();
        $rangeEndDate = now()->endOfDay();

        $citas_agendadas_bot = Cita::where('responsable', 'PiedyBot')
            ->whereBetween('created_at', [$rangeStartDate, $rangeEndDate])
            ->count();

        $citas_agendadas_sistema = Cita::where('responsable', '!=', 'PiedyBot')
            ->whereBetween('created_at', [$rangeStartDate, $rangeEndDate])
            ->count();

        $citas_canceladas = Cita::where('responsable', '!=', 'PiedyBot')
            ->whereBetween('created_at', [$rangeStartDate, $rangeEndDate])
            ->where('status', 3)
            ->count();


        $array = [
            $citas_agendadas_bot,
            $citas_agendadas_sistema,
            $citas_canceladas
        ];

        $labels = [
            'PiedyBot',
            'Agendadas en Tienda',
            'Canceladas'
        ];

        // dd($array, $labels);
        // dd($data);

        $labels = $labels;

        // $shortenedLabels = $labels->map(function($label) {
        //     return substr($label, 0, 10) . (strlen($label) > 10 ? '...' : '');
        // });

        return [
            'datasets' => [
                [
                    'label' => '',
                    'data' => $array,
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

    public function getDescription(): ?string
    {
        return 'Clientes agendados';
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
        ]
    ];

    protected function getType(): string
    {
        return 'bar';
    }
}
