<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductoResource\Pages;
use App\Models\Producto;
use App\Models\Categoria;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Set;
use Illuminate\Support\Str;

class ProductoResource extends Resource
{

    protected static ?string $navigationLabel = 'Productos';

    protected static ?string $model = Producto::class;

    protected static ?string $navigationIcon = 'heroicon-m-shopping-cart';

    protected static ?string $navigationGroup = 'Movimientos de inventario';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                TextInput::make('descripcion')
                ->label('Descripción')
                ->required(),

                Select::make('categoria_id')
                ->label('Categoría')
                    ->relationship('categoria', 'descripcion')
                    ->searchable()
                    ->preload()
                    ->createOptionForm([
                        TextInput::make('descripcion')
                            ->required(),
                            ])
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (Set $set, ?string $state) {
                                $sigla = Categoria::find($state)->siglas;
                                $set('cod_producto', 'Ppro-'.$sigla.'-'.random_int(11111, 99999));
                            }),
                TextInput::make('cod_producto')
                ->label('Codigo del Producto'),

                TextInput::make('precio_venta')
                ->label('Precio de Venta')
                    ->prefix('$')
                    ->numeric()
                    ->inputMode('decimal'),
                TextInput::make('costo')
                    ->label('Costo')
                    ->prefix('$')
                    ->numeric()
                    ->inputMode('decimal'),
                Select::make('tipo_empaquetado')
                ->label('Empaquetado en/por:')
                    ->required()
                    ->options([
                        'caja'   => 'Caja',
                        'inidad' => 'Unidad',
                        'bulto'  => 'Bulto',
                    ]),
                TextInput::make('contenido_neto')
                ->label('Contenido Neto')
                    ->required()
                    ->numeric(),
                Select::make('unidad')
                ->label('Unidad')
                    ->required()
                    ->options([
                        'gr'        => 'Gramos',
                        'ml'        => 'Mililitros',
                        'oz'        => 'Onzas',
                        'par'       => 'Pares',
                        'pzas'      => 'Piezas',
                        'hojas'     => 'Hojas',
                        'und'       => 'Unidad',
                        'litros'    => 'Litros',
                        'galon'     => 'Galon',
                        'kl'        => 'Kilos',
                    ]),
                Select::make('uso')
                ->label('Uso')
                    ->options([
                        'consumo-interno' => 'Consumo Interno',
                        'venta' => 'Venta',
                    ])->required(),
                TextInput::make('responsable')->default(Auth::user()->name)
                ->label('Creado por:'),

                Select::make('status')
                ->label('Estatus')
                    ->options([
                        'activo' => 'Activo',
                        'inactivo' => 'Inactivo',
                    ]),
                FileUpload::make('image')
                ->label('Imagen del Producto')
                    ->imageEditor()
                    ->imageEditorAspectRatios([
                        '16:9',
                        '4:3',
                        '1:1',
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(Producto::query()->orderBy('created_at', 'desc'))
            ->columns([

                TextColumn::make('cod_producto')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),

                ImageColumn::make('image')
                    ->circular()
                    ->searchable(),

                TextColumn::make('descripcion')
                    ->icon('heroicon-s-shopping-bag')
                    ->searchable(),

                TextColumn::make('categoria.descripcion')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),

                TextColumn::make('precio_venta')
                    ->color('success')
                    ->icon('heroicon-s-currency-dollar')
                    ->money('USD')
                    ->alignCenter()
                    ->searchable(),

                TextColumn::make('costo')
                    ->color('success')
                    ->icon('heroicon-s-currency-dollar')
                    ->money('USD')
                    ->alignCenter()
                    ->searchable(),

                TextColumn::make('contenido_neto')
                    ->alignCenter()
                    ->searchable(),

                TextColumn::make('unidad')
                    ->alignCenter()
                    ->searchable(),

                    TextColumn::make('tipo_empaquetado')
                    ->label('Empaquetado en:')
                    ->alignCenter()
                    ->searchable(),

                // IconColumn::make('status')
                //     ->alignCenter()
                //     ->options([
                //         'heroicon-s-check-circle' => fn ($state, $record): bool => $record->status === 'activo',
                //         'heroicon-m-minus-circle' => fn ($state, $record): bool => $record->status === 'inactivo',
                //     ])
                //     ->colors([
                //         'danger' => 'inactivo',
                //         'success' => 'activo',
                //     ]),

                TextColumn::make('responsable')
                    ->label('Responsable')
                    ->color('primary')
                    ->icon('heroicon-m-user')
                    ->searchable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha de Creación')
                    ->icon('heroicon-s-calendar-days')
                    ->dateTime()
                    ->searchable()
                    ->sortable(),

            ])
            ->groups([
                'categoria.descripcion',
                'unidad'
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
