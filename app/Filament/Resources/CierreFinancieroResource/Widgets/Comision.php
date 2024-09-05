<?php

namespace App\Filament\Resources\CierreFinancieroResource\Widgets;

use App\Filament\Resources\CierreFinancieroResource\Pages\ListCierreFinancieros;
use App\Filament\Resources\CierreFinancieroResource;
use App\Models\CierreFinanciero;
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

class Comision extends BaseWidget
{
    use InteractsWithPageTable;

    protected static ?string $pollingInterval = null;

    protected static ?int $sort = 2;

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

            Stat::make('TOTAL COMISIONES ($)', '$'.number_format($this->getPageTableQuery()->sum('total_general_comiciones'), 2, '.', ','))
                ->description('Total neto comisiones en Dolares')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('success')
                ->chart(
                    $data
                        ->map(fn (TrendValue $value) => $value->aggregate)
                        ->toArray()
                ),

            Stat::make('COMISIONES EN (BS.)', 'Bs. '.number_format($this->getPageTableQuery()->sum('total_comisiones_bolivares'), 2, ',', '.'))
                ->description('Conversion a Dolares: '.number_format(($this->getPageTableQuery()->sum('total_comisiones_bolivares') / $this->getPageTableQuery()->sum('tasa_bcv')), 2, '.', ','))
                ->descriptionIcon('heroicon-m-user-group')
                ->color('info')
                ->chart(
                    $data
                        ->map(fn (TrendValue $value) => $value->aggregate)
                        ->toArray()
                ),

            Stat::make('COMISIONES ($)', '$'.number_format($this->getPageTableQuery()->sum('total_comisiones_dolares'), 2, '.', ','))
                ->description('Comisión total por pago en dolares')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('success')
                ->chart(
                    $data
                        ->map(fn (TrendValue $value) => $value->aggregate)
                        ->toArray()
                ),
        ];
    }

    public function getColumns(): int
    {
        return 3;
    }
}
