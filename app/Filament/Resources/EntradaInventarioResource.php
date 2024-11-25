<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EntradaInventarioResource\Pages;
use App\Filament\Resources\EntradaInventarioResource\RelationManagers;
use App\Models\EntradaInventario;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EntradaInventarioResource extends Resource
{
    protected static ?string $model = EntradaInventario::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Manejo de Inventario';

    protected static ?string $navigationLabel = 'Entradas';

    protected static ?int $navigationSort = 6;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('almacen_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('producto_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('cantidad')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('tipo_movimiento')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('responsable')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('almacen.nombre')
                    ->icon('heroicon-s-building-office-2')
                    ->color('colorOne')
                    ->numeric()
                    ->searchable(),
                Tables\Columns\TextColumn::make('producto.descripcion')
                    ->icon('heroicon-m-document-check')
                    ->color('colorOne')
                    ->numeric()
                    ->searchable(),
                Tables\Columns\TextColumn::make('cantidad')
                    ->icon('heroicon-c-document-plus')
                    ->color('success')
                    ->numeric()
                    ->searchable(),
                Tables\Columns\TextColumn::make('tipo_movimiento')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('responsable')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->icon('heroicon-m-calendar-days')
                    ->dateTime()
                    ->searchable(),
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
            'index' => Pages\ListEntradaInventarios::route('/'),
            'create' => Pages\CreateEntradaInventario::route('/create'),
            'edit' => Pages\EditEntradaInventario::route('/{record}/edit'),
        ];
    }
}