<?php

namespace App\Filament\Resources\CierreFinancieroResource\Widgets;

use App\Filament\Resources\CierreFinancieroResource\Pages\ListCierreFinancieros;
use App\Filament\Resources\CierreFinancieroResource;
use App\Models\CierreFinanciero;
use App\Models\Cliente;
use App\Models\Disponible;
use App\Models\GiftCard;
use App\Models\Membresia;
use App\Models\Producto;
use App\Models\VentaProducto;
use App\Models\VentaServicio;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Illuminate\Support\Facades\DB;

class VentaServicioPropinasStats extends BaseWidget
{
    use InteractsWithPageTable;

    protected static ?string $pollingInterval = null;

    protected static ?int $sort = 3;

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
        $periodo = $data_financiera->fecha_ini.' '.$data_financiera->fecha_fin;

        return [

            Stat::make('TOTAL CLIENTES ATENDIDOS', $this->getPageTableQuery()->sum('total_clientes_atendidos'))
                ->description('Clientes atendidos')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('success')
                ->chart(
                    $data
                        ->map(fn (TrendValue $value) => $value->aggregate)
                        ->toArray()
                ),

            Stat::make('TOTAL SERVICIOS', $this->getPageTableQuery()->sum('total_servicios'))
                ->description('Servicios prestados')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('success')
                ->chart(
                    $data
                        ->map(fn (TrendValue $value) => $value->aggregate)
                        ->toArray()
                ),

            Stat::make('TOTAL MEMBRESIAS VENDIDAS($)', '$'.number_format($this->getPageTableQuery()->sum('total_membresias_vendidas'), 2, '.', ','))
                ->description('Cantidad vendida: '.Membresia::whereBetween('created_at', [$data_financiera->fecha_ini, $data_financiera->fecha_fin])->count().' membresias')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('info')
                ->chart(
                    $data
                        ->map(fn (TrendValue $value) => $value->aggregate)
                        ->toArray()
                ),

            Stat::make('TOTAL GIFTCARD VENDIDAS($)', '$'.number_format($this->getPageTableQuery()->sum('total_gif_card_vendidas'), 2, '.', ','))
                ->description('Cantidad vendida: '.GiftCard::whereBetween('created_at', [$data_financiera->fecha_ini, $data_financiera->fecha_fin])->count().' giftcards')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('info')
                ->chart(
                    $data
                        ->map(fn (TrendValue $value) => $value->aggregate)
                        ->toArray()
                ),

            Stat::make('TOTAL PRODUCTOS VENDIDOS($)', '$'.number_format($this->getPageTableQuery()->sum('total_productos_vendidos'), 2, '.', ','))
                ->description('Cantidad vendida: '.VentaProducto::whereBetween('created_at', [$data_financiera->fecha_ini, $data_financiera->fecha_fin])->sum('cantidad').' productos')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('warning')
                ->chart(
                    $data
                        ->map(fn (TrendValue $value) => $value->aggregate)
                        ->toArray()
                ),

            Stat::make('INDICADOR DE INVENTARIO($)', $this->getPageTableQuery()->sum('indicador_inventario'))
                ->description('1: Producto(s) en existencia cero - 0: Sin productos en cero')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('warning')
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
