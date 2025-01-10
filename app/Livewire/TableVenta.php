<?php

namespace App\Livewire;

use Filament\Tables;
use App\Models\Venta;
use Livewire\Component;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;

class TableVenta extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        return $table
            ->heading('VENTA DIARIA')
            ->description('Tabla de ventas diarias. Fecha: ' . date('d-m-Y'))
            ->query(Venta::query()->whereBetween('created_at', [now()->startOfDay(), now()->endOfDay()])
                ->orderBy('created_at', 'desc'))
            ->columns([
                Tables\Columns\TextColumn::make('cod_asignacion')
                    ->searchable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha')
                    ->dateTime()
                    ->sortable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),


                Tables\Columns\TextColumn::make('metodo_pago_dolares')
                    ->label('Metodos de Pago')
                    ->description(fn(Venta $record): string => $record->metodo_pago_bolivares)
                    ->searchable(),

                Tables\Columns\TextColumn::make('tasa_bcv')
                    ->label('Tasa BCV')
                    ->money('VES')
                    ->sortable(),

                Tables\Columns\TextColumn::make('responsable')
                    ->label('Responsable')
                    ->searchable(),

                Tables\Columns\TextColumn::make('total_venta')
                    ->label('Total de Venta')
                    ->money('USD')
                    ->summarize(Sum::make()
                        ->label(('Total'))
                        ->money('USD'))
                    ->sortable(),
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
            ])
            ->striped();
    }

    public function render(): View
    {
        return view('livewire.table-venta');
    }
}
