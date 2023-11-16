<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MovimientoSalidaResource\Pages;
use App\Filament\Resources\MovimientoSalidaResource\RelationManagers;
use App\Models\MovimientoSalida;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MovimientoSalidaResource extends Resource
{
    protected static ?string $navigationLabel = 'Salidas';

    protected static ?string $navigationGroup = 'Movimientos de inventario';

    protected static ?string $model = MovimientoSalida::class;

    protected static ?string $navigationIcon = 'heroicon-o-squares-plus';

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
            'index' => Pages\ListMovimientoSalidas::route('/'),
            'create' => Pages\CreateMovimientoSalida::route('/create'),
            'edit' => Pages\EditMovimientoSalida::route('/{record}/edit'),
        ];
    }
}
