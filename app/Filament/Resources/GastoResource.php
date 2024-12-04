<?php

namespace App\Filament\Resources;

use Carbon\Carbon;
use Filament\Forms;
use Filament\Tables;
use App\Models\Gasto;
use App\Models\TasaBcv;
use Filament\Forms\Get;
use App\Models\Sucursal;
use Filament\Forms\Form;
use App\Models\MetodoPago;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\GastoResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use App\Filament\Resources\GastoResource\RelationManagers;

class GastoResource extends Resource
{
    protected static ?string $model = Gasto::class;

    protected static ?string $navigationIcon = 'heroicon-c-arrow-trending-down';

    protected static ?string $navigationGroup = 'Contabilidad';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('REGISTRO DE GASTOS')
                ->description('Formulario de gastos')
                ->icon('heroicon-m-arrow-trending-down')
                ->schema([

                    Forms\Components\TextInput::make('numero_factura')
                        ->label('Nro. de Referencia del Gasto')
                        ->prefixIcon('heroicon-c-tag')
                        ->maxLength(255)
                        ->default(function () {
                            $ultimo_correlativo = Gasto::where('numero_factura', 'like', '%Pcf-%')->latest()->first();
                            if(isset($ultimo_correlativo))
                            {
                                $parte_entera = intval(str_replace('Pcf-', '', $ultimo_correlativo->nro_referencia));
                                $sum_correlativo = $parte_entera + 1;

                            }else{
                                $sum_correlativo = 1;
                            }

                            $nro_referencia = 'Pcf-'.str_pad($sum_correlativo, 6, '0', STR_PAD_LEFT);
                            return $nro_referencia;
                        }),
                    
                    Forms\Components\TextInput::make('numero_factura_gasto')
                        ->label('Nro. Factura/Nota de Entrega')
                        ->prefixIcon('heroicon-c-tag')
                        ->rules(['required','numeric'])
                        ->validationMessages([
                            'required'  => 'Campo requerido',
                            'numeric'    => 'Solo admite números',
                        ]),

                    Forms\Components\DatePicker::make('fecha_factura')
                        ->label('Fecha de Factura del gasto')
                        ->prefixIcon('heroicon-m-calendar-days')
                        ->format('d-m-Y'),

                    Forms\Components\TextInput::make('descripcion')
                        ->label('Descripción del gasto')
                        ->prefixIcon('heroicon-s-pencil')
                        ->required()
                        ->maxLength(255),

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
                            if($get('forma_pago') == 'dolares'){
                                return MetodoPago::where('moneda', 'usd')->pluck('descripcion', 'id');
                            }

                            if($get('forma_pago') == 'bolivares'){
                                return MetodoPago::where('moneda', 'bsd')->pluck('descripcion', 'id');
                            }

                        })
                        ->live(),

                    Forms\Components\TextInput::make('monto_usd')
                        ->label('Monto en USD($)')
                        ->prefixIcon('heroicon-s-currency-dollar')
                        ->numeric()
                        ->hidden(function (Get $get) {
                            if($get('forma_pago') == 'dolares')
                            {
                                return false;
                            }else{
                                return true;
                            }
                        })
                        ->default(0.00),

                    Forms\Components\TextInput::make('monto_bsd')
                        ->label('Monto en BSD(Bs.)')
                        ->prefixIcon('heroicon-m-credit-card')
                        ->hidden(function (Get $get) {
                            if($get('forma_pago') == 'bolivares')
                            {
                                return false;
                            }else{
                                return true;
                            }
                        })
                        ->numeric()
                        ->default(0.00),
                        
                    Forms\Components\TextInput::make('fecha')
                        ->default(now()->format('d-m-Y'))
                        ->prefixIcon('heroicon-m-calendar-days')
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

                    Forms\Components\TextInput::make('tasa_bcv')
                        ->required()
                        ->default(TasaBcv::where('fecha', date('d-m-Y'))->first()->tasa),
                    
                    Forms\Components\Select::make('sucursal_id')
                        ->prefixIcon('heroicon-s-home')
                        ->label('Sucursal')
                        ->options(Sucursal::all()->pluck('nombre', 'id')),
                        
                    Forms\Components\TextInput::make('responsable')
                        ->required()
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
            ->query(Gasto::query()->orderBy('created_at', 'desc'))
            ->columns([
                Tables\Columns\TextColumn::make('numero_factura')
                ->label('Nro. Factura Piedy')
                ->searchable()
                ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('numero_factura_gasto')
                ->label('Nro. Factura Gasto')
                ->searchable()
                ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('descripcion')
                ->label('Descripcion del Gasto')
                ->icon('heroicon-m-document-check')
                ->searchable(),

                Tables\Columns\TextColumn::make('proveedor_id')
                ->label('Proveedor')
                ->icon('heroicon-m-document-check')
                ->searchable(),

                Tables\Columns\TextColumn::make('monto_usd')
                ->icon('heroicon-s-currency-dollar')
                ->money('USD')
                ->sortable(),

                Tables\Columns\TextColumn::make('monto_bsd')
                ->icon('heroicon-m-credit-card')
                ->money('VES')
                ->sortable(),

                Tables\Columns\TextColumn::make('forma_pago')
                ->label('Forma de Pago')
                ->searchable(),

                Tables\Columns\TextColumn::make('fecha')
                ->label('Regiastrado el:')
                ->icon('heroicon-m-calendar-days')
                ->searchable(),

                Tables\Columns\TextColumn::make('fecha_factura')
                ->label('Fecha Factura de Gasto')
                ->icon('heroicon-m-calendar-days')
                ->searchable(),

                Tables\Columns\TextColumn::make('sucursal.nombre')
                ->searchable(),

                Tables\Columns\TextColumn::make('responsable')
                ->searchable()
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
            'index' => Pages\ListGastos::route('/'),
            'create' => Pages\CreateGasto::route('/create'),
            'edit' => Pages\EditGasto::route('/{record}/edit'),
        ];
    }
}