<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClienteResource\Pages;
use App\Filament\Resources\ClienteResource\RelationManagers;
use App\Models\Cliente;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ClienteResource extends Resource
{
    protected static ?string $model = Cliente::class;

    protected static ?string $navigationIcon = 'heroicon-m-user-group';

    protected static ?string $navigationGroup = 'Clientes';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return static::getModel()::count() > 10 ? 'success' : 'warning';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nombre')
                ->color('success')
                ->icon('heroicon-s-user-circle')
                ->searchable(),
                Tables\Columns\TextColumn::make('apellido')
                ->icon('heroicon-s-user-circle')
                ->searchable(),
                Tables\Columns\TextColumn::make('email')
                ->color('primary')
                ->icon('heroicon-c-at-symbol')
                ->searchable(),
                Tables\Columns\TextColumn::make('telefono')
                ->color('colorTree')
                ->icon('heroicon-c-device-phone-mobile')
                ->searchable(),
                Tables\Columns\TextColumn::make('visitas')
                ->color('colorTree')
                ->icon('heroicon-s-hand-thumb-up')
                ->searchable(),
                Tables\Columns\TextColumn::make('responsable')
                ->color('primary')
                ->icon('heroicon-s-user-circle')
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
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
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
            'index' => Pages\ListClientes::route('/'),
            // 'create' => Pages\CreateCliente::route('/create'),
            // 'edit' => Pages\EditCliente::route('/{record}/edit'),
        ];
    }
}