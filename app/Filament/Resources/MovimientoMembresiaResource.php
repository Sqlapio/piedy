<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MovimientoMembresiaResource\Pages;
use App\Filament\Resources\MovimientoMembresiaResource\RelationManagers;
use App\Models\MovimientoMembresia;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MovimientoMembresiaResource extends Resource
{
    protected static ?string $model = MovimientoMembresia::class;

    protected static ?string $navigationIcon = 'heroicon-c-shield-check';

    protected static ?string $navigationGroup = 'Gestión de Membresias';

    protected static ?string $navigationLabel = 'Movimiento de Membresias';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('membresia_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('descripcion')
                    ->maxLength(100),
                Forms\Components\TextInput::make('cliente_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('cliente')
                    ->maxLength(100),
                Forms\Components\TextInput::make('cedula')
                    ->maxLength(100),
                Forms\Components\TextInput::make('empleado')
                    ->maxLength(100),
                Forms\Components\TextInput::make('empleado_id')
                    ->numeric(),
                Forms\Components\TextInput::make('responsable')
                    ->maxLength(100),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('membresia_id')
                    ->label('ID')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('descripcion')
                    ->searchable(),
                Tables\Columns\TextColumn::make('cliente')
                    ->searchable(),
                Tables\Columns\TextColumn::make('cedula')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('empleado')
                    ->searchable(),
                Tables\Columns\TextColumn::make('responsable')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListMovimientoMembresias::route('/'),
            'create' => Pages\CreateMovimientoMembresia::route('/create'),
            'edit' => Pages\EditMovimientoMembresia::route('/{record}/edit'),
        ];
    }
}
