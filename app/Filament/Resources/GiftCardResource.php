<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GiftCardResource\Pages;
use App\Filament\Resources\GiftCardResource\RelationManagers;
use App\Models\GiftCard;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class GiftCardResource extends Resource
{
    protected static ?string $model = GiftCard::class;

    protected static ?string $navigationIcon = 'heroicon-m-gift-top';

    protected static ?string $navigationGroup = 'GiftCard';

    protected static ?string $navigationLabel = 'Registradas';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('cod_gift_card')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('codigo_seguridad')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('pgc')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('cliente_id')
                    ->relationship('cliente', 'id')
                    ->required(),
                Forms\Components\TextInput::make('cliente')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('correo')
                    ->maxLength(255),
                Forms\Components\TextInput::make('monto')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('fecha_emicion')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('fecha_vence')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('metodo_pago')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('pago_usd')
                    ->required()
                    ->numeric()
                    ->default(0.00),
                Forms\Components\TextInput::make('pago_bsd')
                    ->required()
                    ->numeric()
                    ->default(0.00),
                Forms\Components\TextInput::make('referencia')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('responsable')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('barcode')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('status')
                    ->required()
                    ->maxLength(255)
                    ->default(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('cod_gift_card')
                    ->searchable(),
                Tables\Columns\TextColumn::make('codigo_seguridad')
                    ->searchable(),
                Tables\Columns\TextColumn::make('pgc')
                    ->searchable(),
                Tables\Columns\TextColumn::make('cliente')
                    ->searchable(),
                Tables\Columns\TextColumn::make('correo')
                    ->searchable(),
                Tables\Columns\TextColumn::make('monto')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('fecha_emicion')
                    ->label('Emitida')
                    ->color('success')
                    ->icon('heroicon-s-check-circle')
                    ->iconColor('success')
                    ->searchable(),
                Tables\Columns\TextColumn::make('fecha_vence')
                    ->label('Vence(6 Meses)')
                    ->color('danger')
                    ->icon('heroicon-s-x-circle')
                    ->iconColor('danger')
                    ->searchable(),
                Tables\Columns\TextColumn::make('metodo_pago')
                    ->searchable(),
                Tables\Columns\TextColumn::make('pago_usd')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('pago_bsd')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('referencia')
                    ->searchable(),
                Tables\Columns\TextColumn::make('responsable')
                    ->searchable(),
                IconColumn::make('status')
                    ->label('Estatus')
                    ->options([
                        'heroicon-s-check-circle' => fn ($state, $record): bool => $record->status === '1',
                        'heroicon-m-minus-circle' => fn ($state, $record): bool => $record->status === '2',
                    ])
                    ->colors([
                        'success' => '1',
                        'danger' => '2',
                    ]),
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
            'index' => Pages\ListGiftCards::route('/'),
            'create' => Pages\CreateGiftCard::route('/create'),
            'edit' => Pages\EditGiftCard::route('/{record}/edit'),
        ];
    }
}
