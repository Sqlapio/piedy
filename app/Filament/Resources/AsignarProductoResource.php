<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AsignarProductoResource\Pages;
use App\Filament\Resources\AsignarProductoResource\RelationManagers;
use App\Models\AsignarProducto;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AsignarProductoResource extends Resource
{
    protected static ?string $model = AsignarProducto::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Modulo de Inventario';

    protected static ?string $navigationLabel = 'Productos Asignados';

    protected static ?int $navigationSort = 11;

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                
                Tables\Columns\TextColumn::make('asignacion')
                ->label('Asignacion')
                ->badge()
                ->icon(fn(string $state): string => match ($state) {
                    'tienda' => 'heroicon-s-building-storefront',
                    'tecnico' => 'heroicon-c-user-plus',
                })
                ->color(fn(string $state): string => match ($state) {
                    'tienda' => 'warning',
                    'tecnico' => 'success',
                })
                ->alignCenter()
                ->sortable(),

                Tables\Columns\TextColumn::make('producto.descripcion')
                ->label('Producto')
                ->searchable()
                ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('cantidad')
                ->label('Cantidad')
                ->alignCenter()
                ->numeric()
                ->sortable(),
                Tables\Columns\TextColumn::make('responsable')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                ->label('Fecha de Asignacion')
                    ->dateTime()
                    ->sortable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('sucursal.nombre')
                    ->searchable(),
                Tables\Columns\TextColumn::make('servicios_facturados')
                ->label('Servicios o Dias ')
                ->alignCenter()
                ->numeric()
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
            'index' => Pages\ListAsignarProductos::route('/'),
            'create' => Pages\CreateAsignarProducto::route('/create'),
            'edit' => Pages\EditAsignarProducto::route('/{record}/edit'),
        ];
    }
}