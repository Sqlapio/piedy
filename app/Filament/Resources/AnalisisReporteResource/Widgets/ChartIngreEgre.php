<?php

namespace App\Filament\Resources\AnalisisReporteResource\Widgets;

use Carbon\Carbon;
use Flowframe\Trend\Trend;
use App\Models\AnalisisReporte;
use Flowframe\Trend\TrendValue;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class ChartIngreEgre extends ChartWidget
{
    use InteractsWithPageFilters;
    
    protected static ?string $heading = 'Ingresos y Egresos';


    protected function getData(): array
    {

        $datos = AnalisisReporte::all()->select(['sub_total_ingresos', 'sub_total_egresos', 'created_at']);
        $datos_ingre = $datos->map(fn($data) => $data['sub_total_ingresos'])->toArray();
        $datos_egre = $datos->map(fn($data) => $data['sub_total_egresos'])->toArray();
        $label = $datos->map(fn($data) => Carbon::parse($data['created_at'])->isoFormat('dddd, D MMM'))->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Ventas por servicio',
                    'data' => $datos_ingre,
                    'backgroundColor' => '#2563eb',
                    'borderColor' => '#2563eb',
                ],
                [
                    'label' => 'Ventas por servicio',
                    'data' => $datos_egre,
                    'backgroundColor' => '#b30000',
                    'borderColor' => '#b30000',
                ],
            ],
            'labels' => $label,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    public function getDescription(): ?string
    {
        return 'Grafico de Ingresos y Egresos';
    }
}