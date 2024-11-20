<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LogResource\Pages;
use App\Filament\Resources\LogResource\RelationManagers;
use App\Models\Log;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LogResource extends Resource
{
    protected static ?string $model = Log::class;

    protected static ?string $navigationIcon = 'heroicon-c-bug-ant';

    protected static ?string $navigationGroup = 'Sistema';

    protected static ?string $navigationLabel = 'Log del Sistema';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('accion')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                Forms\Components\TextInput::make('navegador')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('ip')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('descripcion')
                    ->maxLength(100),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(Log::query()->orderBy('created_at', 'desc'))
            ->columns([
                Tables\Columns\TextColumn::make('accion')
                    ->color('warning')
                    ->icon('heroicon-s-information-circle')
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Usuario')
                    ->icon('heroicon-s-user-circle')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('navegador')
                    ->label('Navegador')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('ip')
                ->label('Direccion IP')
                ->icon('heroicon-s-tv')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('descripcion')
                    ->label('Descripcion')
                    ->icon('heroicon-s-bell-alert')
                    ->searchable(),
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
            'index' => Pages\ListLogs::route('/'),
            'create' => Pages\CreateLog::route('/create'),
            'edit' => Pages\EditLog::route('/{record}/edit'),
        ];
    }
}
