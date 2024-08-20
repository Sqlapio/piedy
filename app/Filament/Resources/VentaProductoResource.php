<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VentaProductoResource\Pages;
use App\Filament\Resources\VentaProductoResource\RelationManagers;
use App\Models\VentaProducto;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class VentaProductoResource extends Resource
{
    protected static ?string $model = VentaProducto::class;

    protected static ?string $navigationIcon = 'heroicon-s-presentation-chart-bar';

    protected static ?string $navigationGroup = 'Ventas';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('cod_asignacion')
                ->searchable()
                    ->sortable(),
                TextColumn::make('empleado.name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('producto.descripcion')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('costo_producto')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('comision_empleado')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('comision_gerente')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('fecha_venta')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('cantidad')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('total_venta')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('responsable')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVentaProductos::route('/'),
            'create' => Pages\CreateVentaProducto::route('/create'),
            'edit' => Pages\EditVentaProducto::route('/{record}/edit'),
        ];
    }
}
