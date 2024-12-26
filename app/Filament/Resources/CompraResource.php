<?php

namespace App\Filament\Resources;

use Carbon\Carbon;
use App\Models\Iva;
use Filament\Forms;
use Filament\Tables;
use App\Models\Compra;
use App\Models\TasaBcv;
use Filament\Forms\Get;
use Filament\Forms\Set;
use App\Models\Sucursal;
use Filament\Forms\Form;
use App\Models\MetodoPago;
use Filament\Tables\Table;
use Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\CompraResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use App\Filament\Resources\CompraResource\RelationManagers;

class CompraResource extends Resource
{
    protected static ?string $model = Compra::class;

    protected static ?string $navigationIcon = 'heroicon-m-shopping-cart';

    protected static ?string $navigationGroup = 'Contabilidad';

    protected static ?int $navigationSort = 1;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('REGISTRO DE COMPRAS')
                ->description('Formulario para el registro de compras')
                ->icon('heroicon-s-receipt-percent')
                ->schema([
                    Forms\Components\TextInput::make('cod_compra')
                        ->prefixIcon('heroicon-c-tag')
                        ->required()
                        ->maxLength(255)
                        ->default(function () {
                            $ultimo_correlativo = Compra::where('cod_compra', 'like', '%Pcc-%')->latest()->first();

                            if(isset($ultimo_correlativo))
                            {
                                $parte_entera = intval(str_replace('Pcc-', '', $ultimo_correlativo->cod_compra));
                                $sum_correlativo = $parte_entera + 1;

                            }else{
                                $sum_correlativo = 1;
                            }

                            $numero_factura = 'Pcc-'.str_pad($sum_correlativo, 6, '0', STR_PAD_LEFT);
                            return $numero_factura;
                        }),

                    Forms\Components\TextInput::make('numero_factura_compra')
                        ->prefixIcon('heroicon-s-document-check')
                        ->label('Nro. Factura de Compra')
                        ->required()
                        ->maxLength(255),

                    Forms\Components\TextInput::make('descripcion')
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
                    ->prefixIcon('heroicon-s-truck')
                        ->label('Forma de Pago')
                        ->required()
                        ->options([
                            'dolares' => 'Dolares',
                            'bolivares' => 'Bolivares',
                        ])
                        ->live(),

                    Forms\Components\Select::make('metodo_pago')
                        ->prefixIcon('heroicon-s-truck')
                        ->label('Metodo de Pago')
                        ->required()
                        ->options(function (Get $get) {
                            if($get('forma_pago') == 'dolares'){
                                return MetodoPago::where('moneda', 'usd')->pluck('descripcion', 'id');
                            }

                            if($get('forma_pago') == 'bolivares'){
                                return MetodoPago::where('moneda', 'bsd')->pluck('descripcion', 'id');
                            }

                        })
                        ->live(),

                    Forms\Components\TextInput::make('monto_usd')
                        ->prefixIcon('heroicon-s-currency-dollar')
                        ->label('Monto en USD($)')
                        ->hint('Ejemplo: 1245.90($)')
                        ->numeric()
                        ->hidden(function (Get $get) {
                            if($get('forma_pago') == 'dolares')
                            {
                                return false;
                            }else{
                                return true;
                            }
                        }),

                    Forms\Components\TextInput::make('monto_bsd')
                        ->prefixIcon('heroicon-m-credit-card')
                        ->label('Monto en BSD(Bs.)')
                        ->hint('Ejemplo: 120.78(Bs.)')
                        ->numeric()
                        ->hidden(function (Get $get) {
                            if($get('forma_pago') == 'bolivares')
                            {
                                return false;
                            }else{
                                return true;
                            }
                        })
                        ->live(),

                    Forms\Components\DatePicker::make('fecha_compra')
                        ->prefixIcon('heroicon-m-calendar-days')
                        ->label('Fecha de Compra')
                        ->format('d-m-Y'),
                    
                    Forms\Components\TextInput::make('tasa_bcv')
                        ->required()
                        ->default(TasaBcv::where('fecha', date('d-m-Y'))->first()->tasa),
                        
                    Forms\Components\Select::make('sucursal_id')
                            ->prefixIcon('heroicon-s-home')
                            ->label('Sucursal')
                            ->options(Sucursal::all()->pluck('nombre', 'id')),

                    Forms\Components\TextInput::make('responsable')
                        ->prefixIcon('heroicon-c-user-circle')
                        ->default(auth()->user()->name),

                    
                    Forms\Components\Section::make()
                        ->schema([
                        Forms\Components\Textarea::make('observacion')
                            ->label('Observaciones Relevante'),
                        ])
                ])
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