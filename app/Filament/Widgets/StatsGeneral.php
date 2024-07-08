<?php

namespace App\Filament\Widgets;

use App\Models\Cliente;
use App\Models\Producto;
use App\Models\VentaServicio;
use App\Models\Disponible;
use App\Models\Frecuencia;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;


class StatsGeneral extends BaseWidget
{
    use InteractsWithPageFilters;

    protected function getStats(): array
    {
        if($this->filters['activar'] == false)
        {
            $rangeStartDate = now()->startOfYear();
            $rangeEndDate = now()->endOfYear();
            $rango = date('d-m-Y', strtotime($rangeStartDate)).' al '.date('d-m-Y', strtotime($rangeEndDate));

        }else{
            $rangeStartDate = $this->filters['startDate'].' 00:00:00.000';
            $rangeEndDate = $this->filters['endDate'].'. 23:59:59.000';
            $rango = date('d-m-Y', strtotime($rangeStartDate)).' al '.date('d-m-Y', strtotime($rangeEndDate));
        }

        $ventas_generales = VentaServicio::whereBetween('created_at',[$rangeStartDate, $rangeEndDate])->sum('total_USD');

        return [

            Stat::make('Total General de Ventas($)', '$ '.number_format($ventas_generales, 2, '.', ','))
                ->description($rango)
                ->descriptionIcon('heroicon-m-presentation-chart-line')
                ->color('success')
                ->chart([7, 2, 10, 3, 15, 4, 17]),
        ];
    }

    public function getColumns(): int
    {
        return 1;
    }
}
