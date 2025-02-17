<?php

namespace App\Filament\Resources\VentaResource\Widgets;

use App\Models\Cliente;
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

class VentasNetasChart extends ChartWidget
{
    use InteractsWithPageFilters;

    protected static ?string $heading = 'Ventas';

    protected static ?string $maxHeight = '300px';

    protected int | string | array $columnSpan = '2';

    protected static ?int $sort = 5;

    protected function getData(): array
    {
        // $start = $this->filters['startDate'];
        // $end = $this->filters['endDate'];

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

        $data2 = Trend::model(VentaServicio::class)
            ->between(
                start: (isset($start)) ? Carbon::parse($start) : now()->startOfMonth(),
                end: (isset($end)) ? Carbon::parse($end) : now()->endOfMonth(),
                // start: now()->startOfMonth(),
                // end: now()->endOfMonth(),
            )
            // ->perMonth()
            ->perDay()
            ->sum('pago_usd');

        $data3 = Trend::model(VentaServicio::class)
        ->between(
            start: (isset($start)) ? Carbon::parse($start) : now()->startOfMonth(),
            end: (isset($end)) ? Carbon::parse($end) : now()->endOfMonth(),
            // start: now()->startOfMonth(),
            // end: now()->endOfMonth(),
        )
            // ->perMonth()
            ->perDay()
            ->sum('pago_bsd');

        return [
            'datasets' => [
                [
                    'label' => 'Clientes Atendidos',
                    'data' => $data2->map(fn (TrendValue $value) => $value->aggregate),
                    'backgroundColor' => '#00ce0026',
                    'borderColor' => '#00ce00',
                    'fill' => true,
                ],
                [
                    'label' => 'Conversion Bsd/Usd',
                    'data' => $data3->map(fn(TrendValue $value) => $value->aggregate),
                    'backgroundColor' => '#00ce0026',
                    'borderColor' => '#ff0000',
                    'fill' => true,
                ],

            ],
            'labels' => ($data2->map(fn (TrendValue $value) => Carbon::parse($value->date)->isoFormat('dd, D'))->toArray()),


            // 'labels' => ($data1->map(fn (TrendValue $value) => Carbon::parse($value->date)->isoFormat('dddd, D MMM'))->toArray()),
        ];
    }

    public function getDescription(): ?string
    {
        return 'Ventas netas en Bs/Usd';
    }

    protected function getType(): string
    {
         return 'line';
    }
}