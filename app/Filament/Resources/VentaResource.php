<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VentaResource\Pages;
use App\Filament\Resources\VentaResource\RelationManagers;
use App\Models\Venta;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class VentaResource extends Resource
{
    protected static ?string $model = Venta::class;

    protected static ?string $navigationGroup = 'Ventas';

    protected static ?string $navigationLabel = 'Dashboard Ventas';

    protected static ?string $navigationIcon = 'heroicon-s-presentation-chart-line';

    protected static ?int $navigationSort = 3;


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('cod_asignacion')
                ->label('Codigo Asignacion')
                    ->searchable(),
                Tables\Columns\TextColumn::make('total_venta')
                ->label('Total Venta')
                    ->money('USD')
                    ->sortable(),

                Tables\Columns\TextColumn::make('sucursal_id')
                ->label('Sucursal')
                    ->numeric()
                    ->sortable(),
                    Tables\Columns\TextColumn::make('responsable')
                    ->label('Responsable')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                ->label('Fecha de Creacion')
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
            'index' => Pages\ListVentas::route('/'),
            'create' => Pages\CreateVenta::route('/create'),
            'edit' => Pages\EditVenta::route('/{record}/edit'),
        ];
    }
}