<?php

namespace App\Livewire;

use App\Models\NomManicurista;
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

class TableNomManicurista extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        return $table
            ->query(NomManicurista::query())
            ->columns([
                TextColumn::make('name')
                    ->label('Empleado')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('total_servicios')
                    ->label('Total Servicios')
                    ->alignCenter()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('promedio_duracion_servicios')
                    ->label('Promedio de Duración(min)')
                    ->alignCenter()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('total_comision_dolares')
                    ->label('Comision($)')
                    ->money('USD')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('total_comision_bolivares')
                    ->label('Comision(Bs)')
                    ->money('VES')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('asignaciones_dolares')
                    ->label('Asignacion($)')
                    ->money('USD')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('asignaciones_bolivares')
                    ->label('Asignacion(Bs.)')
                    ->money('VES')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('deducciones_dolares')
                    ->label('Deduccion($)')
                    ->money('USD')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('deducciones_bolivares')
                    ->label('Deduccion(Bs.)')
                    ->money('VES')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('porcentaje_membresias')
                    ->label('Membresia(%)')
                    ->money('USD')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('comision_membresias')
                    ->label('Comision Membresia($)')
                    ->money('VES')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('fecha_ini')
                    ->alignCenter()
                    ->label('Desde')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('fecha_fin')
                ->alignCenter()
                    ->label('Hasta')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('total_dolares')
                    ->money('USD')
                    ->summarize(Sum::make()
                        ->money('USD')
                        ->label('Total Nomina($)'))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('total_bolivares')
                    ->money('VES')
                    ->summarize(Sum::make()
                        ->money('VES')
                        ->label('Total Nomina(Bs.)'))
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
        return view('livewire.table-nom-manicurista');
    }
}
