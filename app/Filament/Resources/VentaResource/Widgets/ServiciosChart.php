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

    // protected static ?string $maxHeight = '300px';

    // protected int | string | array $columnSpan = 'full';

    // protected static ?int $sort = 4;


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

        // $activeFilter = $this->filter;

        // if ($activeFilter === 'today') {
        //     $rangeStartDate = now()->startOfDay();
        //     $rangeEndDate = now()->endOfDay();
        // } elseif ($activeFilter === 'week') {
        //     $rangeStartDate = now()->subWeek()->startOfWeek();
        //     $rangeEndDate = now()->endOfWeek();
        // } elseif ($activeFilter === 'month') {
        //     $rangeStartDate = now()->subMonthNoOverflow()->startOfMonth();
        //     $rangeEndDate = now()->endOfMonth();
        // } elseif ($activeFilter === 'year'){
        //     $rangeStartDate = now()->subMonthNoOverflow()->startOfYear();
        //     $rangeEndDate = now()->endOfYear();
        // }
        // $start = $this->filters['startDate'];
        // $end = $this->filters['endDate'];

        $data = DB::table('detalle_asignacions')
        ->select(DB::raw('COUNT(servicio_id) as venta, servicio_id, servicios.descripcion as descripcion'))
        ->join('servicios', 'detalle_asignacions.servicio_id', '=', 'servicios.id')
        ->groupBy('servicio_id')
        ->get();

        return [
           'datasets' => [
                    [
                        'label' => 'Average de servicios',
                        'data' => $data->map(fn ($data) => $data->venta),
                        'backgroundColor' => '#22c55e',
                        'borderColor' => '#22c55e',
                        'fill' => true,
                    ],

                ],
                'labels' => $data->map(fn ($data) => $data->servicio_id),                                
            ];

    }

    // protected function getOptions(): RawJs
    // {
        
    //     return RawJs::make(<<<JS
    //         {
    //                 options: {
    //                     plugins: {
    //                         tooltip: {
    //                             callbacks: (value) => '€' + value,
    //                         }
    //                     }
    //                 }

    //         }
    //     JS);
    // }

    public function getDescription(): ?string
    {
        return 'Servicios por la cantidad de ventas';
    }

    protected function getType(): string
    {
        return 'line';
    }
}