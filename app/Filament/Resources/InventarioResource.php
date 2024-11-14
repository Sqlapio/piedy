<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InventarioResource\Pages;
use App\Filament\Resources\InventarioResource\RelationManagers;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\InventarioSucursalController;
use App\Models\Inventario;
use App\Models\Sucursal;
use App\Models\InventarioSucursal;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class InventarioResource extends Resource
{
    protected static ?string $model = Inventario::class;

    protected static ?string $navigationIcon = 'heroicon-s-square-3-stack-3d';

    protected static ?string $navigationGroup = 'Movimientos de inventario';

    protected static ?string $navigationLabel = 'Inventario General';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('producto_id')
                    ->relationship('producto', 'descripcion')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('almacen_id')
                    ->relationship('almacen', 'nombre')
                    ->searchable()
                    ->preload()
                    ->createOptionForm([
                        TextInput::make('nombre')
                            ->required(),
                    ])
                    ->required(),
                TextInput::make('cantidad')
                    ->required()
                    ->numeric(),
                TextInput::make('responsable')
                    ->default(Auth::user()->name)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(Inventario::query()->where('cantidad', '>', 0)->orderBy('created_at', 'desc'))
            ->columns([
                Tables\Columns\ImageColumn::make('producto.image')
                    ->label('Imagen')
                    ->circular(),
                Tables\Columns\TextColumn::make('producto.descripcion')
                    ->icon('heroicon-s-shopping-bag')
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
                                ->description('Debe llenar los campos de forma correcta. Campos Requeridos(*)')
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

                                        // Select::make('sucursal_id')
                                        //     ->label('Sucursal')
                                        //     ->prefixIcon('heroicon-c-building-office-2')
                                        //     ->options(Sucursal::all()->pluck('nombre', 'id'))
                                        //     ->searchable()
                                        //     ->required(),

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
                            InventarioController::asigancion_sucursal(
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
                                ->description('Debe llenar los campos de forma correcta. Campos Requeridos(*)')
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
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
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
