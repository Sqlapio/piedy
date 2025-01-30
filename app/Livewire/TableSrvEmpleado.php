<?php

namespace App\Livewire;

use App\Models\VentaServicio;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;

class TableSrvEmpleado extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        return $table
            ->query(VentaServicio::query())
            ->columns([
                Tables\Columns\TextColumn::make('cod_asignacion')
                    ->searchable(),
                Tables\Columns\TextColumn::make('empleado_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('cliente_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('comision_dolares')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('comision_bolivares')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('propina_usd')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('propina_bsd')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('responsable_id')
                    ->numeric()
                    ->sortable(),
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
        return view('livewire.table-srv-empleado');
    }
}