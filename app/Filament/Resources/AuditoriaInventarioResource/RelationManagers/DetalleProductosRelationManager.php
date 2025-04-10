<?php

namespace App\Filament\Resources\AuditoriaInventarioResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;
use App\Filament\Exports\DetalleAuditoriaProductoExporter;
use Filament\Tables\Actions\ExportAction;

class DetalleProductosRelationManager extends RelationManager
{
    protected static string $relationship = 'detalleProductos';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('auditoria_inventario_id')
            ->columns([
                Tables\Columns\TextColumn::make('producto.descripcion')
                    ->label('Producto'),
                Tables\Columns\TextColumn::make('cantidad')
                    ->label('Cantidad en Sucursal')
                    ->icon('heroicon-m-truck')
                    ->badge()
                    ->color(function (string $state): string {
                        if ($state > 5)  {
                            return 'success';
                        }else{
                            return 'danger';
                        };
                        
                    })
            ])
            ->filters([
                //
            ])
            ->headerActions([
                ExportAction::make()
                    ->color('primary')
                    ->icon('heroicon-s-arrow-down-tray')
                    ->exporter(DetalleAuditoriaProductoExporter::class)
            ]);
    }
}