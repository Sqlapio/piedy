<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ConsumibleResource\Pages;
use App\Filament\Resources\ConsumibleResource\RelationManagers;
use App\Models\Consumible;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ConsumibleResource extends Resource
{
    protected static ?string $model = Consumible::class;

    protected static ?string $navigationIcon = 'heroicon-m-beaker';

    protected static ?string $navigationGroup = 'Configuración';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('producto_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('contenido_neto')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('unidad')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('can_srv')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('uso')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('tipo_uso')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('producto.descripcion')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('contenido_neto')
                    ->alignCenter()
                    ->numeric(),
                Tables\Columns\TextColumn::make('unidad')
                    ->alignCenter()
                    ->searchable(),
                Tables\Columns\TextColumn::make('can_srv')
                    ->label('Uso por Servicios')
                    ->alignCenter()
                    ->numeric(),
                Tables\Columns\TextColumn::make('uso')
                    ->label('Uso')
                    ->alignCenter()
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tipo_uso')
                    ->label('Usado por:')
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
            'index' => Pages\ListConsumibles::route('/'),
            'create' => Pages\CreateConsumible::route('/create'),
            'edit' => Pages\EditConsumible::route('/{record}/edit'),
        ];
    }
}