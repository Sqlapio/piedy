<?php

namespace App\Filament\Widgets;

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

class ClienteNuevoChart extends ChartWidget
{
    use InteractsWithPageFilters;

    protected static ?string $heading = 'Clientes';

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

        $data1 = Trend::model(Frecuencia::class)
            ->between(
                // start: (isset($start)) ? Carbon::parse($start) : now()->startOfMonth(),
                // end: (isset($end)) ? Carbon::parse($end) : now()->endOfMonth(),
                start: now()->startOfMonth(),
                end: now()->endOfMonth(),
            )

            ->perDay()
            ->count('cliente_id');

        $data2 = Trend::model(VentaServicio::class)
            ->between(
                start: (isset($start)) ? Carbon::parse($start) : now()->startOfMonth(),
                end: (isset($end)) ? Carbon::parse($end) : now()->endOfMonth(),
                // start: now()->startOfMonth(),
                // end: now()->endOfMonth(),
            )
            // ->perMonth()
            ->perDay()
            ->count('cliente_id');

        $data3 = DB::table('venta_productos')
        ->select(DB::raw('count(cantidad) as cantidad, producto_id'))
        ->groupBy('producto_id')
        ->get();

        // dd($data3, $data3->map(fn ($data3) => $value->cantidad));

        return [
            'datasets' => [
                [
                    'label' => 'Clientes Atendidos',
                    'data' => $data1->map(fn (TrendValue $value) => $value->aggregate),
                    'backgroundColor' => '#00ce0026',
                    'borderColor' => '#00ce00',
                    'fill' => true,
                ],
                [
                    'label' => 'Clientes Recurentes',
                    'data' => $data3->map(fn ($data3) => $data3->cantidad),
                    // 'backgroundColor' => '#ffeb3ba6',
                    'borderColor' => '#ff0000',
                    // 'fill' => true,
                ],
                [
                    'label' => 'Clientes Nuevos',
                    'data' => $data2->map(fn (TrendValue $value) => $value->aggregate),
                    // 'backgroundColor' => '#7b9aa666',
                    'borderColor' => '#0a0aff',
                    // 'fill' => true,
                ],

            ],
            'labels' => ($data1->map(fn (TrendValue $value) => Carbon::parse($value->date)->isoFormat('dd, D'))->toArray()),


            // 'labels' => ($data1->map(fn (TrendValue $value) => Carbon::parse($value->date)->isoFormat('dddd, D MMM'))->toArray()),
        ];
    }

    public function getDescription(): ?string
    {
        return 'Clientes Nuevos/Recurentes/Atendidos';
    }

    protected function getType(): string
    {
         return 'line';
    }
}
