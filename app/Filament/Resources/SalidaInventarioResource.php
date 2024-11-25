<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SalidaInventarioResource\Pages;
use App\Models\SalidaInventario;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SalidaInventarioResource extends Resource
{
    protected static ?string $model = SalidaInventario::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Manejo de Inventario';

    protected static ?string $navigationLabel = 'Salidas';

    protected static ?int $navigationSort = 7;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('almacen_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('producto_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('sucursal_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('cantidad')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('tipo_movimiento')
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
                Tables\Columns\TextColumn::make('almacen.nombre')
                    ->icon('heroicon-s-building-office-2')
                    ->color('colorOne')
                    ->numeric()
                    ->searchable(),
                Tables\Columns\TextColumn::make('producto.descripcion')
                    ->icon('heroicon-m-document-check')
                    ->color('colorOne')
                    ->numeric()
                    ->searchable(),
                Tables\Columns\TextColumn::make('sucursal.nombre')
                    ->icon('heroicon-s-building-office-2')
                    ->color('colorTree')
                    ->numeric()
                    ->searchable(),
                Tables\Columns\TextColumn::make('cantidad')
                    ->icon('heroicon-c-document-minus')
                    ->color('danger')
                    ->numeric()
                    ->searchable(),
                Tables\Columns\TextColumn::make('tipo_movimiento')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('responsable')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->icon('heroicon-m-calendar-days')
                    ->dateTime()
                    ->searchable(),
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
            'index' => Pages\ListSalidaInventarios::route('/'),
            'create' => Pages\CreateSalidaInventario::route('/create'),
            // 'edit' => Pages\EditSalidaInventario::route('/{record}/edit'),
        ];
    }
}