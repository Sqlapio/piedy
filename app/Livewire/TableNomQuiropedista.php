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
use Illuminate\Support\Facades\Blade;
use Livewire\Attributes\On;
use Spatie\LaravelPdf\Enums\Format;
use Spatie\LaravelPdf\Facades\Pdf;

class TableNomQuiropedista extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    #[On('nomina-calculada-quiropedista')]
    public function updateNominaCreada()
    {
        $this->reset();
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(NomQuiropedista::query()->where('fecha_calculo', now()->format('d-m-Y')))
            ->columns([
                TextColumn::make('name')
                    ->label('Empleado')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('total_servicios')
                    ->label('Servicios')
                    ->alignCenter()
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('promedio_duracion_servicios')
                    ->label('Duración(Min)')
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

                TextColumn::make('total_comisiones_venprod_dolares')
                    ->label('Comision Productos($)')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('asignaciones_bolivares')
                    ->label('Bono(Bs.)')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),


                TextColumn::make('deducciones_dolares')
                    ->label('Deduccion($)')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),


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
            ->defaultGroup('quincena')
            ->filters([
                //
            ])
            ->actions([
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
