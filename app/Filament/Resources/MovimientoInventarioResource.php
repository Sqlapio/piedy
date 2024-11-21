<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MovimientoInventarioResource\Pages;
use App\Filament\Resources\MovimientoInventarioResource\RelationManagers;
use App\Models\MovimientoInventario;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MovimientoInventarioResource extends Resource
{
    protected static ?string $model = MovimientoInventario::class;

    protected static ?string $navigationIcon = 'heroicon-s-clipboard-document-list';

    protected static ?string $navigationGroup = 'Movimientos de inventario';

    protected static ?int $navigationSort = 6;

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('producto.descripcion')
                    ->icon('heroicon-s-shopping-bag')
                    ->numeric()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('cantidad')
                    ->label('Cantidad')
                    ->icon('heroicon-o-square-3-stack-3d')
                    ->color('success')
                    ->numeric()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tipo_movimiento')
                    ->searchable(),
                Tables\Columns\TextColumn::make('codigo')
                    ->label('Código de Movimiento')
                    ->icon('heroicon-s-hashtag')
                    ->badge()
                    ->color('colorTwo')
                    ->searchable(),
                Tables\Columns\TextColumn::make('sucursal.nombre')
                    ->icon('heroicon-c-building-office-2')
                    ->label('Sucursal')
                    ->numeric()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('responsable')
                    ->label('Responsable')
                    ->color('primary')
                    ->icon('heroicon-m-user')
                    ->numeric()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha de Creación')
                    ->icon('heroicon-s-calendar-days')
                    ->dateTime()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                // // Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
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
            'index' => Pages\ListMovimientoInventarios::route('/'),
            'create' => Pages\CreateMovimientoInventario::route('/create'),
            'edit' => Pages\EditMovimientoInventario::route('/{record}/edit'),
        ];
    }
}