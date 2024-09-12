<?php

namespace App\Livewire;

use App\Models\Gasto;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\On;

class ListGastos extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    #[On('carga-gastos')]
    public function updateNominaCreada()
    {
        $this->reset();
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Gasto::query()->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()]))
            ->columns([
                TextColumn::make('descripcion')
                ->label('Descripción')
                ->sortable()
                ->searchable(),
                TextColumn::make('forma_pago')
                ->label('Forma de pago')
                ->searchable(),
                TextColumn::make('fecha_factura')
                ->label('Fecha Fectura')
                ->sortable()
                ->searchable(),
                TextColumn::make('numero_factura')
                ->label('Nro. Factura')
                ->searchable(),
                TextColumn::make('monto_usd')
                ->label('Monto($)')
                ->money('USD')
                    ->summarize(Sum::make()
                        ->label(('Total($)'))
                        ->money('USD'))
                ->searchable(),
                TextColumn::make('monto_bsd')
                ->label('Monto(Bsd)')
                    ->summarize(Sum::make()
                        ->label(('Total(Bs.)'))
                        ->money('VEF'))
                ->searchable(),

                //
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
        return view('livewire.list-gastos');
    }
}
