<?php

namespace App\Filament\Resources\AuditoriaInventarioResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;
use App\Filament\Exports\DetalleMovimientoInventarioGeneralExporter;
use Filament\Tables\Actions\ExportAction;

class DetalleMovimientosInventarioGeneralRelationManager extends RelationManager
{
    protected static string $relationship = 'detalleMovimientosInventarioGeneral';

    protected static ?string $title = 'Salidas Almacen General';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('auditoria_inventario_id')
            ->columns([
                Tables\Columns\TextColumn::make('producto.descripcion'),
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
                Tables\Columns\TextColumn::make('fecha_movimiento')
                ->label('Fecha de Salida')
                ->dateTime()
            ])
            ->filters([
                //
            ])
            ->headerActions([
            ExportAction::make()
                ->color('primary')
                ->icon('heroicon-s-arrow-down-tray')
                ->exporter(DetalleMovimientoInventarioGeneralExporter::class)
            ]);
    }
}