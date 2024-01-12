<?php

namespace App\Filament\Resources\VentaServicioResource\Widgets;

use App\Filament\Resources\VentaServicioResource;
use App\Filament\Resources\VentaServicioResource\Pages\ListVentaServicios;
use App\Models\Cliente;
use App\Models\Disponible;
use App\Models\Producto;
use App\Models\VentaServicio;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Illuminate\Support\Facades\DB;

class VentaServicioComisionStats extends BaseWidget
{
    use InteractsWithPageTable;

    protected static ?string $pollingInterval = null;

    protected static ?int $sort = 2;

    protected function getTablePage(): string
    {
        return ListVentaServicios::class;
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

        return [

            Stat::make('COMISION EN DOLARES ($)', $this->getPageTableQuery()->sum('comision_dolares'))
                ->description('Comisión total por pago en dolares (40%)')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('success')
                ->chart(
                    $data
                        ->map(fn (TrendValue $value) => $value->aggregate)
                        ->toArray()
                ),

            Stat::make('COMISION EN BOLIVARES (BS.)', $this->getPageTableQuery()->sum('comision_bolivares'))
                ->description('Comisión total por pago en bolivares (40%)')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('info')
                ->chart(
                    $data
                        ->map(fn (TrendValue $value) => $value->aggregate)
                        ->toArray()
                ),
        ];
    }

    public function getColumns(): int
    {
        return 2;
    }
}
