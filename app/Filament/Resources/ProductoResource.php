<?php

namespace App\Filament\Resources;

use Carbon\Carbon;
use Filament\Tables;
use Filament\Forms\Set;
use App\Models\Producto;
use Filament\Forms\Form;
use App\Models\Categoria;
use App\Models\Inventario;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\Filter;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
// use Filament\Tables\Columns\Layout\Grid;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use App\Http\Controllers\InventarioController;
use App\Filament\Resources\ProductoResource\Pages;
use AnourValar\EloquentSerialize\Tests\Models\Post;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class ProductoResource extends Resource
{

    protected static ?string $navigationLabel = 'Productos';

    protected static ?string $model = Producto::class;

    protected static ?string $navigationIcon = 'heroicon-m-shopping-cart';

    protected static ?string $navigationGroup = 'Manejo de Inventario';

    protected static ?int $navigationSort = 3;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return static::getModel()::count() > 10 ? 'success' : 'warning';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('REGISTRO DE PRODUCTOS')
                ->description('Formulario de registro de productos para la venta y de consumo interno')
                ->icon('heroicon-m-list-bullet')
                ->schema([
                    //Imagen del producto
                    Section::make()
                    ->schema([
                        FileUpload::make('image')
                        ->label('Imagen del Producto')
                        ->imageEditor()
                        ->imageEditorAspectRatios([
                            '16:9',
                            '4:3',
                            '1:1',
                        ]),
                    ])->columns(2),
                    
                    TextInput::make('descripcion')
                    ->prefixIcon('heroicon-s-pencil')
                    ->label('Descripción')
                    ->required(),

                    Select::make('categoria_id')
                    ->prefixIcon('heroicon-m-list-bullet')
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
                        ->prefixIcon('heroicon-c-tag')
                        ->label('Codigo del Producto'),

                    TextInput::make('precio_venta')
                        ->prefixIcon('heroicon-s-currency-dollar')
                        ->label('Precio de Venta')
                        ->prefix('$')
                        ->numeric()
                        ->inputMode('decimal'),

                    TextInput::make('costo')
                        ->prefixIcon('heroicon-s-currency-dollar')
                        ->label('Costo')
                        ->numeric()
                        ->inputMode('decimal'),

                    Select::make('tipo_empaquetado')
                        ->prefixIcon('heroicon-m-list-bullet')
                        ->label('Empaquetado en/por:')
                        ->required()
                        ->options([
                            'caja'    => 'Caja',
                            'unidad'  => 'Unidad',
                            'bulto'   => 'Bulto',
                            'paquete' => 'Paquete',
                        ]),
                        
                    TextInput::make('contenido_neto')
                        ->prefixIcon('heroicon-m-list-bullet')
                        ->label('Contenido Neto')
                        ->required()
                        ->numeric(),
                    
                    Select::make('unidad')
                        ->prefixIcon('heroicon-m-list-bullet')
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
                        ->prefixIcon('heroicon-s-inbox-arrow-down')
                        ->label('Uso')
                        ->options([
                            'consumo-interno' => 'Consumo Interno',
                            'venta' => 'Venta',
                        ])->required(),

                    TextInput::make('responsable')->default(Auth::user()->name)
                        ->prefixIcon('heroicon-c-user-circle')
                        ->label('Creado por:'),
                ])->columns(2),
                
                Section::make('MANEJO DE INVENTARIO')
                ->description('Informacion para el manejo de las cantiddes maximas y minimas de inventario')
                ->icon('heroicon-m-list-bullet')
                ->schema([
                    TextInput::make('max')
                        ->prefixIcon('heroicon-s-pencil')
                        ->label('Cantidad Maxima en existencia')
                        ->numeric(),
                        
                    TextInput::make('min')
                        ->prefixIcon('heroicon-s-pencil')
                        ->label('Cantidad Minima en existencia')
                        ->numeric(),

                    TextInput::make('existencia_min_sucursal')
                        ->prefixIcon('heroicon-s-pencil')
                        ->label('Cantidad Minima en existencia en sucursal')
                        ->default(5)
                        ->helperText('Este dato sera utilizado para el envio de notificaciones cuando el producto llegue a la cantidad minima en el almacen de la sucursal')
                        ->numeric(),
                ])->columns(3),
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
                ->toggleable(isToggledHiddenByDefault: true)
                    ->circular()
                    ->searchable(),

                TextColumn::make('descripcion')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->icon('heroicon-s-shopping-bag')
                    ->searchable(),

                TextColumn::make('uso')
                    ->toggleable(isToggledHiddenByDefault: false)

                    ->icon('heroicon-s-shopping-bag')
                    ->searchable(),

                TextColumn::make('categoria.descripcion')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),

                TextColumn::make('precio_venta')
                    ->toggleable(isToggledHiddenByDefault: false)

                    ->color('success')
                    ->icon('heroicon-s-currency-dollar')
                    ->money('USD')
                    ->alignCenter()
                    ->searchable(),

                TextColumn::make('costo')
                    ->toggleable(isToggledHiddenByDefault: false)

                    ->color('success')
                    ->icon('heroicon-s-currency-dollar')
                    ->money('USD')
                    ->alignCenter()
                    ->searchable(),

                TextColumn::make('contenido_neto')
                    ->toggleable(isToggledHiddenByDefault: false)

                    ->alignCenter()
                    ->searchable(),

                TextColumn::make('unidad')
                    ->toggleable(isToggledHiddenByDefault: false)

                    ->alignCenter()
                    ->searchable(),

                    TextColumn::make('tipo_empaquetado')
                    ->toggleable(isToggledHiddenByDefault: false)

                    ->label('Empaquetado en:')
                    ->toggleable(isToggledHiddenByDefault: false)

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
                    ->toggleable(isToggledHiddenByDefault: false)

                    ->label('Responsable')
                    ->color('primary')
                    ->icon('heroicon-m-user')
                    ->searchable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->toggleable(isToggledHiddenByDefault: false)

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
                Filter::make('created_at')
                    ->form([
                        DatePicker::make('desde'),
                        DatePicker::make('hasta'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['desde'] ?? null,
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['hasta'] ?? null,
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['desde'] ?? null) {
                            $indicators['desde'] = 'Venta desde ' . Carbon::parse($data['desde'])->toFormattedDateString();
                        }
                        if ($data['hasta'] ?? null) {
                            $indicators['hasta'] = 'Venta hasta ' . Carbon::parse($data['hasta'])->toFormattedDateString();
                        }

                        return $indicators;
                    }),
            ])
            ->filtersTriggerAction(
                fn(Action $action) => $action
                    ->button()
                    ->label('Filtros'),
            )
            ->actions([
                ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Action::make('Entrada')
                    ->icon('heroicon-s-calendar-days')
                    ->model(Producto::class)
                        ->form([
                            Section::make('Formulario')
                                ->description(function (Producto $record) {
                                    return 'Mover a sucursal: ' . $record->descripcion;
                                })
                                ->icon('heroicon-s-clipboard-document-list')
                                ->schema([
                                    Grid::make()
                                    ->schema([
                                        TextInput::make('min')
                                            ->label('Exitencia Minima en Almacen')
                                            ->prefixIcon('heroicon-s-queue-list')
                                            ->numeric(),
                                            // ->required(),
                                        Select::make('almacen_id')
                                            ->prefixIcon('heroicon-m-list-bullet')
                                            ->relationship('almacenes', 'nombre')
                                            ->searchable()
                                            ->preload()
                                            ->createOptionForm([
                                                TextInput::make('nombre')
                                                    ->required(),
                                            ]),
                                            // ->required(),,
                                        TextInput::make('cantidad')
                                            ->prefixIcon('heroicon-s-queue-list')
                                            ->required()
                                            ->numeric(),
                                    ]),
                                ])
                        ])->action(function (Producto $record, array $data) {
                            InventarioController::entrada_directa(
                                $record->id,
                                $record->uso,
                                $data['min'],
                                $data['almacen_id'],
                                $data['cantidad']
                            );
                        })
                    
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    ExportBulkAction::make()
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