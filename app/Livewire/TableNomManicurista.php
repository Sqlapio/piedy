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
use Livewire\Attributes\On;

class TableNomManicurista extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    #[On('nomina-calculada-manicurista')]
    public function updateNominaCreada()
    {
        $this->reset();
    }

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
                    ->label('Servicios')
                    ->alignCenter()
                    ->sortable()
                    ->searchable(),

                TextColumn::make('total_comision_dolares')
                    ->label('Comision($)')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('total_comision_bolivares')
                    ->label('Comision(Bs.)')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('total_comision_venprod')
                    ->label('Comision Productos($)')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('asignaciones_bolivares')
                    ->label('Bono(Bs.)')
                    ->sortable()
                    ->searchable(),
                
                TextColumn::make('total_propina_bsd')
                    ->label('Propinas(Bs.)')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('total_propina_usd')
                    ->label('Propina($)')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('deducciones_dolares')
                    ->label('Deduccion($)')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('deducciones_bolivares')
                    ->label('Deduccion(Bs.)')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('comision_membresias')
                    ->label('Membresia(Bs.)')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('total_dolares')
                    ->label('Nomina($)')
                        ->money('USD')
                        ->summarize(Sum::make()
                            ->money('USD')
                            ->label('Total Nomina($)'))
                        ->sortable()
                        ->searchable(),
                TextColumn::make('total_bolivares')
                    ->label('Nomina(Bs.)')
                        ->money('VES')
                        ->summarize(Sum::make()
                            ->money('VES')
                            ->label('Total Nomina(Bs.)'))
                        ->sortable()
                        ->searchable(),
                //
            ])
            ->defaultGroup('quincena')
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
