<?php

namespace App\Livewire;

use App\Models\Producto;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;

class ListProducts extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        return $table
            ->query(Producto::query())
            ->columns([
                TextColumn::make('cod_producto')->searchable(),
                ImageColumn::make('image')
                    ->circular()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                TextColumn::make('descripcion')->searchable(),
                TextColumn::make('categoria.descripcion')->searchable(),
                TextColumn::make('precio_venta')
                    ->money('USD')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                TextColumn::make('existencia')->color('error')->searchable(),
                TextColumn::make('fecha_carga')->searchable(),
                TextColumn::make('unidad')->searchable(),
                TextColumn::make('contenido_neto')->searchable(),
                TextColumn::make('comision_venta_emp')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                TextColumn::make('comision_venta_gte')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                IconColumn::make('status')
                ->options([
                    'heroicon-s-check-circle' => fn ($state, $record): bool => $record->status === 'activo',
                    'heroicon-m-minus-circle' => fn ($state, $record): bool => $record->status === 'inactivo',
                ])
                ->colors([
                    'danger' => 'inactivo',
                    'success' => 'activo',
                ]),
                TextColumn::make('responsable')->searchable(),

            ])
            ->recordClasses(fn (Producto $record) => match ($record->existencia <= 5) {
                5 => 'border-s-2 border-orange-600',
                4 => 'border-s-2 border-orange-600 dark:border-orange-300',
                3 => 'border-s-2 border-green-600 dark:border-green-300',
                default => null,
            })
            ->groups([
                'categoria.descripcion',
                'unidad'
            ])
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                    Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                    Tables\Actions\CreateAction::make(),
            ]);
    }

    public function render(): View
    {
        return view('livewire.list-products');
    }
}
