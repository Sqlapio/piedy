<?php

namespace App\Livewire;

use App\Models\CierreGeneral;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;

class CierreGeneralTable extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        return $table
            ->query(CierreGeneral::query())
            ->columns([
                Tables\Columns\TextColumn::make('total_ventas')
                    ->label('Total de Servicios')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_dolares_efectivo')
                    ->label('Total Dolares($) Efectivo')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_dolares_zelle')
                    ->label('Total Dolares($) ZELLE')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_bolivares')
                    ->label('Total Bolivares')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha de cierre')
                    ->searchable(),
                Tables\Columns\TextColumn::make('responsable')
                    ->searchable(),
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
        return view('livewire.cierre-general-table');
    }
}
