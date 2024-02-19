<?php

namespace App\Filament\Widgets;

use App\Models\Frecuencia;
use App\Models\VentaServicio;
use Carbon\Carbon;
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
        // dd($star = now()->startOfMonth());
        // $end = now()->endOfMonth()->isoFormat('dddd, D MMM');

        $data = Trend::model(Frecuencia::class)
            ->between(
                now()->startOfMonth(),
                now()->endOfMonth(),
            )
            // ->perMonth()
            ->perDay()
            ->count('cliente_id');
            // ->count();
        // $labels = ($data->map(fn (TrendValue $value) => $value->date))->toArray();
        // $r = Carbon::parse($labels[0])->isoFormat('dddd, D MMM');
        // $p = strtotime($labels[0]);
        // dd($p, $r, count($labels));

        return [
            'datasets' => [
                [
                    'label' => 'Clientes Nuevos',
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
        return 'Clientes Nuevos';
    }

    protected function getType(): string
    {
        return 'line';
    }
}
