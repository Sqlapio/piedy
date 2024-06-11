<?php

namespace App\Livewire;

use App\Models\NomQuiropedista;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;

class TableNomQuiropedista extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        return $table
            ->query(NomQuiropedista::query())
            ->columns([
                TextColumn::make('name')
                    ->label('PCS')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('total_servicios')
                    ->label('PCS')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('promedio_duracion_servicios')
                    ->label('PCS')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('total_comision_dolares')
                    ->label('PCS')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('total_comision_bolivares')
                    ->label('PCS')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('asignaciones_dolares')
                    ->label('PCS')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('asignaciones_bolivares')
                    ->label('PCS')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('deducciones_dolares')
                    ->label('PCS')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('deducciones_bolivares')
                    ->label('PCS')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('fecha_ini')
                    ->label('PCS')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('fecha_fin')
                    ->label('PCS')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('total_nomina')
                    ->label('PCS')
                    ->sortable()
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
        return view('livewire.table-nom-quiropedista');
    }
}
