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

    protected static ?string $navigationGroup = 'Movimientos de inventario';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('cod_producto')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('producto_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('producto')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('cantidad')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('contenido_neto')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('fecha_entrega')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('empleado_id')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('empleado')
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
                Tables\Columns\TextColumn::make('cod_producto')
                    ->searchable(),
                Tables\Columns\TextColumn::make('producto')
                    ->searchable(),
                Tables\Columns\TextColumn::make('cantidad')
                    ->searchable(),
                Tables\Columns\TextColumn::make('contenido_neto')
                    ->searchable(),
                Tables\Columns\TextColumn::make('fecha_entrega')
                    ->searchable(),
                Tables\Columns\TextColumn::make('empleado')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('responsable')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
