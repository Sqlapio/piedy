<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Get;
use Filament\Forms\Set;
use App\Models\Producto;
use App\Models\Sucursal;
use Filament\Forms\Form;
use App\Models\Inventario;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Models\InventarioSucursal;
use Filament\Forms\Components\Grid;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\ActionGroup;
use App\Http\Controllers\InventarioController;
use App\Filament\Resources\InventarioResource\Pages;
use App\Http\Controllers\InventarioSucursalController;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use App\Filament\Resources\InventarioResource\RelationManagers;

class InventarioResource extends Resource
{
    protected static ?string $model = Inventario::class;

    protected static ?string $navigationIcon = 'heroicon-s-square-3-stack-3d';

    protected static ?string $navigationGroup = 'Manejo de Inventario';

    protected static ?string $navigationLabel = 'Inventario General';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('CARGA DE INVENTARIO POR PRODUCTO')
                    ->description('Formulario de carga de inventario')
                    ->icon('heroicon-s-square-3-stack-3d')
                    ->schema([
                        Select::make('producto_id')
                            ->prefixIcon('heroicon-m-list-bullet')
                            ->relationship('producto', 'descripcion')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (Set $set, ?string $state) {
                                $uso = Producto::find($state)->uso;
                                $set('uso', $uso);
                            }),

                        TextInput::make('uso')
                            ->prefixIcon('heroicon-s-queue-list')
                            ->required(),

                        TextInput::make('min')
                            ->label('Exitencia Minima en Almacen')
                            ->prefixIcon('heroicon-s-queue-list')
                            ->numeric()
                            ->required(),
                        Select::make('almacen_id')
                            ->prefixIcon('heroicon-m-list-bullet')
                            ->relationship('almacen', 'nombre')
                            ->searchable()
                            ->preload()
                            ->createOptionForm([
                                TextInput::make('nombre')
                                    ->required(),
                            ])
                            ->required(),
                        TextInput::make('cantidad')
                            ->prefixIcon('heroicon-s-queue-list')
                            ->required()
                            ->numeric(),
                        TextInput::make('responsable')
                            ->prefixIcon('heroicon-c-user-circle')
                            ->default(Auth::user()->name)
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(Inventario::query()->orderBy('created_at', 'desc'))
            ->columns([
                Tables\Columns\ImageColumn::make('producto.image')
                    ->label('Imagen')
                    ->circular(),
                Tables\Columns\TextColumn::make('producto.descripcion')
                    ->icon('heroicon-s-shopping-bag')
                    ->numeric()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('uso')
                    ->icon('heroicon-s-shopping-bag')
                    ->numeric()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('min')
                    ->label('Exitencia Minima')
                    ->icon('heroicon-o-square-3-stack-3d')
                    ->numeric()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('almacen.nombre')
                    ->icon('heroicon-c-building-office-2')
                    ->numeric()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('cantidad')
                    ->label('Existencia')
                    ->icon('heroicon-o-square-3-stack-3d')
                    ->color('success')
                    ->numeric()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('responsable')
                    ->label('Responsable')
                    ->color('primary')
                    ->icon('heroicon-m-user')
                    ->numeric()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha de Creación')
                    ->icon('heroicon-s-calendar-days')
                    ->dateTime()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->groups([
                'almacen.nombre',
            ])
            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\Action::make('Mover a Sucursal')
                        ->icon('heroicon-s-truck')
                        ->model(InventarioSucursal::class)
                        ->form([
                            Section::make('Formulario')
                                ->description(function (Inventario $record) {
                                    return 'Mover a sucursal: ' . $record->producto->descripcion;
                                })
                                ->icon('heroicon-s-clipboard-document-list')
                                ->schema([
                                    Grid::make()
                                        ->schema([

                                            //Seleccion del producto
                                            Select::make('sucursal_id')
                                                ->label('Sucursal')
                                                ->prefixIcon('heroicon-c-building-office-2')
                                                ->options(Sucursal::all()->pluck('nombre', 'id'))
                                                ->searchable()
                                                ->required(),

                                            //Cantidad
                                            TextInput::make('cantidad')
                                                ->label('Cantidad')
                                                ->prefixIcon('heroicon-c-squares-plus')
                                                ->numeric()
                                                ->required(),

                                            //Responsable
                                            TextInput::make('responsable')
                                                ->label('Responsable del Movimiento')
                                                ->prefixIcon('heroicon-c-squares-plus')
                                                ->default(Auth::user()->name)
                                                ->disabled()

                                        ]),
                                ])
                        ])->action(function (Inventario $record, array $data) {
                            InventarioController::asignacion_sucursal(
                                $record->id,
                                $data['sucursal_id'],
                                $data['cantidad']
                            );
                        }),
                    Tables\Actions\Action::make('Reposición')
                        ->icon('heroicon-c-arrow-uturn-down')
                        ->model(Inventario::class)
                        ->form([
                            Section::make('Formulario')
                                ->description(function (Inventario $record) {
                                    return 'Mover a sucursal: ' . $record->producto->descripcion;
                                })
                                ->icon('heroicon-s-clipboard-document-list')
                                ->schema([
                                    Grid::make()
                                        ->schema([
                                            //Cantidad
                                            TextInput::make('cantidad')
                                                ->label('Cantidad')
                                                ->prefixIcon('heroicon-c-squares-plus')
                                                ->numeric()
                                                ->required(),

                                            //Responsable
                                            TextInput::make('responsable')
                                                ->label('Responsable del Movimiento')
                                                ->prefixIcon('heroicon-c-squares-plus')
                                                ->default(Auth::user()->name)
                                                ->disabled()

                                        ]),
                                ])
                        ])->action(function (Inventario $record, array $data) {
                            InventarioController::reposicion(
                                $record->id,
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
            'index' => Pages\ListInventarios::route('/'),
            'create' => Pages\CreateInventario::route('/create'),
            'edit' => Pages\EditInventario::route('/{record}/edit'),
        ];
    }
}
