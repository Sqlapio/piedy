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

class ListGastos extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        return $table
            ->query(Gasto::query()->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()]))
            ->columns([
                TextColumn::make('descripcion')
                ->label('Descripción del gasto')
                ->sortable()
                ->searchable(),
                TextColumn::make('forma_pago')
                ->label('Forma de pago')
                ->searchable(),
                TextColumn::make('created_at')
                ->label('Fecha/Hora del gasto')
                ->sortable()
                ->searchable(),
                TextColumn::make('responsable')
                ->label('Responsable')
                ->searchable(),
                TextColumn::make('monto_usd')
                ->label('Monto($)')
                ->money('USD')
                    ->summarize(Sum::make()
                        ->label(('Total de gastos'))
                        ->money('USD'))
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
