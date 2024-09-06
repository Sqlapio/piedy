<?php

namespace App\Filament\Resources\CierreFinancieroResource\Widgets;

use App\Filament\Resources\CierreFinancieroResource\Pages\ListCierreFinancieros;
use App\Filament\Resources\CierreFinancieroResource;
use App\Models\CierreFinanciero;
use App\Models\Cliente;
use App\Models\DetalleAsignacion;
use App\Models\Disponible;
use App\Models\GiftCard;
use App\Models\Membresia;
use App\Models\NominaGeneral;
use App\Models\Producto;
use App\Models\TasaBcv;
use App\Models\VentaProducto;
use App\Models\VentaServicio;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Illuminate\Support\Facades\DB;

class VentaServicioStats extends BaseWidget
{
    use InteractsWithPageTable;

    protected static ?string $pollingInterval = null;

    protected function getTablePage(): string
    {
        return ListCierreFinancieros::class;
    }

    protected function getStats(): array
    {
        $data = Trend::model(VentaServicio::class)
            ->between(
                start: now()->subYear(),
                end: now(),
            )
            ->perMonth()
            ->count('cliente');

        $data_financiera = CierreFinanciero::latest()->first();

        return [

            Stat::make('TOTAL GENERAL DE VENTAS($)', '$'.number_format($this->getPageTableQuery()->sum('total_general_ventas'), 2, '.', ','))
                ->description('Venta Neta')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('primary')
                ->chart(
                    $data
                        ->map(fn(TrendValue $value) => $value->aggregate)
                        ->toArray()
                ),
            Stat::make('INGRESOS(BS.)', 'Bs. '.number_format($this->getPageTableQuery()->sum('total_ingreso_bolivares'), 2, ',', '.'))
                ->description('Conversion a Dolares: $'.number_format(($this->getPageTableQuery()->sum('total_ingreso_bolivares') / $this->getPageTableQuery()->sum('tasa_bcv')), 2, '.', ','))
                ->descriptionIcon('heroicon-m-user-group')
                ->color('info')
                ->chart(
                    $data
                        ->map(fn(TrendValue $value) => $value->aggregate)
                        ->toArray()
                ),
            Stat::make('INGRESOS($)', '$'.number_format($this->getPageTableQuery()->sum('total_ingreso_dolares'), 2, '.', ','))
                ->description('Total de ingresos en Dolares')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('success')
                ->chart(
                    $data
                        ->map(fn(TrendValue $value) => $value->aggregate)
                        ->toArray()
                ),
        ];
    }
}
