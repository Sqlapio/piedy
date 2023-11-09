<?php

namespace App\Filament\Widgets;

use App\Models\VentaServicio;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class VentaServicioChart extends ChartWidget
{
    protected static ?string $heading = 'Ventas por servicio';

    protected function getData(): array
    {
        $activeFilter = $this->filter;

        $data = Trend::model(VentaServicio::class)
            ->between(
                start: now()->startOfMonth(),
                end: now()->endOfMonth(),
            )
            // ->perMonth()
            ->perDay()
            ->sum('total_USD');
            // ->count();

        return [
            'datasets' => [
                [
                    'label' => 'Ventas por servicio',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                    'backgroundColor' => '#36A2EB',
                    'borderColor' => '#22c55e',
                ],
            ],
            'labels' => $data->map(fn (TrendValue $value) => $value->date),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
