<?php

namespace App\Filament\Widgets;

use App\Models\VentaServicio;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class VentasServiciosCAChart extends ChartWidget
{
    protected static ?string $heading = 'Clientes';

    public ?string $filter = 'today';

    protected function getFilters(): ?array
    {
        return [
            'today' => 'Hoy',
            'week'  => 'Semana',
            'month' => 'Mes',
            'year'  => 'Año',
        ];
    }

    protected function getData(): array
    {
        $activeFilter = $this->filter;

        if ($activeFilter === 'today') {
            $rangeStartDate = now()->startOfDay();
            $rangeEndDate = now()->endOfDay();
        } elseif ($activeFilter === 'week') {
            $rangeStartDate = now()->subWeek()->startOfWeek();
            $rangeEndDate = now()->endOfWeek();
        } elseif ($activeFilter === 'month') {
            $rangeStartDate = now()->subMonthNoOverflow()->startOfMonth();
            $rangeEndDate = now()->endOfMonth();
        } elseif ($activeFilter === 'year'){
            $rangeStartDate = now()->subMonthNoOverflow()->startOfYear();
            $rangeEndDate = now()->endOfYear();
        }
        
        $data = Trend::model(VentaServicio::class)
            ->between(
                start: now()->startOfMonth(),
                end: now()->endOfMonth(),
            )
            // ->perMonth()
            ->perDay()
            ->count('cliente');

        return [
            'datasets' => [
                [
                    'label' => 'Clientes atendidos',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                    'backgroundColor' => '#36A2EB',
                    'borderColor' => '#22c55e',
                ],
            ],
            'labels' => ($data->map(fn (TrendValue $value) => Carbon::parse($value->date)->isoFormat('dddd, D MMM'))->toArray()),
        ];
    }

    public function getDescription(): ?string
    {
        return 'Total de clientes atendidos por día';
    }

    protected function getType(): string
    {
        return 'line';
    }
}
