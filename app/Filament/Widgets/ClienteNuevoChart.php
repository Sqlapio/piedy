<?php

namespace App\Filament\Widgets;

use App\Models\Frecuencia;
use App\Models\VentaServicio;
// use Carbon\Carbon;
use Illuminate\Support\Carbon;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class ClienteNuevoChart extends ChartWidget
{
    use InteractsWithPageFilters;

    protected static ?string $heading = 'Clientes Registrados(Nuevos) / Clientes Atendidos';

    protected static ?string $maxHeight = '300px';

    protected int | string | array $columnSpan = 'full';

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
        $start = $this->filters['startDate'];
        $end = $this->filters['endDate'];

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
                start: (isset($start)) ? Carbon::parse($start) : now()->startOfMonth(),
                end: (isset($end)) ? Carbon::parse($end) : now()->endOfMonth(),
                // start: now()->startOfMonth(),
                // end: now()->endOfMonth(),
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
            ->count('cliente');

        return [
            'datasets' => [
                [
                    'label' => 'Clientes Nuevos',
                    'data' => $data1->map(fn (TrendValue $value) => $value->aggregate),
                    'backgroundColor' => '#22c55e',
                    'borderColor' => '#22c55e',
                ],
                [
                    'label' => 'Clientes Atendidos',
                    'data' => $data2->map(fn (TrendValue $value) => $value->aggregate),
                    'backgroundColor' => '#36A2EB',
                    'borderColor' => '#36A2EB',
                ],
            ],
            'labels' => ($data1->map(fn (TrendValue $value) => Carbon::parse($value->date)->isoFormat('dddd, D MMM'))->toArray()),
        ];
    }

    public function getDescription(): ?string
    {
        return 'Clientes Nuevos';
    }

    protected function getType(): string
    {
        return 'line';
    }
}
