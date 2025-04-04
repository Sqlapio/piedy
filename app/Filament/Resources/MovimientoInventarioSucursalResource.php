<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use App\Models\MovimientoInventarioSucursal;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use App\Filament\Resources\MovimientoInventarioSucursalResource\Pages;
use App\Filament\Resources\MovimientoInventarioSucursalResource\RelationManagers;

class MovimientoInventarioSucursalResource extends Resource
{
    protected static ?string $model = MovimientoInventarioSucursal::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Modulo de Inventario';

    protected static ?int $navigationSort = 7;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('sucursal_id')
                    ->required()
                    ->numeric(),
                Forms\Components\Select::make('producto_id')
                    ->relationship('producto', 'id')
                    ->required(),
                Forms\Components\TextInput::make('cantidad')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('tipo_movimiento')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('consumo')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('responsable')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('sucursal.nombre')
                    ->label('Sucursal')
                    ->icon('heroicon-s-building-office-2')
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('producto.descripcion')
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('cantidad')
                    ->alignCenter()
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('tipo_movimiento')
                    ->label('Tipo')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'entrada' => 'success',
                        'salida' => 'danger',
                    })
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('consumo')
                    ->label('Tipo de Consumo')
                    ->alignCenter()
                    ->badge()
                    ->color('info')
                    ->searchable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Generado el:')
                    ->dateTime()
                    ->icon('heroicon-m-calendar-days')
                    ->sortable(),
                Tables\Columns\TextColumn::make('responsable')
                    ->searchable(),
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
            'index' => Pages\ListMovimientoInventarioSucursals::route('/'),
            'create' => Pages\CreateMovimientoInventarioSucursal::route('/create'),
            'edit' => Pages\EditMovimientoInventarioSucursal::route('/{record}/edit'),
        ];
    }
}