<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VentaProductoResource\Pages;
use App\Filament\Resources\VentaProductoResource\RelationManagers;
use App\Models\VentaProducto;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
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
                //
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
            'index' => Pages\ListVentaProductos::route('/'),
            'create' => Pages\CreateVentaProducto::route('/create'),
            'edit' => Pages\EditVentaProducto::route('/{record}/edit'),
        ];
    }    
}
