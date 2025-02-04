<?php

namespace App\Filament\Resources\AnalisisReporteResource\Pages;

use App\Filament\Resources\AnalisisReporteResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAnalisisReportes extends ListRecords
{
    protected static string $resource = AnalisisReporteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            AnalisisReporteResource\Widgets\ChartIngreEgre::class,
            AnalisisReporteResource\Widgets\ChartUtilidad::class,
        ];
    }
}