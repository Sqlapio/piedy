<?php

namespace App\Filament\Resources\VentaResource\Widgets;

use App\Models\Servicio;
use Flowframe\Trend\Trend;
use Illuminate\Support\Carbon;
use Flowframe\Trend\TrendValue;
use App\Models\DetalleAsignacion;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class ProductosChart extends ChartWidget
{
    use InteractsWithPageFilters;

    protected static ?string $heading = 'Productos';

    protected static ?string $maxHeight = '300px';

    // protected int | string | array $columnSpan = '3';

    protected static ?int $sort = 4;


    // public ?string $filter = 'today';

    // protected function getFilters(): ?array
    // {
    //     return [
    //         'today' => 'Hoy',
    //         'week'  => 'Semana',
    //         'month' => 'Mes',
    //         'year'  => 'Año',
    //     ];
    // }

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
                    'backgroundColor' => ['#22c55e', '#ed0000'],
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
    ];   // protected function getOptions(): RawJs

    protected function getType(): string
    {
        return 'doughnut';
    }
}