<?php

namespace App\Filament\Widgets;

use App\Models\Frecuencia;
use App\Models\VentaServicio;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class ClienteNuevoChart extends ChartWidget
{
    protected static ?string $heading = 'Frecuencia de registro de clientes';

    protected static ?int $sort = 4;

    protected function getData(): array
    {
        $activeFilter = $this->filter;

        $data = Trend::model(Frecuencia::class)
            ->between(
                start: now()->startOfMonth(),
                end: now()->endOfMonth(),
            )
            // ->perMonth()
            ->perDay()
            ->count('cliente_id');
            // ->count();

        return [
            'datasets' => [
                [
                    'label' => 'Clientes Nuevos',
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
        return 'Clientes Nuevos';
    }

    protected function getType(): string
    {
        return 'line';
    }
}
