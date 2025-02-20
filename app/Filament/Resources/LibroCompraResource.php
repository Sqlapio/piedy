<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\LibroCompra;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\LibroCompraResource\Pages;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use App\Filament\Resources\LibroCompraResource\RelationManagers;

class LibroCompraResource extends Resource
{
    protected static ?string $model = LibroCompra::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    
    protected static ?string $navigationGroup = 'Módulo Contable';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('gasto_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('sucursal.nombre')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('rif')
                    ->searchable(),
                Tables\Columns\TextColumn::make('razon_social')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tipo_prov')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nro_comprobante')
                    ->searchable(),
                Tables\Columns\TextColumn::make('fecha_documento')
                    ->searchable(),
                Tables\Columns\TextColumn::make('fecha_apli_retencion')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nro_planilla_importacion')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nro_expediente_importacion')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nro_decla_aduana')
                    ->searchable(),
                Tables\Columns\TextColumn::make('fecha_decla_aduana')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nro_documento')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nro_control')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nro_nota_debito')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nro_nota_credito')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tipo_transaccion')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nro_doc_afectado')
                    ->searchable(),
                Tables\Columns\TextColumn::make('total_importacion_con_iva')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('impor_exenta_exoneradas')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('base_imponible_importaciones, 8, 2')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('porcen_alicuota_importaciones')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('impuesto_iva_importaciones')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_comp_con_iva')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('comp_sin_derecho_credito')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('compras_exentas')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('compras_exoneradas')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('compras_no_sujetas')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('base_imponible_internas')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('porcen_alicuota_internas')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('impuesto_iva_internas')
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
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListLibroCompras::route('/'),
            'create' => Pages\CreateLibroCompra::route('/create'),
            'edit' => Pages\EditLibroCompra::route('/{record}/edit'),
        ];
    }
}