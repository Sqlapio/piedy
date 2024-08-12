<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MovimientoGiftCardResource\Pages;
use App\Filament\Resources\MovimientoGiftCardResource\RelationManagers;
use App\Models\MovimientoGiftCard;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MovimientoGiftCardResource extends Resource
{
    protected static ?string $model = MovimientoGiftCard::class;

    protected static ?string $navigationIcon = 'heroicon-c-ticket';

    protected static ?string $navigationGroup = 'GiftCard';

    protected static ?string $navigationLabel = 'Detalle de Movimientos';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('gift_card_id')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('codigo_seguridad')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('cliente_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('monto_pagado')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('fecha_debito')
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
                Tables\Columns\TextColumn::make('gift_card_id')
                    ->searchable(),
                Tables\Columns\TextColumn::make('codigo_seguridad')
                    ->searchable(),
                Tables\Columns\TextColumn::make('cliente_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('monto_pagado')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('fecha_debito')
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
            'index' => Pages\ListMovimientoGiftCards::route('/'),
            'create' => Pages\CreateMovimientoGiftCard::route('/create'),
            'edit' => Pages\EditMovimientoGiftCard::route('/{record}/edit'),
        ];
    }
}
