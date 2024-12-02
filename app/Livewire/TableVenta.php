<?php

namespace App\Livewire;

use App\Models\Venta;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;
use Illuminate\Contracts\View\View;

class TableVenta extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;
    
    public function table(Table $table): Table
    {
        return $table
            ->heading('VENTA DIARIA')
            ->description('Tabla de ventas diarias. Fecha: ' . date('d-m-Y'))
            ->query(Venta::query()->whereBetween('created_at', [now()->startOfDay(), now()->endOfDay()]))
            ->columns([
                Tables\Columns\TextColumn::make('cod_asignacion')
                    ->searchable(),
                Tables\Columns\TextColumn::make('total_venta')
                    ->money('USD')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('responsable')
                    ->searchable(),
                    Tables\Columns\TextColumn::make('metodo_pago_dolares')
                    ->description(fn (Venta $record): string => $record->metodo_pago_bolivares)
                    ->searchable(),
                Tables\Columns\TextColumn::make('tasa_bcv')
                    ->money('VES')
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