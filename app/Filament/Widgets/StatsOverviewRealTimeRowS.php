<?php

namespace App\Filament\Widgets;

use App\Models\GiftCard;
use App\Models\Membresia;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;


class StatsOverviewRealTimeRowS extends BaseWidget
{
    // protected static ?string $pollingInterval = '10s';

    protected function getStats(): array
    {
        return [

            Stat::make('Membresia Emitidas', Membresia::Count())
                ->description('Vencidas: '.Membresia::where('status', 2)->count())
                ->descriptionIcon('heroicon-o-users')
                ->color('info')
                ->chart([7, 2, 10, 3, 15, 4, 17]),
            Stat::make('GiftCard Emitidas', GiftCard::Count())
                ->description('Usadas: '.GiftCard::where('status', 2)->count())
                ->descriptionIcon('heroicon-c-share')
                ->color('gray')
                ->chart([7, 2, 10, 3, 15, 4, 17]),
        ];
    }

    public function getColumns(): int
    {
        return 2;
    }
}
