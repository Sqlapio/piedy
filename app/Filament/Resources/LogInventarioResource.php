<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LogInventarioResource\Pages;
use App\Filament\Resources\LogInventarioResource\RelationManagers;
use App\Models\LogInventario;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LogInventarioResource extends Resource
{
    protected static ?string $model = LogInventario::class;

    protected static ?string $navigationIcon = 'heroicon-m-exclamation-circle';

    protected static ?string $navigationGroup = 'Sistema';

    protected static ?string $navigationLabel = 'Logs de Sistema';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('accion')
                ->color('warning')
                ->icon('heroicon-c-bell-alert')
                    ->searchable(),
                Tables\Columns\TextColumn::make('descripcion')
                    ->icon('heroicon-m-information-circle')
                    ->color('colorTree')
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Usuario responsable')
                    ->color('primary')
                    ->icon('heroicon-m-user')
                    ->searchable(),
                Tables\Columns\TextColumn::make('navegador')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('ip')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha de Creación')
                    ->searchable()
                    ->icon('heroicon-s-calendar-days')
                    ->dateTime()
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
            'index' => Pages\ListLogInventarios::route('/'),
            'create' => Pages\CreateLogInventario::route('/create'),
            'edit' => Pages\EditLogInventario::route('/{record}/edit'),
        ];
    }
}
