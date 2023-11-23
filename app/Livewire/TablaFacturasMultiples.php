<?php

namespace App\Livewire;

use App\Models\FacturaMultiple;
use Barryvdh\Debugbar\Facades\Debugbar;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;
use WireUi\Traits\Actions;
use Livewire\Component;

class TablaFacturasMultiples extends Component implements HasForms, HasTable
{
    use Actions;
    use InteractsWithTable;
    use InteractsWithForms;

    public function table(Table $table): Table
    {
        return $table
            ->query(FacturaMultiple::query())
            ->columns([
                TextColumn::make('responsable')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('cod_asignacion')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('cliente')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('empleado')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label(__('Fecha de registro'))
                    ->searchable(),
                TextColumn::make('metodo_pago')->searchable(),
                TextColumn::make('referencia')->searchable(),

                TextColumn::make('total_usd')
                    ->summarize(Sum::make()
                    ->numeric(
                        decimalPlaces: 00,
                        decimalSeparator: '.',
                        thousandsSeparator: ',',
                    )
                    ->label('Venta Neta($)'))
                    ->searchable(),

                TextColumn::make('pago_usd')->money('USD')
                ->summarize(Sum::make()
                ->numeric(
                    decimalPlaces: 00,
                    decimalSeparator: '.',
                    thousandsSeparator: ',',
                )
                ->label('Total($)'))
                ->searchable(),

                TextColumn::make('pago_bsd')
                ->summarize(Sum::make()
                ->numeric(
                    decimalPlaces: 00,
                    decimalSeparator: '.',
                    thousandsSeparator: ',',
                )
                ->label('Total(Bs)'))
                ->searchable(),

            ])
            ->groups([
                'metodo_pago',
                'cliente',
                'empleado',
                'fecha_venta',
                'responsable'
            ])
            // ->defaultGroup('empleado')
            ->filters([
                DateRangeFilter::make('created_at')
                ->timezone('America/Caracas'),
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
        return view('livewire.tabla-facturas-multiples');
    }
}
