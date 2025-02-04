<?php

namespace App\Filament\Resources\AnalisisReporteResource\Widgets;

use Carbon\Carbon;
use Flowframe\Trend\Trend;
use App\Models\AnalisisReporte;
use Flowframe\Trend\TrendValue;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class ChartUtilidad extends ChartWidget
{
    use InteractsWithPageFilters;
    
    protected static ?string $heading = 'Utilidad Neta';

    protected function getData(): array
    {

        $datos = AnalisisReporte::all()->select(['neto', 'created_at']);
        $datos_y = $datos->map(fn($data) => $data['neto'])->toArray();
        $datos_x = $datos->map(fn($data) => Carbon::parse($data['created_at'])->isoFormat('dddd, D MMM'))->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Ventas por servicio',
                    'data' => $datos_y,
                    'backgroundColor' => '#36A2EB',
                    'borderColor' => '#22c55e',
                ],
            ],
            'labels' => $datos_x,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    public function getDescription(): ?string
    {
        return 'Grafico de Utilidad Neta';
    }
}