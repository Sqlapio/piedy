<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ConsumoTecnicoResource\Pages;
use App\Filament\Resources\ConsumoTecnicoResource\RelationManagers;
use App\Models\ConsumoTecnico;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ConsumoTecnicoResource extends Resource
{
    protected static ?string $model = ConsumoTecnico::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Manejo de Inventario';

    protected static ?int $navigationSort = 9;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('producto_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('contenido')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('unidad')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('cant_servicio')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('total_uso')
                    ->required()
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('producto.descripcion')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('contenido')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('unidad')
                    ->searchable(),
                Tables\Columns\TextColumn::make('cant_servicio')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_uso')
                    ->numeric()
                    ->sortable(),
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
            'index' => Pages\ListConsumoTecnicos::route('/'),
            'create' => Pages\CreateConsumoTecnico::route('/create'),
            'edit' => Pages\EditConsumoTecnico::route('/{record}/edit'),
        ];
    }
}