<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MovimientoEntradaResource\Pages;
use App\Filament\Resources\MovimientoEntradaResource\RelationManagers;
use App\Models\MovimientoEntrada;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MovimientoEntradaResource extends Resource
{
    protected static ?string $navigationLabel = 'Entradas';

    protected static ?string $navigationGroup = 'Movimientos de inventario';

    protected static ?string $model = MovimientoEntrada::class;

    protected static ?string $navigationIcon = 'heroicon-s-squares-plus';

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
            'index' => Pages\ListMovimientoEntradas::route('/'),
            'create' => Pages\CreateMovimientoEntrada::route('/create'),
            'edit' => Pages\EditMovimientoEntrada::route('/{record}/edit'),
        ];
    }
}
