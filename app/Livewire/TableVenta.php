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
            ->description('Tabla de ventas diarias. Las misma refleja ventas tanto de productos como servicios')
            ->query(Venta::query())
            ->columns([
                Tables\Columns\TextColumn::make('cod_asignacion')
                    ->searchable(),
                Tables\Columns\TextColumn::make('total_venta')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('fecha')
                    ->searchable(),
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
                    ->searchable(),
                Tables\Columns\TextColumn::make('metodo_pago_bolivares')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tasa_bcv')
                    ->numeric()
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
            ]);
    }

    public function render(): View
    {
        return view('livewire.table-venta');
    }
}