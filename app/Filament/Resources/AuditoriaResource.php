<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\Auditoria;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\AuditoriaResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use App\Filament\Resources\AuditoriaResource\RelationManagers;

class AuditoriaResource extends Resource
{
    protected static ?string $model = Auditoria::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Módulo Contable';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('cod_auditoria')
                    ->searchable(),
                Tables\Columns\TextColumn::make('fecha_ini')
                    ->searchable(),
                Tables\Columns\TextColumn::make('fecha_fin')
                    ->searchable(),
                Tables\Columns\TextColumn::make('sucursal.nombre')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('producto.descripcion')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('contenido_neto')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('unidad')
                    ->searchable(),
                Tables\Columns\TextColumn::make('cantidad_solicitada')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('gasto_total_usd')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('consumo_por_servicios')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('servicios_realizados')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('existencia_sucursal')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('existencia_central')
                    ->numeric()
                    ->sortable(),
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
                // Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    ExportBulkAction::make()
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
            'index' => Pages\ListAuditorias::route('/'),
            'create' => Pages\CreateAuditoria::route('/create'),
            'edit' => Pages\EditAuditoria::route('/{record}/edit'),
        ];
    }
}