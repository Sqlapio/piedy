<?php

namespace App\Filament\Widgets;

use App\Models\VentaServicio;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class VentasServiciosCAChart extends ChartWidget
{
    protected static ?string $heading = 'Clientes';

    protected function getData(): array
    {
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
            'labels' => $data->map(fn (TrendValue $value) => $value->date),
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
