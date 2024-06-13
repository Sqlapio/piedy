<?php

namespace App\Livewire;

use App\Models\NomQuiropedista;
use Filament\Enums\ThemeMode;
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
                    ->label('Empleado')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('total_servicios')
                    ->label('Servicios')
                    ->alignCenter()
                    ->sortable()
                    ->searchable(),

                TextColumn::make('promedio_duracion_servicios')
                    ->label('Promedio de Duración(Min)')
                    ->alignCenter()
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                    
                TextColumn::make('total_comision_dolares')
                    ->label('Comision($)')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('total_comision_bolivares')
                    ->label('Comision(Bs)')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('asignaciones_bolivares')
                    ->label('Bono(Bs.)')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('deducciones_dolares')
                    ->label('Deduccion($)')
                    ->sortable()
                    ->searchable(),


                TextColumn::make('total_dolares')
                ->label('Nomina($)')
                    ->summarize(Sum::make()
                        ->label('Total Nomina($)'))
                    ->sortable()
                    ->searchable(),

                TextColumn::make('total_bolivares')
                ->label('Nomina(Bs.)')
                    ->summarize(Sum::make()
                        ->label('Total Nomina(Bs.)'))
                    ->sortable()
                    ->searchable(),
                //
            ])
            ->striped()
            ->groups([
                'name',
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
