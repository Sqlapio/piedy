<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MembresiaResource\Pages;
use App\Filament\Resources\MembresiaResource\RelationManagers;
use App\Models\Membresia;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MembresiaResource extends Resource
{
    protected static ?string $model = Membresia::class;

    protected static ?string $navigationIcon = 'heroicon-c-identification';

    protected static ?string $navigationGroup = 'Membresias';

    protected static ?string $navigationLabel = 'Registradas';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('cod_membresia')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('pm')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('cliente_id')
                    ->relationship('cliente', 'id')
                    ->required(),
                Forms\Components\TextInput::make('cliente')
                    ->maxLength(255),
                Forms\Components\TextInput::make('correo')
                    ->maxLength(255),
                Forms\Components\TextInput::make('fecha_activacion')
                    ->maxLength(100),
                Forms\Components\TextInput::make('fecha_exp')
                    ->maxLength(255),
                Forms\Components\TextInput::make('monto')
                    ->numeric()
                    ->default(40.00),
                Forms\Components\TextInput::make('referencia')
                    ->maxLength(100),
                Forms\Components\TextInput::make('barcode')
                    ->maxLength(100),
                Forms\Components\TextInput::make('status')
                    ->numeric()
                    ->default(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('cod_membresia')
                    ->searchable(),
                Tables\Columns\TextColumn::make('pm')
                    ->searchable(),
                Tables\Columns\TextColumn::make('cliente')
                    ->searchable(),
                Tables\Columns\TextColumn::make('correo')
                    ->searchable(),
                Tables\Columns\TextColumn::make('fecha_activacion')
                    ->label('Activada')
                    ->color('success')
                    ->icon('heroicon-s-check-circle')
                    ->iconColor('success')
                    ->searchable(),
                Tables\Columns\TextColumn::make('fecha_exp')
                    ->label('Vence')
                    ->color('danger')
                    ->icon('heroicon-s-x-circle')
                    ->iconColor('danger')
                    ->searchable(),
                Tables\Columns\TextColumn::make('referencia')
                    ->searchable(),
                IconColumn::make('status')
                    ->label('Estatus')
                    ->options([
                        'heroicon-s-check-circle' => fn ($state, $record): bool => $record->status === 1,
                        'heroicon-m-minus-circle' => fn ($state, $record): bool => $record->status === 2,
                    ])
                    ->colors([
                        'success' => 1,
                        'danger' => 2,
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
            'index' => Pages\ListMembresias::route('/'),
            'create' => Pages\CreateMembresia::route('/create'),
            'edit' => Pages\EditMembresia::route('/{record}/edit'),
        ];
    }
}
