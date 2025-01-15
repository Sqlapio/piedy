<?php

namespace App\Filament\Supervisor\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Producto;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Requisicion;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\TextInputColumn;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Supervisor\Resources\ProductoResource\Pages;
use App\Filament\Supervisor\Resources\ProductoResource\RelationManagers;

class ProductoResource extends Resource
{
    protected static ?string $model = Producto::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('cod_producto')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('descripcion')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('unidad')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('contenido_neto')
                    ->numeric(),
                Forms\Components\TextInput::make('uso')
                    ->maxLength(100),
                Forms\Components\TextInput::make('tipo_empaquetado')
                    ->maxLength(100),
                Forms\Components\TextInput::make('total_unidades')
                    ->numeric(),
                Forms\Components\TextInput::make('marca')
                    ->maxLength(100),
                Forms\Components\TextInput::make('total_paquetes')
                    ->numeric(),
                Forms\Components\TextInput::make('max')
                    ->numeric(),
                Forms\Components\TextInput::make('min')
                    ->numeric(),
                Forms\Components\TextInput::make('existencia_min_sucursal')
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\TextColumn::make('cod_producto')
                    ->searchable(),
                Tables\Columns\TextColumn::make('descripcion')
                    ->searchable(),
                Tables\Columns\TextColumn::make('contenido_neto')
                    ->numeric()
                    ->alignCenter()
                    ->sortable(),
                Tables\Columns\TextColumn::make('unidad')
                    ->searchable()
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('uso')
                    ->searchable(),
                TextInputColumn::make('cant_req')
                    ->label('Cantidad Requerida')
                    ->default(0),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('Agregar')
                    ->icon('heroicon-s-truck')
                    ->model(Producto::class)
                    ->color('success')
                    ->hidden(function (Producto $record) {
                        if (Producto::where('id', $record['id'])->first()->cant_req >= 1) {
                            return false;
                        } else {
                            return true;
                        }
                    })
                    ->requiresConfirmation()
                    ->action(function (Producto $record, array $data) {
                        dd($record);
                    }),
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
            'index' => Pages\ListProductos::route('/'),
            'create' => Pages\CreateProducto::route('/create'),
            'edit' => Pages\EditProducto::route('/{record}/edit'),
        ];
    }
}
