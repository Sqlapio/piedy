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
                ->label('Numero Operacion')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('sucursal.nombre')
                ->label('Sucursal')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('rif')
                ->label('Rif')
                    ->searchable(),
                Tables\Columns\TextColumn::make('razon_social')
                ->label('Razon Social')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tipo_prov')
                ->label('Tipo Proveedor')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nro_comprobante')
                ->label('Nro Comprobante')
                    ->searchable(),
                Tables\Columns\TextColumn::make('fecha_documento')
                ->label('Fecha Documento')
                    ->searchable(),
                Tables\Columns\TextColumn::make('fecha_apli_retencion')
                    ->label('Fecha Retencion')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nro_planilla_importacion')
                    ->label('Nro Planilla Importacion')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nro_expediente_importacion')
                    ->label('Nro Expediente Importacion')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nro_decla_aduana')
                    ->label('Nro Declaracion Aduana')
                    ->searchable(),
                Tables\Columns\TextColumn::make('fecha_decla_aduana')
                    ->label('Fecha Declaracion Aduana')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nro_documento')
                    ->label('Nro Documento')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nro_control')
                    ->label('Nro Control')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nro_nota_debito')
                    ->label('Nro Nota Debito')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nro_nota_credito')
                    ->label('Nro Nota Credito')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tipo_transaccion')
                    ->label('Tipo Transaccion')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nro_doc_afectado')
                    ->label('Nro Doc Afectado')    
                    ->searchable(),
                Tables\Columns\TextColumn::make('total_importacion_con_iva')
                    ->label('Total Importacion Con Iva')    
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('impor_exenta_exoneradas')
                    ->label('Impor Exenta Exoneradas')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('base_imponible_importaciones, 8, 2')
                    ->label('Base Imponible Importaciones')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('porcen_alicuota_importaciones')
                    ->label('Porcen Alicuota Importaciones')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('impuesto_iva_importaciones')
                    ->label('Impuesto Iva Importaciones')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_comp_con_iva')
                    ->label('Total Comp Con Iva')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('comp_sin_derecho_credito')
                    ->label('Comp Sin Derecho Credito')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('compras_exentas')
                    ->label('Compras Exentas')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('compras_exoneradas')
                    ->label('Compras Exoneradas')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('compras_no_sujetas')
                    ->label('Compras No Sujetas')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('base_imponible_internas')
                    ->label('Base Imponible Internas')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('porcen_alicuota_internas')
                    ->label('Porcen Alicuota Internas')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('impuesto_iva_internas')
                    ->label('Impuesto Iva Internas')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('responsable')
                    ->label('Responsable')    
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha de Creación')    
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Fecha de Actualizacion')
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