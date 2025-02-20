<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AuditoriaResource\Pages;
use App\Filament\Resources\AuditoriaResource\RelationManagers;
use App\Models\Auditoria;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AuditoriaResource extends Resource
{
    protected static ?string $model = Auditoria::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Módulo Contable';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('cod_auditoria')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('fecha_ini')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('fecha_fin')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('sucursal_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('producto_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('contenido_neto')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('unidad')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('cantidad_solicitada')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('gasto_total_usd')
                    ->numeric()
                    ->default(0.00),
                Forms\Components\TextInput::make('consumo_por_servicios')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('servicios_realizados')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('existencia_sucursal')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('existencia_central')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('responsable')
                    ->required()
                    ->maxLength(255),
            ]);
    }

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
                Tables\Columns\TextColumn::make('sucursal_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('producto_id')
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
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListAuditorias::route('/'),
            'create' => Pages\CreateAuditoria::route('/create'),
            'edit' => Pages\EditAuditoria::route('/{record}/edit'),
        ];
    }
}