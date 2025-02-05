<?php

namespace App\Filament\Resources;

use Carbon\Carbon;
use App\Models\Iva;
use Filament\Forms;
use Filament\Tables;
use App\Models\Compra;
use App\Models\Almacen;
use App\Models\TasaBcv;
use Filament\Forms\Get;
use Filament\Forms\Set;
use App\Models\Sucursal;
use Filament\Forms\Form;
use App\Models\MetodoPago;
use Filament\Tables\Table;
use Forms\Components\TextInput;
use Filament\Resources\Resource;
use App\Models\ConfiguracionNomina;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\Filter;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\ToggleButtons;
use App\Filament\Resources\CompraResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use App\Filament\Resources\CompraResource\RelationManagers;

class CompraResource extends Resource
{
    protected static ?string $model = Compra::class;

    protected static ?string $navigationIcon = 'heroicon-m-shopping-cart';

    protected static ?string $navigationGroup = 'Modulo Administrativo';

    protected static ?int $navigationSort = 4;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('INFORMACION DE LA COMPRA')
                ->description('Formulario de compras')
                ->icon('heroicon-m-arrow-trending-down')
                ->schema([

                    Forms\Components\TextInput::make('numero_factura_compra')
                    ->label('Nro. Factura')
                    ->required()
                    ->prefixIcon('heroicon-c-tag')
                    ->rules(['required', 'numeric'])
                    ->validationMessages([
                        'required'  => 'Campo requerido',
                        'numeric'    => 'Solo admite números',
                    ]),

                    Forms\Components\DatePicker::make('fecha_compra')
                    ->label('Fecha de Factura de compra')
                    ->prefixIcon('heroicon-m-calendar-days')
                    ->required()
                    ->format('d-m-Y'),

                    Forms\Components\TextInput::make('descripcion')
                    ->label('Descripción de la compra')
                    ->prefixIcon('heroicon-s-pencil')
                    ->required()
                        ->maxLength(255),

                    Forms\Components\Select::make('proveedor_id')
                    ->prefixIcon('heroicon-s-truck')
                    ->relationship('proveedor', 'nombre')
                    ->searchable()
                        ->preload()
                        ->createOptionForm([
                            Forms\Components\TextInput::make('rif')
                                ->label('Rif')
                                ->required(),
                            Forms\Components\TextInput::make('nombre')
                            ->label('Nombre/Razon Social')
                            ->required(),
                        ])
                        ->required(),

                    Forms\Components\Select::make('forma_pago')
                    ->label('Forma de Pago')
                    ->prefixIcon('heroicon-m-list-bullet')
                    ->required()
                        ->live()
                        ->options([
                            'dolares' => 'Dolares',
                            'bolivares' => 'Bolivares',
                        ]),

                    Forms\Components\Select::make('metodo_pago')
                    ->prefixIcon('heroicon-s-truck')
                    ->label('Metodo de Pago')
                    ->required()
                        ->options(function (Get $get) {
                            if ($get('forma_pago') == 'dolares') {
                                return MetodoPago::where('moneda', 'usd')->pluck('descripcion', 'id');
                            }

                            if ($get('forma_pago') == 'bolivares') {
                                return MetodoPago::where('moneda', 'bsd')->pluck('descripcion', 'id');
                            }
                        })
                        ->live(),

                    Forms\Components\TextInput::make('responsable')
                    ->prefixIcon('heroicon-s-home')
                    ->label('Cargado por:')
                    ->disabled()
                        ->dehydrated()
                        ->default(Auth::user()->name),

                    Forms\Components\Section::make()
                        ->schema([
                            Forms\Components\Textarea::make('observacion')
                            ->label('Observaciones Relevante'),
                        ])
                ])->columns(2),

                Forms\Components\Section::make('ASOCIADO A:')
                ->description('Formulario para la asociacion')
                ->icon('heroicon-m-arrow-trending-down')
                ->schema([
                    Forms\Components\Select::make('almacen_id')
                    ->prefixIcon('heroicon-s-home')
                    ->live()
                        ->label('Almacenes')
                        ->options(Almacen::all()->pluck('nombre', 'id')),

                    Forms\Components\Select::make('sucursal_id')
                    ->prefixIcon('heroicon-s-home')
                    ->live()
                        ->label('Sucursal')
                        ->options(Sucursal::all()->pluck('nombre', 'id')),
                ])->columns(2),

