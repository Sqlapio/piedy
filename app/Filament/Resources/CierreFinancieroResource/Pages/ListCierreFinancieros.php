<?php

namespace App\Filament\Resources\CierreFinancieroResource\Pages;

use App\Filament\Resources\CierreFinancieroResource;
use App\Models\CierreFinanciero;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords\Tab;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Pages\ListRecords;

class ListCierreFinancieros extends ListRecords
{
    use ExposesTableToWidgets;

    protected ?string $heading = 'Dashboard Financiero';

    protected static string $resource = CierreFinancieroResource::class;

    protected static ?string $recordTitleAttribute = 'codigo_quincena';

    protected int | string | array $columnSpan = [
        'md' => 2,
        'xl' => 4,
    ];

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [

            CierreFinancieroResource\Widgets\UtilidadReal::class,
            CierreFinancieroResource\Widgets\VentaServicioStats::class,
            CierreFinancieroResource\Widgets\Comision::class,
            CierreFinancieroResource\Widgets\VentaServicioPropinasStats::class,
        ];
    }

    // public function getTabs(): array
    // {
    //     return [

    //         'Todo' => ListRecords\Tab::make('Todo'),
    //         'Enero' => Tab::make()
    //             ->query(fn ($query) => $query->where('mes', 'Enero'))
    //             ->badge(CierreFinanciero::query()->where('mes', 'Enero')->count()),
    //         'Febrero' => Tab::make()
    //             ->query(fn ($query) => $query->whereBetween('codigo_quincena', ['1022024', '2022024']))
    //             ->badge(CierreFinanciero::query()->whereBetween('codigo_quincena', ['1022024', '2022024'])->count()),
    //         'Marzo' => Tab::make()
    //             ->query(fn ($query) => $query->whereBetween('codigo_quincena', ['1032024', '2032024']))
    //             ->badge(CierreFinanciero::query()->whereBetween('codigo_quincena', ['1032024', '2032024'])->count()),
    //         'Abril' => Tab::make()
    //             ->query(fn ($query) => $query->whereBetween('codigo_quincena', ['1042024', '2042024']))
    //             ->badge(CierreFinanciero::query()->whereBetween('codigo_quincena', ['1042024', '2042024'])->count()),
    //         'Mayo' => Tab::make()
    //             ->query(fn ($query) => $query->whereBetween('codigo_quincena', ['1052024', '2052024']))
    //             ->badge(CierreFinanciero::query()->whereBetween('codigo_quincena', ['1052024', '2052024'])->count()),
    //         'Junio' => Tab::make()
    //             ->query(fn ($query) => $query->whereBetween('codigo_quincena', ['1062024', '2062024']))
    //             ->badge(CierreFinanciero::query()->whereBetween('codigo_quincena', ['1062024', '2062024'])->count()),
    //         'Julio' => Tab::make()
    //             ->query(fn ($query) => $query->whereBetween('codigo_quincena', ['1072024', '2072024']))
    //             ->badge(CierreFinanciero::query()->whereBetween('codigo_quincena', ['1072024', '2072024'])->count()),
    //         'Agosto' => Tab::make()
    //             ->query(fn ($query) => $query->whereBetween('codigo_quincena', ['1082024', '2082024']))
    //             ->badge(CierreFinanciero::query()->whereBetween('codigo_quincena', ['1082024', '2082024'])->count()),
    //         'Septiembre' => Tab::make()
    //             ->query(fn ($query) => $query->whereBetween('codigo_quincena', ['1092024', '2092024']))
    //             ->badge(CierreFinanciero::query()->whereBetween('codigo_quincena', ['1092024', '2092024'])->count()),
    //         'Octubre' => Tab::make()
    //             ->query(fn ($query) => $query->whereBetween('codigo_quincena', ['1102024', '2102024']))
    //             ->badge(CierreFinanciero::query()->whereBetween('codigo_quincena', ['1102024', '2102024'])->count()),
    //         'Noviembre' => Tab::make()
    //             ->query(fn ($query) => $query->whereBetween('codigo_quincena', ['1112024', '2112024']))
    //             ->badge(CierreFinanciero::query()->whereBetween('codigo_quincena', ['1112024', '2112024'])->count()),
    //         'Diciembre' => Tab::make()
    //             ->query(fn ($query) => $query->whereBetween('codigo_quincena', ['1122024', '2122024']))
    //             ->badge(CierreFinanciero::query()->whereBetween('codigo_quincena', ['1122024', '2122024'])->count()),
    //     ];
    // }

}
