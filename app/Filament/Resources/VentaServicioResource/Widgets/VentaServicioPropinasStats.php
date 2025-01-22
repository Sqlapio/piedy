<?php

namespace App\Filament\Resources\VentaServicioResource\Widgets;

use App\Filament\Resources\VentaServicioResource\Pages\ListVentaServicios;
use App\Models\VentaServicio;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Flowframe\Trend\Trend;

class VentaServicioPropinasStats extends BaseWidget
{
    use InteractsWithPageTable;

    protected static ?string $pollingInterval = null;

    protected static ?int $sort = 3;

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
            ->count('cliente_id');

        return [

            Stat::make('PROPINAS EN DOLARES ($)', $this->getPageTableQuery()->sum('propina_usd'))
                ->description('Total acumulado propinas en dolares')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('success')
                ->extraAttributes(['class' => 'col-span-1 row-span-1 rounded-md text-center']),

            Stat::make('PROPINAS EN BOLIVARES (BS.)', $this->getPageTableQuery()->sum('propina_bsd'))
                ->description('Total acumulado propinas en bolivares')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('info')
                ->extraAttributes(['class' => 'col-span-1 row-span-1 rounded-md text-center']),

        ];
    }

    public function getColumns(): int
    {
        return 4;
    }
}
