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
use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;

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
                TextColumn::make('responsable')->searchable(),
                TextColumn::make('created_at')
                ->label(__('Fecha de registro'))
                ->searchable(),
                TextColumn::make('cliente')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('empleado')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('metodo_pago')->searchable(),
                TextColumn::make('referencia')->searchable(),

                TextColumn::make('total_USD')
                    ->summarize(Sum::make()
                    ->numeric(
                        decimalPlaces: 00,
                        decimalSeparator: ',',
                        thousandsSeparator: '.',
                    )
                    ->label('Venta Neta($)'))
                    ->searchable(),

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

    public function inicio(){
        redirect()->to('/dashboard');
    }

    public function citas(){
        redirect()->to('/citas');
    }

    public function clientes(){
        redirect()->to('/clientes');
    }

    public function cabinas(){
        redirect()->to('/cabinas');
    }

    public function productos(){
        redirect()->to('/productos');
    }

    public function servicios(){
        redirect()->to('/servicios');
    }

    public function atras(){
        redirect()->to('/dashboard');
    }


    public function render()
    {
        return view('livewire.venta');
    }
}
