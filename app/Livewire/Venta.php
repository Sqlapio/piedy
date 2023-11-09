<?php

namespace App\Livewire;

use App\Models\VentaServicio;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Venta extends Component implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;
    
    public function table(Table $table): Table
    {
        return $table
            ->query(VentaServicio::query())
            ->columns([
                TextColumn::make('cod_asignacion')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('cliente')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('empleado')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('fecha_venta')->searchable(),
                TextColumn::make('metodo_pago')->searchable(),
                TextColumn::make('referencia')->searchable(),

                TextColumn::make('total_USD')
                ->summarize(Sum::make()
                // ->money('USD')
                ->numeric(
                    decimalPlaces: 00,
                    decimalSeparator: ',',
                    thousandsSeparator: '.',
                )
                ->label('Venta Neta($)'))
                ->searchable(),
                // TextColumn::make('total_USD')->summarize(Sum::make()),

                TextColumn::make('pago_usd')->money('USD')
                ->summarize(Sum::make()
                ->numeric(
                    decimalPlaces: 00,
                    decimalSeparator: ',',
                    thousandsSeparator: '.',
                )
                ->label('Total($)'))
                ->searchable(),

                TextColumn::make('pago_bsd')
                ->summarize(Sum::make()
                ->numeric(
                    decimalPlaces: 00,
                    decimalSeparator: ',',
                    thousandsSeparator: '.',
                )
                ->label('Total(Bs)'))
                ->searchable(),
                
            ])
            ->groups([
                'metodo_pago',
                'cliente',
                'empleado',
                'fecha_venta'
            ])
            // ->defaultGroup('empleado')
            ->filters([
                // ...
            ])
            ->actions([
                // ...
            ])
            ->bulkActions([
                // ...
            ]);
    }

    public function render()
    {
        return view('livewire.venta');
    }
}
