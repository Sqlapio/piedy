<?php

namespace App\Livewire;

use App\Http\Controllers\AsignacionController;
use App\Http\Controllers\GiftCardController;
use App\Http\Controllers\UtilsController;
use App\Http\Controllers\CajaController;
use App\Models\Servicio;
use App\Models\Producto;
use App\Models\DetalleAsignacion;
use App\Models\FacturacionMultiple;
use App\Models\Disponible;
use App\Models\VentaServicio;
use App\Models\MetodoPago;
use App\Models\TasaBcv;
use App\Models\MetodoPrepago;
use App\Models\InventarioSucursal;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Actions\BulkAction;
use Illuminate\Database\Eloquent\Collection;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;
use Illuminate\Contracts\View\View;
use Filament\Forms\Components\Select;
use Filament\Tables\Actions\ActionGroup;
use Illuminate\Support\Facades\Auth;
use Filament\Support\Enums\ActionSize;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Forms\Components\Grid;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Fieldset;
use Illuminate\Support\Str;
use Filament\Notifications\Notification;
use Filament\Forms\Components\Actions\Action as HintAction;

class TableFacturacionMultiple extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public $valor;

    public function table(Table $table): Table
    {
        return $table
            ->heading('Servicios por Facturar')
            ->description('Tabla de servicios cerrados listo para facturar')
            ->query(Disponible::query()->where('sucursal_id', Auth::user()->sucursal_id)->where('status', 'cerrado'))
            ->columns([
                Tables\Columns\TextColumn::make('cod_asignacion')
                ->label('Codigo de Asigancion')
                ->icon('heroicon-o-hashtag')
                ->color('colorOne')
                ->searchable(),
                Tables\Columns\TextColumn::make('cliente.nombre')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('empleado.name')
                    ->numeric()
                    ->sortable(),
                // Tables\Columns\TextColumn::make('cod_prod_serv')
                //     ->searchable(),
                // Tables\Columns\TextColumn::make('servicio.descripcion')
                //     ->numeric()
                //     ->sortable(),
                // Tables\Columns\TextColumn::make('status')
                //     ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                // Tables\Columns\TextColumn::make('sucursal_id')
                //     ->searchable(),
                Tables\Columns\TextColumn::make('acu_servicios')
                    ->label('Total Servicios($)')
                    ->color('colorTree')
                    ->money('USD')
                    ->sortable(),
                Tables\Columns\TextColumn::make('acu_productos')
                    ->label('Total Productos($)')
                    ->color('colorOne')
                    ->money('USD')
                    ->sortable(),
                Tables\Columns\TextColumn::make('venta_total')
                ->label('Total a Pagar')
                ->icon('heroicon-c-currency-dollar')
                ->color('success')
                    ->money('USD')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                //
            ])
            ->bulkActions([
                BulkAction::make('facturar')
                ->label('Facturar')
                ->icon('heroicon-c-cog-8-tooth')
                ->color('success')
                ->form([
                    Section::make('Facturación')
                        ->description('Debe llenar los campos de forma correcta. Campos Requeridos(*)')
                        ->icon('heroicon-o-shopping-cart')
                        ->schema([
                            Grid::make(2)
                                ->schema([
                                    Fieldset::make('Metodo Prepagado')
                                        ->schema([
                                            Toggle::make('is_prepagado')
                                                ->label('Usa Metodo Prepagado?')
                                                ->live(onBlur: true)
                                                ->onColor('success')
                                                ->columnSpan('full')
                                                ->offColor('danger'),
                                            Select::make('metodo_pago_prepagado')
                                                ->label('Metodo de pago Prepagado')
                                                ->prefixIcon('heroicon-o-shopping-cart')
                                                ->options(MetodoPrepago::all()->where('sucursal_id', Auth::user()->sucursal_id)->pluck('descripcion', 'id'))
                                                ->searchable()
                                                ->visible(fn(Get $get):bool => $get('is_prepagado')),
                                            TextInput::make('cod_gift_men')
                                                ->numeric()
                                                ->prefixIcon('heroicon-m-currency-dollar')
                                                ->label('Codigo GiftCard/Membresia')
                                                ->live(onBlur: true)
                                                ->visible(fn(Get $get):bool => $get('is_prepagado'))
                                                // ->hintAction(
                                                //     HintAction::make('Aplicar')
                                                //         ->icon('heroicon-m-clipboard')
                                                //         ->requiresConfirmation()
                                                //         ->action(function (Set $set, $state) {

                                                //             $update_venta = Disponible::where('cod_asignacion', $this->cod_asignacion)
                                                //             ->where('cliente_id',  $this->cliente_id)
                                                //             ->where('sucursal_id', Auth::user()->sucursal_id)
                                                //             ->where('status', 'cerrado')
                                                //             ->first();

                                                //             $valor = GiftCardController::validaGiftCard($state, $this->cod_asignacion);
                                                //             if($valor['status'] != 'error'){

                                                //                 if($valor['valor'] > 0){
                                                //                     $update_venta->venta_total = $valor['valor'];
                                                //                     $update_venta->save();
                                                //                 }

                                                //                 if($valor['valor'] == 0){
                                                //                     $res = GiftCardController::ejecutar_pago($state, $this->cod_asignacion, $this->cliente_id);
                                                //                     if($res){
                                                //                         return redirect()->route('cabinas');

                                                //                     }else{
                                                //                         Notification::make()
                                                //                         ->title('NOTIFICACIÓN')
                                                //                         ->icon('heroicon-o-shield-check')
                                                //                         ->iconColor('danger')
                                                //                         ->body('Falla interna del sistema, por favor comuniquese con el administrador')
                                                //                         ->send();
                                                //                     }
                                                //                 }

                                                //             }else{
                                                //                 Notification::make()
                                                //                         ->title('NOTIFICACIÓN')
                                                //                         ->icon('heroicon-o-shield-check')
                                                //                         ->iconColor('danger')
                                                //                         ->body($valor['mensaje'])
                                                //                         ->send();
                                                //             }

                                                //             // $set('price', $state);
                                                //         })
                                                // )
                                        ]),

                                    Select::make('metodo_pago')
                                        ->label('Metodo Pago($)')
                                        ->live(onBlur: true)
                                        ->prefixIcon('heroicon-c-credit-card')
                                        ->options(MetodoPago::all()->where('moneda', 'usd')->pluck('descripcion', 'id'))
                                        ->searchable(),

                                    Select::make('metodo_pago_dos')
                                        ->label('Metodo Pago(Bs.)')
                                        ->live(onBlur: true)
                                        ->prefixIcon('heroicon-c-credit-card')
                                        ->options(MetodoPago::all()->where('moneda', 'bsd')->pluck('descripcion', 'id'))
                                        ->searchable(),

                                    TextInput::make('pago_usd')
                                        ->numeric()
                                        ->prefixIcon('heroicon-m-currency-dollar')
                                        ->label('Monto($)')
                                        ->live(onBlur: true)
                                        ->afterStateUpdated(function (Get $get, Set $set, ?string $old, ?string $state) {
                                            $set('pago_bsd', $this->calculo($state));
                                        })
                                        ->required()
                                        ->helperText('Total en Dolares($): '.$this->valor),

                                    TextInput::make('pago_bsd')
                                        ->label('Monto(Bs.)')
                                        ->prefixIcon('heroicon-m-building-library')
                                        ->readOnly(),


                                    Fieldset::make('Carga de Referencias')
                                        ->schema([
                                            Grid::make(3)
                                                ->schema([
                                                    TextInput::make('ref_zelle')
                                                        ->numeric()
                                                        ->label('Referencia Zelle($)')
                                                        ->prefixIcon('heroicon-s-hashtag')
                                                        ->visible(fn(Get $get):bool => $get('metodo_pago') == 3)
                                                        ->required(fn(Get $get): bool => ($get('metodo_pago') == 3) ? true : false),
                                                    TextInput::make('ref_pago_movil')
                                                        ->numeric()
                                                        ->label('Referencia Pago Movil(Bs.)')
                                                        ->prefixIcon('heroicon-s-hashtag')
                                                        ->visible(fn(Get $get):bool => $get('metodo_pago_dos') == 5)
                                                        ->required(fn(Get $get): bool => ($get('metodo_pago_dos') == 5) ? true : false),
                                                    TextInput::make('ref_debito_credito')
                                                        ->numeric()
                                                        ->label('Referencia Debito/Credito')
                                                        ->prefixIcon('heroicon-s-hashtag')
                                                        ->visible(fn(Get $get):bool => $get('metodo_pago_dos') == 7)
                                                        ->required(fn(Get $get): bool => ($get('metodo_pago_dos') == 7) ? true : false),
                                                    TextInput::make('nro_tarjeta')
                                                        ->numeric()
                                                        ->label('Nro. Tarjeta Debito/Credito')
                                                        ->prefixIcon('heroicon-s-hashtag')
                                                        ->visible(fn(Get $get):bool => $get('metodo_pago_dos') == 7)
                                                        ->required(fn(Get $get): bool => ($get('metodo_pago_dos') == 7) ? true : false),
                                                ])
                                        ])->visible(fn(Get $get):bool => $get('metodo_pago') == 3 || $get('metodo_pago_dos') == 4 || $get('metodo_pago_dos') == 5 || $get('metodo_pago_dos') == 7),

                                    Fieldset::make('Propinas')
                                        ->schema([
                                            Toggle::make('is_usd')
                                                ->label('Dolares($)')
                                                ->live(onBlur: true)
                                                ->onColor('success')
                                                ->offColor('danger'),

                                            Toggle::make('is_bsd')
                                                ->label('Bolivares(Bs.)')
                                                ->live(onBlur: true)
                                                ->onColor('success')
                                                ->offColor('danger'),

                                            TextInput::make('propina_usd')
                                                ->numeric()
                                                ->label('Monto en Dolares($)')
                                                ->prefixIcon('heroicon-o-shopping-cart')
                                                ->visible(fn(Get $get):bool => $get('is_usd')),

                                            TextInput::make('propina_bsd')
                                                ->label('Monto(Bs.)')
                                                ->prefixIcon('heroicon-o-shopping-cart')
                                                ->visible(fn(Get $get):bool => $get('is_bsd'))
                                                ->required(fn(Get $get):bool => $get('is_bsd')),

                                            TextInput::make('pro_ref_debito_credito')
                                                ->numeric()
                                                ->label('Referencia Debito/Credito')
                                                ->prefixIcon('heroicon-s-hashtag')
                                                ->visible(fn(Get $get):bool => $get('is_bsd'))
                                                ->required(fn(Get $get):bool => $get('is_bsd')),

                                            TextInput::make('pro_nro_tarjeta')
                                                ->numeric()
                                                ->label('Nro. Tarjeta Debito/Credito')
                                                ->prefixIcon('heroicon-s-hashtag')
                                                ->visible(fn(Get $get):bool => $get('is_bsd'))
                                                ->required(fn(Get $get):bool => $get('is_bsd')),
                                                // ->visible(fn(Get $get):bool => $get('usa_propinas')),
                                        ]),
                                ])

                        ])
                ])
                ->beforeFormFilled(function (Collection $records) {
                    $array_codigos = [];
                    $array_costos_usd = [];
                    $tasa_bcv = TasaBcv::first()->tasa;
                    for($i = 0; $i < count($records); $i++) {
                        array_push($array_codigos, $records[$i]->cod_asignacion);
                        array_push($array_costos_usd, $records[$i]->venta_total);
                    }

                    dd($array_codigos, $array_costos_usd);

                    $this->valor = FacturacionMultiple::where('sucursal_id', Auth::user()->sucursal_id)->sum('costo_usd');


                })
                ->action(function (array $data, Collection $records) {

                    //Dolares
                    if($data['metodo_pago'] != '' &&  $data['metodo_pago_dos'] == '')
                    {
                        CajaController::dolares(
                            $data['pago_usd'],
                            $data['metodo_pago'],
                            $this->cod_asignacion,
                            (isset($data['ref_zelle'])) ? $data['ref_zelle'] : null,
                            (isset($data['propina_usd'])) ? $data['propina_usd'] : 0.00,
                            (isset($data['propina_bsd'])) ? $data['propina_bsd'] : 0.00,
                            (isset($data['pro_ref_debito_credito'])) ? $data['pro_ref_debito_credito'] : null,
                            (isset($data['pro_nro_tarjeta'])) ? $data['pro_nro_tarjeta'] : null,
                            $metodoUsd = 'Usd'

                        );

                    }

                    //Pago en Bolivares metodos 2 - 4 - 5 - 7
                    if($data['metodo_pago_dos'] != '' &&  $data['metodo_pago'] == '')
                    {
                        CajaController::bolivares(
                            $data['metodo_pago_dos'],
                            $this->cod_asignacion,
                            (isset($data['ref_pago_movil'])) ? $data['ref_pago_movil'] : null,
                            (isset($data['ref_debito_credito'])) ? $data['ref_debito_credito'] : null,
                            (isset($data['nro_tarjeta'])) ? $data['nro_tarjeta'] : null,
                            (isset($data['propina_usd'])) ? $data['propina_usd'] : 0.00,
                            (isset($data['propina_bsd'])) ? $data['propina_bsd'] : 0.00,
                            (isset($data['pro_ref_debito_credito'])) ? $data['pro_ref_debito_credito'] : null,
                            (isset($data['pro_nro_tarjeta'])) ? $data['pro_nro_tarjeta'] : null,
                            $data['pago_bsd'],
                        );

                    }

                    //Pago en Bolivares metodos 2 - 4 - 5 - 7
                    if($data['metodo_pago'] != '' && $data['metodo_pago_dos'] != '')
                    {
                        $monto_bsd = Str::replace(',', '.', (Str::replace('.', '', $data['pago_bsd'])));

                        CajaController::multiple(
                            $data['pago_usd'],
                            $monto_bsd,
                            $this->cod_asignacion,
                            $data['metodo_pago'],
                            $data['metodo_pago_dos'],
                            (isset($data['ref_zelle'])) ? $data['ref_zelle'] : null,
                            (isset($data['ref_pago_movil'])) ? $data['ref_pago_movil'] : null,
                            (isset($data['ref_debito_credito'])) ? $data['ref_debito_credito'] : null,
                            (isset($data['nro_tarjeta'])) ? $data['nro_tarjeta'] : null,
                            (isset($data['propina_usd'])) ? $data['propina_usd'] : 0.00,
                            (isset($data['propina_bsd'])) ? $data['propina_bsd'] : 0.00,
                            (isset($data['pro_ref_debito_credito'])) ? $data['pro_ref_debito_credito'] : null,
                            (isset($data['pro_nro_tarjeta'])) ? $data['pro_nro_tarjeta'] : null,

                        );

                    }

                }),
            ]);
    }

    public function render(): View
    {
        return view('livewire.table-facturacion-multiple');
    }
}
