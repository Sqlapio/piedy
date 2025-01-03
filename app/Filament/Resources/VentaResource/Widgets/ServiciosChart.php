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

        return [
           'datasets' => [
                    [
                        'label' => 'Average de servicios',
                        'data' => $data->map(fn ($data) => $data->venta),
                        'backgroundColor' => ['#22c55e', '#ed0000'],
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
    ];   // protected function getOptions(): RawJs


    public function getDescription(): ?string
    {
        return 'Servicios por la cantidad de ventas';
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}