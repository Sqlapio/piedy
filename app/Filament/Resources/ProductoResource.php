<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductoResource\Pages;
use App\Filament\Resources\ProductoResource\RelationManagers;
use App\Models\Comision;
use App\Models\Producto;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductoResource extends Resource
{
    protected static ?string $model = Producto::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-group';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('cod_producto')->default('Ppro-'.random_int(11111, 99999)),
                TextInput::make('descripcion')->required(),
                TextInput::make('existencia')
                    ->numeric()
                    ->minValue(5)
                    ->required(),
                TextInput::make('precio')
                    ->prefix('$')
                    ->numeric()
                    ->minValue(5)
                    ->required(),
                Select::make('comision_id')
                    ->relationship('comision', 'porcentaje')
                    ->options(Comision::where('aplicacion', 'producto')->pluck('porcentaje', 'id'))
                    ->searchable()
                    
                    ->preload()
                    ->createOptionForm([
                        TextInput::make('cod_comision')->default('Pco-'.random_int(11111, 99999)),
                        TextInput::make('porcentaje')
                            ->prefix('%')
                            ->required(),
                    ])
                    ->required()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('cod_producto'),
                Tables\Columns\TextColumn::make('descripcion'),
                Tables\Columns\TextColumn::make('existencia'),
                Tables\Columns\TextColumn::make('precio')->money('USD'),
                Tables\Columns\TextColumn::make('comision.porcentaje'),
                IconColumn::make('status')
                ->options([
                    'heroicon-o-check-circle' => fn ($state, $record): bool => $record->status === 'activo',
                    'heroicon-o-clock'        => fn ($state, $record): bool => $record->status === 'inactivo',
                ])
                ->colors([
                    'danger' => 'inactivo',
                    'success' => 'activo',
                ]),
                
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
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
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
            'index' => Pages\ListProductos::route('/'),
            'create' => Pages\CreateProducto::route('/create'),
            'edit' => Pages\EditProducto::route('/{record}/edit'),
        ];
    }    
}
