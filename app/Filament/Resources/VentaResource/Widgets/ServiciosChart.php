<?php

namespace App\Filament\Resources\VentaResource\Widgets;

use App\Models\Servicio;
use Flowframe\Trend\Trend;
use Filament\Support\RawJs;
use Illuminate\Support\Carbon;
use Flowframe\Trend\TrendValue;
use App\Models\DetalleAsignacion;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class ServiciosChart extends ChartWidget
{
    use InteractsWithPageFilters;

    protected static ?string $heading = 'Servicios';

    protected static ?string $maxHeight = '300px';

    // protected int | string | array $columnSpan = '2';

    protected function getData(): array
    {

        $data = DB::table('detalle_asignacions')
        ->select(DB::raw('COUNT(servicio_id) as venta, servicio_id, servicios.descripcion as descripcion', 'created_at'))
        ->join('servicios', 'detalle_asignacions.servicio_id', '=', 'servicios.id')
        ->groupBy('servicio_id')
        ->get();

        $totalVentas = $data->sum('venta');
        $percentages = $data->map(fn ($item) => round(($item->venta / $totalVentas) * 100, 2));

        return [
           'datasets' => [
                    [
                        'label' => 'Average de servicios',
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
                'labels' => $data->map(fn ($data) => $data->descripcion),
            ];

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


    public function getDescription(): ?string
    {
        return 'Servicios por la cantidad de ventas';
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
