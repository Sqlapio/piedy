<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\AnalisisReporte;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\Summarizers\Sum;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\AnalisisReporteResource\Pages;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use App\Filament\Resources\AnalisisReporteResource\RelationManagers;

class AnalisisReporteResource extends Resource
{
    protected static ?string $model = AnalisisReporte::class;

    protected static ?string $navigationIcon = 'heroicon-s-book-open';

    protected static ?string $navigationGroup = 'Módulo Contable';

    protected static ?int $navigationSort = 1;

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('sucursal.nombre')
                    ->label('Sucursal')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Generado el:')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('fecha_ini')
                    ->label('Rango de fechas')
                    ->alignCenter()
                    ->description(fn(AnalisisReporte $record): string => $record->fecha_fin)
                    ->searchable(),
                Tables\Columns\TextColumn::make('tasa_bcv')
                    ->label('Tasa BCV(Bs.)')
                    ->alignCenter()
                    ->numeric(),
                    

                //Ingresos-------------------------------------------
                
                Tables\Columns\TextColumn::make('total_srv')
                    ->color('info')
                    ->label('Servicios($)')
                    ->alignCenter()
                    ->numeric(),                
                Tables\Columns\TextColumn::make('total_prod')
                    ->color('info')
                    ->label('Productos($)')
                    ->alignCenter()
                    ->numeric(),
                Tables\Columns\TextColumn::make('sub_total_ingresos')
                    ->color('info')
                    ->label('Sub-Total Ingresos($)')
                    ->weight(FontWeight::Bold)
                    ->alignCenter()
                    ->numeric()
                    ->summarize(
                        Sum::make()
                            ->label('Ingresos($)')
                            ->numeric()
                    ),
                //Fin------------------------------------------------

                
                //Egresos -------------------------------------------
                
                Tables\Columns\TextColumn::make('total_gastos')
                    ->color('warning')
                    ->label('Gastos($)')
                    ->alignCenter()
                    ->numeric(),
                Tables\Columns\TextColumn::make('total_compras')
                    ->color('warning')
                    ->label('Compras($)')
                    ->alignCenter()
                    ->numeric(),
                Tables\Columns\TextColumn::make('productos_asignados')
                    ->color('warning')
                    ->label('Requisiciones($)')
                    ->alignCenter()
                    ->numeric(),
                Tables\Columns\TextColumn::make('nomina')
                    ->color('warning')
                    ->label('Nomina($)')
                    ->alignCenter()
                    ->numeric(),
                Tables\Columns\TextColumn::make('total_comisiones')
                    ->color('warning')
                    ->label('Comisiones($)')
                    ->alignCenter()
                    ->numeric(),                    
                Tables\Columns\TextColumn::make('sub_total_egresos')
                    ->color('warning')
                    ->label('Sub-Total Egresos($)')
                    ->weight(FontWeight::Bold)
                    ->alignCenter()
                    ->numeric()
                    ->summarize(
                        Sum::make()
                            ->label('Egresos($)')
                            ->numeric()
                    ),
                //Fin--------------------------------------------------



                //Neto del Periodo-------------------------------------
                
                Tables\Columns\TextColumn::make('neto')
                    ->color('success')
                    ->alignCenter()
                    ->weight(FontWeight::Bold)
                    ->label('Neto($)')
                    ->numeric()
                    ->summarize(Sum::make()
                        ->label('Neto($)')
                        ->numeric()
                    ),
                //Fin--------------------------------------------------



                //Campos Ocultos-----------------------------------------
                
                Tables\Columns\TextColumn::make('venta_servicios_usd')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('venta_servicios_bsd')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('con_ven_srv_bsd_a_usd')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('venta_productos_usd')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('venta_productos_bsd')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('con_ven_prod_bsd_a_usd')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('comisiones_usd')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('comisiones_bsd')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('con_comi_bsd_a_usd')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('compras_usd')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('compras_bsd')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('con_com_bsd_a_usd')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('gastos_usd')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('gastos_bsd')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('con_gas_bsd_a_usd')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                //Fin-----------------------------------------------

                
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
            'index' => Pages\ListAnalisisReportes::route('/'),
            'create' => Pages\CreateAnalisisReporte::route('/create'),
            'edit' => Pages\EditAnalisisReporte::route('/{record}/edit'),
        ];
    }
}