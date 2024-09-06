<?php

namespace App\Filament\Resources\CierreFinancieroResource\Widgets;

use App\Filament\Resources\CierreFinancieroResource\Pages\ListCierreFinancieros;
use App\Models\IndicadorVentaGerente as ModelsIndicadorVentaGerente;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\TableWidget as BaseWidget;

class IndicadorVentaGerente extends BaseWidget
{
    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        $date = ('m');
        return $table
            ->query(ModelsIndicadorVentaGerente::query())
            ->columns([
                TextColumn::make('user.name')
                    ->sortable(),
                TextColumn::make('productos_vendidos')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('gift_card_vendidas')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('membresias_vendidas')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('servicios_vip_vendidos')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('dias_trabajados')
                    ->searchable()
                    ->sortable(),
                // TextColumn::make('numero_quincena')
                //     ->searchable()
                //     ->sortable(),
                // TextColumn::make('mes')
                //     ->searchable()
                //     ->sortable(),
            ])
            ->filters([
                SelectFilter::make('mes')
                ->options([
                    'Enero' => 'Enero',
                    'Febrero' => 'Febrero',
                    'Marzo' => 'Marzo',
                    'Abril' => 'Abril',
                    'Mayo' => 'Mayo',
                    'Junio' => 'Junio',
                    'Julio' => 'Julio',
                    'Agosto' => 'Agosto',
                    'Septiembre' => 'Septiembre',
                    'Octubre' => 'Octubre',
                    'Noviembre' => 'Noviembre',
                    'Diciembre' => 'Diciembre',
                ]),
                SelectFilter::make('numero_quincena')
                ->options([
                    '1' => 'Primera Quicena',
                    '2' => 'Segunda Quincena',
                ]),
            ]);
    }

}
