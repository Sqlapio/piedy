<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CompraResource\Pages;
use App\Filament\Resources\CompraResource\RelationManagers;
use App\Models\Compra;
use App\Models\MetodoPago;
use App\Models\Iva;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CompraResource extends Resource
{
    protected static ?string $model = Compra::class;

    protected static ?string $navigationIcon = 'heroicon-m-shopping-cart';

    protected static ?string $navigationGroup = 'Contabilidad';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('cod_compra')
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
                    ->label('Nro. Factura de Compra')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('descripcion')
                    ->required()
                    ->maxLength(255),

                Forms\Components\Select::make('proveedor_id')
                    ->relationship('proveedores', 'nombre')
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
                ->required()
                ->options([
                    'dolares' => 'Dolares',
                    'bolivares' => 'Bolivares',
                ])
                ->live(),

                Forms\Components\Select::make('metodo_pago')
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

                // Forms\Components\Select::make('iva_id')
                //     ->label('Maneja IVA?')
                //     ->relationship('iva', 'iva')
                //     ->searchable()
                //     ->preload()
                //     ->createOptionForm([
                //         Forms\Components\TextInput::make('iva')
                //             ->label('IVA(%)')
                //             ->numeric()
                //             ->required(),
                //     ])
                //     ->required()
                //     ->hidden(function (Get $get) {
                //         if ($get('forma_pago') == 'bolivares') {
                //             return false;
                //         } else {
                //             return true;
                //         }
                //     })
                //     ->live()
                //     ->afterStateUpdated(function (Get $get, Set $set, ?string $state) {
                //         $porcen = Iva::where('id', $get('iva_id'))->first()->iva;
                //         $iva = ($get('monto_bsd') * $porcen) / 100;
                //         $neto = ($get('monto_bsd')) + $iva;
                //         $set('monto_con_iva', $neto);
                //     }),

                // Forms\Components\TextInput::make('monto_con_iva')
                //     ->label('Neto (Bs.)')
                //     ->hint(function (Get $get) {
                //         $iva = $get('monto_con_iva') - $get('monto_bsd');
                //         return 'Monto IVA: '. $iva .' Bs.';
                //     })
                //     ->hintIcon('heroicon-m-question-mark-circle')
                //     ->visible(function (Get $get) {
                //         if($get('forma_pago') == 'bolivares')
                //         {
                //             return true;
                //         }else{
                //             return false;
                //         }
                //     })
                //     ->live(),

                Forms\Components\DatePicker::make('fecha_compra')
                    ->label('Fecha de Compra')
                    ->format('d-m-Y'),
                
                Forms\Components\Textarea::make('observacion')
                ->default(auth()->user()->name),

                Forms\Components\TextInput::make('responsable')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('cod_compra')
                    ->searchable(),
                Tables\Columns\TextColumn::make('proveedor_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('descripcion')
                    ->searchable(),
                Tables\Columns\TextColumn::make('monto_usd')
                    ->searchable(),
                Tables\Columns\TextColumn::make('monto_bsd')
                    ->searchable(),
                Tables\Columns\TextColumn::make('iva.iva')
                    ->searchable(),
                Tables\Columns\TextColumn::make('monto_con_iva')
                    ->searchable(),
                Tables\Columns\TextColumn::make('forma_pago')
                    ->searchable(),
                Tables\Columns\TextColumn::make('fecha_compra')
                    ->searchable(),
                Tables\Columns\TextColumn::make('numero_factura_compra')
                    ->searchable(),
                Tables\Columns\TextColumn::make('responsable')
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
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListCompras::route('/'),
            'create' => Pages\CreateCompra::route('/create'),
            'edit' => Pages\EditCompra::route('/{record}/edit'),
        ];
    }
}