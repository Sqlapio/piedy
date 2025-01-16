<?php

namespace App\Livewire;

use App\Models\DetalleEsGanPer;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;

class TableDetalleEsGanPer extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        return $table
            ->query(DetalleEsGanPer::query())
            ->columns([
                Tables\Columns\TextColumn::make('ingresos_usd')
                    ->description('Ingresos en USD')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('ingresos_bsd')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('mano_de_obra')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('otros_costos_directos')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('publicidad')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('comisiones_empleados')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('sueldos_empleados')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('alquiler')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('telefono')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('internet')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('gastos_financieros')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('perdidas_no_recurrentes')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('ingresos_financieros')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('ganancias_no_recurrentes')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user_id')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                //
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    //
                ]),
            ]);
    }

    public function render(): View
    {
        return view('livewire.table-detalle-es-gan-per');
    }
}
