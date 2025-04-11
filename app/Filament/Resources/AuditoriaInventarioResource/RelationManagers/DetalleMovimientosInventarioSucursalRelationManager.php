<?php

namespace App\Filament\Resources\AuditoriaInventarioResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Tables\Actions\ExportAction;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\Summarizers\Sum;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;
use App\Filament\Exports\DetalleMovimientoInventarioSucursalExporter;

class DetalleMovimientosInventarioSucursalRelationManager extends RelationManager
{
    protected static string $relationship = 'detalleMovimientosInventarioSucursal';

    protected static ?string $title = 'Salidas Almacen Sucursal';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('auditoria_inventario_id')
            ->columns([
                Tables\Columns\TextColumn::make('producto.descripcion'),
                Tables\Columns\TextColumn::make('consumo')
                    ->label('Tipo de Consumo')
                    ->alignCenter()
                    ->badge()
                    ->color('info')
                    ->searchable(),
                Tables\Columns\TextColumn::make('fecha_movimiento')
                    ->label('Fecha de Salida')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('responsable')
                    ->label('Responsable'),
                Tables\Columns\TextColumn::make('cantidad')
                    ->label('Cantidad saliente')
                    ->icon('heroicon-m-truck')
                    ->badge()
                    ->color(function (string $state): string {
                        if ($state > 5) {
                            return 'success';
                        } else {
                            return 'danger';
                        };
                    }),
                
                Tables\Columns\TextColumn::make('costo')
                    ->label('Costo')
                    ->numeric(),
                Tables\Columns\TextColumn::make('total')
                    ->label('Subtotal')
                    ->numeric()
                    ->summarize(Sum::make()
                        ->money('USD')
                        ->label('Total(US$)'))
            ])
            ->filters([
                //
            ])
            ->headerActions([
            ExportAction::make()
                ->color('primary')
                ->icon('heroicon-s-arrow-down-tray')
                ->exporter(DetalleMovimientoInventarioSucursalExporter::class)
            ]);
    }
}