                Forms\Components\Section::make('COSTOS:')
                ->description('Formulario para los costos de la compra')
                ->icon('heroicon-m-arrow-trending-down')
                ->schema([

                    ToggleButtons::make('feedback')
                    ->label('Maneja IVA?')
                    ->boolean()
                        ->inline()
                        ->live()
                        ->hidden(function (Get $get) {
                            if ($get('forma_pago') == 'bolivares') {
                                return false;
                            } else {
                                return true;
                            }
                        })
                        ->default(false)
                        ->columnSpanFull(),

                    Forms\Components\TextInput::make('tasa_bcv')
                    ->hidden(function (Get $get) {
                        if ($get('forma_pago') == 'bolivares') {
                            return false;
                        } else {
                            return true;
                        }
                    })
                        ->live()
                        ->numeric()
                        ->required(),

                    Forms\Components\TextInput::make('monto_usd')
                    ->label('Monto en USD($)')
                    ->prefixIcon('heroicon-s-currency-dollar')
                    ->numeric()
                    ->live(onBlur: true)
                    ->hidden(function (Get $get) {
                        if ($get('forma_pago') == 'dolares') {
                            return false;
                        } else {
                            return true;
                        }
                    })
                    ->afterStateUpdated(function (Get $get, Set $set) {
                        self::updateTotales($get, $set);
                    })
                    ->placeholder('0.00'),
                    

                    Forms\Components\TextInput::make('monto_bsd')
                    ->label('Monto en BSD(Bs.)')
                    ->prefixIcon('heroicon-m-credit-card')
                    ->hidden(function (Get $get) {
                        if ($get('forma_pago') == 'bolivares') {
                            return false;
                        } else {
                            return true;
                        }
                    })
                    ->numeric()
                    ->placeholder('0.00')
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (Get $get, Set $set) {
                        self::updateTotales($get, $set);
                    }),
                    

                    Forms\Components\TextInput::make('iva')
                        ->label('IVA(%)')
                        ->prefixIcon('heroicon-m-credit-card')
                        ->hidden(function (Get $get) {
                            if ($get('feedback') == true) {
                                return false;
                            } else {
                                return true;
                            }
                        })
                        ->live()
                        ->disabled()
                        ->dehydrated()
                        ->numeric()
                        ->default(0.00),

                    Forms\Components\TextInput::make('total_compra_bsd')
                    ->label('Total Compra en Bolivares(Bs.)')
                    ->prefixIcon('heroicon-m-credit-card')
                    ->live()
                    ->disabled()
                    ->dehydrated()
                    ->numeric()
                    ->placeholder('0.00'),

                    Forms\Components\TextInput::make('conversion_a_usd')
                    ->label('Total Compra en Dolares($)')
                    ->prefixIcon('heroicon-m-credit-card')
                    ->live()
                    ->disabled()
                    ->dehydrated()
                    ->numeric()
                    ->placeholder('0.00'),

                ])
                    ->hidden(function (Get $get) {
                        if ($get('sucursal_id')  || $get('almacen_id') != null) {
                            return false;
                        } else {
                            return true;
                        }
                    })
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(Compra::query()->orderBy('created_at', 'desc'))
            ->columns([
                Tables\Columns\TextColumn::make('proveedor.nombre')
                    ->icon('heroicon-s-truck')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('descripcion')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('monto_usd')
                    ->money('USD')
                    ->searchable(),
                Tables\Columns\TextColumn::make('monto_bsd')
                    ->money('VES')
                    ->searchable(),
                Tables\Columns\TextColumn::make('forma_pago')
                    ->searchable(),
                Tables\Columns\TextColumn::make('fecha_compra')
                    ->searchable(),
                Tables\Columns\TextColumn::make('numero_factura_compra')
                    ->searchable(),
                Tables\Columns\TextColumn::make('responsable')
                    ->searchable(),
                Tables\Columns\TextColumn::make('sucursal.nombre')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
                            fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                        )
                        ->when(
                            $data['hasta'] ?? null,
                            fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
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
            SelectFilter::make('tienda')
            ->relationship('sucursal', 'nombre')
            ->attribute('sucursal_id')
            ])
            ->filtersTriggerAction(
                fn (Action $action) => $action
                    ->button()
                    ->label('Filtros'),
            )
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    ExportBulkAction::make()
                ]),
            ])->striped();
    }

    public static function updateTotales(Get $get, Set $set): void
    {
        $parametro_iva = ConfiguracionNomina::first()->iva;

        if ($get('feedback') == true) {
            $iva = $get('monto_bsd') * $parametro_iva;
            $set('iva', round($iva, 2));
            $set('total_compra_bsd',  round(($get('monto_bsd') + $iva), 2));
            $set('conversion_a_usd', round($get('total_compra_bsd') / $get('tasa_bcv'), 2));
        }

        if ($get('feedback') == false && $get('forma_pago') == 'dolares') {
            $set('conversion_a_usd', round($get('monto_usd'), 2));
        }

        if ($get('feedback') == false && $get('forma_pago') == 'bolivares') {
            $set('total_compra_bsd',  round($get('monto_bsd'), 2));
            $set('conversion_a_usd', round($get('monto_bsd') / $get('tasa_bcv'), 2));
        }
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
            'index' => Pages\ListCompras::route('/'),
            'create' => Pages\CreateCompra::route('/create'),
            'edit' => Pages\EditCompra::route('/{record}/edit'),
        ];
    }
}