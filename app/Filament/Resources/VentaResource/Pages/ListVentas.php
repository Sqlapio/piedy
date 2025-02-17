<?php

namespace App\Filament\Resources\VentaResource\Pages;

use Filament\Actions;
use App\Filament\Resources\VentaResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Pages\Concerns\ExposesTableToWidgets;

class ListVentas extends ListRecords
{
    use ExposesTableToWidgets;

    protected static string $resource = VentaResource::class;

    protected ?string $heading = 'Ventas Generales';

    protected int | string | array $columnSpan = [
        'sm' => 1,
        'md' => 2,
        'xl' => 2,
    ];

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            VentaResource\Widgets\ServiciosChart::class,
            VentaResource\Widgets\ProductosChart::class,
            VentaResource\Widgets\VentasNetasChart::class,
        ];
    }
}
