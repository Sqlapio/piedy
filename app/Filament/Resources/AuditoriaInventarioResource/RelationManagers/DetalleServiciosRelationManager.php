<?php

namespace App\Filament\Resources\AuditoriaInventarioResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Tables\Actions\ExportAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;
use App\Filament\Exports\DetalleAuditoriaServicioExporter;

class DetalleServiciosRelationManager extends RelationManager
{
    protected static string $relationship = 'detalleServicios';

    protected static ?string $title = 'Servicios';


    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('auditoria_inventario_id')
            ->columns([
                Tables\Columns\TextColumn::make('servicio')
                ->label('Servicio'),
                Tables\Columns\TextColumn::make('cantidad')
                ->label('Total Facturados')
                ->icon('heroicon-c-check-circle')
                ->badge()
                ->color('success')
            ])
            ->filters([
                //
            ])
            ->headerActions([
                ExportAction::make()
                    ->color('primary')
                    ->icon('heroicon-s-arrow-down-tray')
                    ->exporter(DetalleAuditoriaServicioExporter::class)
            ]);
    }
}