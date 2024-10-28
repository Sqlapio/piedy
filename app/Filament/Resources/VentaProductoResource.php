<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VentaProductoResource\Pages;
use App\Filament\Resources\VentaProductoResource\RelationManagers;
use App\Models\VentaProducto;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class VentaProductoResource extends Resource
{
    protected static ?string $model = VentaProducto::class;

    protected static ?string $navigationIcon = 'heroicon-s-presentation-chart-bar';

    protected static ?string $navigationGroup = 'Ventas';

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
                TextColumn::make('cod_asignacion')
                ->label('Códido')
                ->searchable()
                    ->sortable(),
                TextColumn::make('producto.descripcion')
                    ->icon('heroicon-s-shopping-bag')
                    ->numeric()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('empleado.name')
                    ->label('Vendido por:')
                    ->color('primary')
                    ->icon('heroicon-m-user')
                    ->numeric()
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha de Venta')
                    ->icon('heroicon-s-calendar-days')
                    ->dateTime()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('cantidad')
                    ->label('Cantidad')
                    ->icon('heroicon-o-square-3-stack-3d')
                    ->color('success')
                    ->numeric()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('costo_producto')
                    ->label('Costo')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('total_venta')
                    ->label('Total de venta')
                    ->icon('heroicon-m-currency-dollar')
                    ->color('success')
                    ->money('USD')
                        ->summarize(Sum::make()
                            ->money('USD')
                            ->label('Total($)')
                        )
                    ->sortable(),

                //Campos Ocultos
                TextColumn::make('metodo_pago')
                    ->label('Metodo de Pago')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('metodoUsd')
                    ->label('Metodo($)')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('metodoBsd')
                    ->label('Metodo(Bs.)')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('comision_empleado')
                    ->label(('Comision Empleado($)'))
                    ->icon('heroicon-m-currency-dollar')
                    ->color('colorTwo')
                    ->money('USD')
                    ->summarize(Sum::make()
                        ->money('USD')
                        ->label('Neto Empleado($)'))
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('comision_gerente')
                    ->label(('Comision Gerente($)'))
                    ->icon('heroicon-m-currency-dollar')
                    ->color('colorTwo')
                    ->money('USD')
                    ->summarize(Sum::make()
                        ->money('USD')
                        ->label('Neto Gerente($)'))
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('montoUsd')
                    ->label(('Monto($)'))
                    ->icon('heroicon-m-currency-dollar')
                    ->money('USD')
                    ->summarize(Sum::make()
                        ->money('USD')
                        ->label('Neto($)'))
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('montoBsd')
                    ->label(('Monto(Bs.)'))
                    ->icon('heroicon-s-credit-card')
                    ->money('VES')
                    ->summarize(Sum::make()
                        ->money('VES')
                        ->label('Neto(Bs.)'))
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('fecha_venta')
                    ->label('Fecha Venta')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('responsable')
                    ->label('Responsable')
                    ->color('primary')
                    ->icon('heroicon-m-user')
                    ->numeric()
                    ->searchable()
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
            'index' => Pages\ListVentaProductos::route('/'),
            'create' => Pages\CreateVentaProducto::route('/create'),
            'edit' => Pages\EditVentaProducto::route('/{record}/edit'),
        ];
    }
}
