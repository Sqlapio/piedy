<?php

namespace App\Filament\Resources\CierreFinancieroResource\Widgets;

use App\Filament\Resources\CierreFinancieroResource\Pages\ListCierreFinancieros;
use App\Filament\Resources\VentaServicioResource;
use App\Filament\Resources\VentaServicioResource\Pages\ListVentaServicios;
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

class UtilidadReal extends BaseWidget
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

            Stat::make('UTILIDAD REAL EN DIVISAS($)', '$'.number_format($this->getPageTableQuery()->sum('utilidad_real'), 2, '.', ','))
                ->description('Utilidad Neta Real (Ganancia PIEDY)')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('primary')
                ->chart(
                    $data
                        ->map(fn(TrendValue $value) => $value->aggregate)
                        ->toArray()
                ),
            Stat::make('COSTO OPERATIVO EN DIVISAS($)', '$'.number_format($this->getPageTableQuery()->sum('total_costos_operativos'), 2, '.', ','))
                ->description('Costos de alquiler, servicios y reposición de materia prima')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('info')
                ->chart(
                    $data
                        ->map(fn(TrendValue $value) => $value->aggregate)
                        ->toArray()
                ),
        ];
    }

    public function getColumns(): int
    {
        return 2;
    }
}
