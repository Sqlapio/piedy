<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductoResource\Pages;
use App\Filament\Resources\ProductoResource\RelationManagers;
use App\Models\Categoria;
use App\Models\Comision;
use App\Models\Producto;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
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
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget\Stat;

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
                TextInput::make('cod_producto')->default('Ppro-'.random_int(11111, 99999)),
                TextInput::make('descripcion')->required(),
                Select::make('categoria_id')
                    ->relationship('categoria', 'descripcion')
                    ->searchable()
                    ->preload()
                    ->createOptionForm([
                        TextInput::make('descripcion')
                            ->required(),
                    ])
                    ->required(),
                TextInput::make('precio_venta')
                    ->prefix('$')
                    ->numeric()
                    ->inputMode('decimal'),
                TextInput::make('existencia')
                    ->numeric()
                    ->required(),
                DatePicker::make('fecha_carga')->format('d-m-Y'),
                TextInput::make('unidad')
                    ->numeric(),
                TextInput::make('contenido_neto')
                    ->numeric(),
                TextInput::make('comision_venta_emp')
                    ->numeric(),
                TextInput::make('comision_venta_gte')
                    ->numeric(),
                Select::make('status')
                    ->options([
                        'activo' => 'Activo',
                        'inactivo' => 'Inactivo',
                    ]),
                FileUpload::make('image')
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
            ->columns([
                TextColumn::make('cod_producto')->searchable(),
                ImageColumn::make('image')
                    ->circular()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                TextColumn::make('descripcion')->searchable(),
                TextColumn::make('categoria.descripcion')->searchable(),
                TextColumn::make('precio_venta')
                    ->money('USD')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                TextColumn::make('existencia')->searchable(),
                TextColumn::make('fecha_carga')->searchable(),
                TextColumn::make('unidad')->searchable(),
                TextColumn::make('contenido_neto')->searchable(),
                TextColumn::make('comision_venta_emp')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                TextColumn::make('comision_venta_gte')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                IconColumn::make('status')
                ->options([
                    'heroicon-s-check-circle' => fn ($state, $record): bool => $record->status === 'activo',
                    'heroicon-m-minus-circle' => fn ($state, $record): bool => $record->status === 'inactivo',
                ])
                ->colors([
                    'danger' => 'inactivo',
                    'success' => 'activo',
                ]),
                TextColumn::make('responsable')->searchable(),

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
