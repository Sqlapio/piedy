<?php

namespace App\Livewire;

use Filament\Tables;
use App\Models\TasaBcv;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Livewire\Component;
use App\Models\Disponible;
use App\Models\MetodoPago;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Illuminate\Http\Request;
use App\Models\MetodoPrepago;
use App\Models\VentaServicio;
use App\Models\FacturacionMultiple;
use Filament\Forms\Components\Grid;
use Filament\Tables\Actions\Action;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Forms\Contracts\HasForms;
use App\Http\Controllers\LogController;
use Filament\Forms\Components\Fieldset;
use Filament\Tables\Contracts\HasTable;
use App\Http\Controllers\CajaController;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use App\Http\Controllers\GiftCardController;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;
use App\Http\Controllers\NotificacionesController;
use Filament\Forms\Components\Actions\Action as HintAction;


class TableTotalizar extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public $valor;

    public function mount()
    {
        if (isset(FacturacionMultiple::where('sucursal_id', Auth::user()->sucursal_id)->first()->venta_total_usd)) {
            $this->valor = FacturacionMultiple::where('sucursal_id', Auth::user()->sucursal_id)->first()->venta_total_usd;
        } else {
            $this->valor = 0;
        }
    }

    #[On('update-item')]
    public function updateItem()
    {
        $this->reset();
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading('TOTALIZACION MULTI-SERVICIOS')
            ->description('Sumatoria de Servicios')
            ->query(FacturacionMultiple::query())
            ->columns([
                Tables\Columns\TextColumn::make('cod_fac_multiple')
                    ->label('Codigo de Facturacion')
                    ->icon('heroicon-o-hashtag')
                    ->color('colorOne')
                    ->searchable(),
                Tables\Columns\TextColumn::make('venta_total_usd')
                    ->label('Venta Total USD')
                    ->color('success')
                    ->money('USD')
                    ->sortable(),
                Tables\Columns\TextColumn::make('venta_total_bsd')
                    ->label('Venta Total BSD')
                    ->color('colorOne')
                    ->money('VES')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Action::make('Facturar')
                    ->label('Facturar')
                    ->icon('heroicon-c-cog-8-tooth')
                    ->color('success')
                    // ->hidden((Auth::user()->rol_id < 3))
                    ->model(VentaServicio::class)
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
                                                    ->visible(fn(Get $get): bool => $get('is_prepagado')),
                                                TextInput::make('cod_gift_men')
                                                    ->numeric()
                                                    ->prefixIcon('heroicon-m-currency-dollar')
                                                    ->label('Codigo GiftCard/Membresia')
                                                    ->live(onBlur: true)
                                                    ->visible(fn(Get $get): bool => $get('is_prepagado'))
                                                    ->hintAction(
                                                        HintAction::make('Aplicar')
                                                            ->icon('heroicon-m-clipboard')
                                                            ->requiresConfirmation()
                                                            ->action(function (Set $set, $state) {

                                                                $update_venta = Disponible::where('cod_asignacion', $this->cod_asignacion)
                                                                    ->where('cliente_id',  $this->cliente_id)
                                                                    ->where('sucursal_id', Auth::user()->sucursal_id)
                                                                    ->where('status', 'cerrado')
                                                                    ->first();

                                                                $valor = GiftCardController::validaGiftCard($state, $this->cod_asignacion);
                                                                if ($valor['status'] != 'error') {

                                                                    if ($valor['valor'] > 0) {
                                                                        $update_venta->venta_total = $valor['valor'];
                                                                        $update_venta->save();
                                                                    }

                                                                    if ($valor['valor'] == 0) {
                                                                        $res = GiftCardController::ejecutar_pago($state, $this->cod_asignacion, $this->cliente_id);
                                                                        if ($res) {
                                                                            return redirect()->route('cabinas');
                                                                        } else {
                                                                            Notification::make()
                                                                                ->title('NOTIFICACIÓN')
                                                                                ->icon('heroicon-o-shield-check')
                                                                                ->iconColor('danger')
                                                                                ->body('Falla interna del sistema, por favor comuniquese con el administrador')
                                                                                ->send();
                                                                        }
                                                                    }
                                                                } else {
                                                                    Notification::make()
                                                                        ->title('NOTIFICACIÓN')
                                                                        ->icon('heroicon-o-shield-check')
                                                                        ->iconColor('danger')
                                                                        ->body($valor['mensaje'])
                                                                        ->send();
                                                                }

                                                                // $set('price', $state);
                                                            })
                                                    )
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
                                            ->afterStateUpdated(function (Get $get, Set $set, ?string $old, ?string $state, Request $request) {
                                                $codigo = $request->session()->get('cod_asignacion_fm');
                                                $set('pago_bsd', $this->calculo($codigo, $state));
                                            })
                                            ->required()
                                            ->helperText('Total en Dolares($): ' . $this->valor),

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
                                                            ->visible(fn(Get $get): bool => $get('metodo_pago') == 3)
                                                            ->required(fn(Get $get): bool => ($get('metodo_pago') == 3) ? true : false),
                                                        TextInput::make('ref_pago_movil')
                                                            ->numeric()
                                                            ->label('Referencia Pago Movil(Bs.)')
                                                            ->prefixIcon('heroicon-s-hashtag')
                                                            ->visible(fn(Get $get): bool => $get('metodo_pago_dos') == 5)
                                                            ->required(fn(Get $get): bool => ($get('metodo_pago_dos') == 5) ? true : false),
                                                        TextInput::make('ref_debito_credito')
                                                            ->numeric()
                                                            ->label('Referencia Debito/Credito')
                                                            ->prefixIcon('heroicon-s-hashtag')
                                                            ->visible(fn(Get $get): bool => $get('metodo_pago_dos') == 7)
                                                            ->required(fn(Get $get): bool => ($get('metodo_pago_dos') == 7) ? true : false),
                                                        TextInput::make('nro_tarjeta')
                                                            ->numeric()
                                                            ->label('Nro. Tarjeta Debito/Credito')
                                                            ->prefixIcon('heroicon-s-hashtag')
                                                            ->visible(fn(Get $get): bool => $get('metodo_pago_dos') == 7)
                                                            ->required(fn(Get $get): bool => ($get('metodo_pago_dos') == 7) ? true : false),
                                                    ])
                                            ])->visible(fn(Get $get): bool => $get('metodo_pago') == 3 || $get('metodo_pago_dos') == 4 || $get('metodo_pago_dos') == 5 || $get('metodo_pago_dos') == 7),

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
                                                    ->visible(fn(Get $get): bool => $get('is_usd')),

                                                TextInput::make('propina_bsd')
                                                    ->label('Monto(Bs.)')
                                                    ->prefixIcon('heroicon-o-shopping-cart')
                                                    ->visible(fn(Get $get): bool => $get('is_bsd'))
                                                    ->required(fn(Get $get): bool => $get('is_bsd')),

                                                TextInput::make('pro_ref_debito_credito')
                                                    ->numeric()
                                                    ->label('Referencia Debito/Credito')
                                                    ->prefixIcon('heroicon-s-hashtag')
                                                    ->visible(fn(Get $get): bool => $get('is_bsd'))
                                                    ->required(fn(Get $get): bool => $get('is_bsd')),

                                                TextInput::make('pro_nro_tarjeta')
                                                    ->numeric()
                                                    ->label('Nro. Tarjeta Debito/Credito')
                                                    ->prefixIcon('heroicon-s-hashtag')
                                                    ->visible(fn(Get $get): bool => $get('is_bsd'))
                                                    ->required(fn(Get $get): bool => $get('is_bsd')),
                                                // ->visible(fn(Get $get):bool => $get('usa_propinas')),
                                            ]),
                                    ])

                            ])
                    ])->action(function (array $data, FacturacionMultiple $record) {
                        $data_fm = json_decode($record->cod_asignacion);
                        // dd($data_fm);
                        // dd($data_fm);
                        for ($i = 0; $i < count($data_fm); $i++) {
                            //Dolares
                            if ($data['metodo_pago'] != '' &&  $data['metodo_pago_dos'] == '') {
            
                                $fm_dolares = CajaController::dolares(
                                    $data['pago_usd'],
                                    $data['metodo_pago'],
                                    $data_fm[$i],
                                    (isset($data['ref_zelle'])) ? $data['ref_zelle'] : null,
                                    (isset($data['propina_usd'])) ? $data['propina_usd'] : 0.00,
                                    (isset($data['propina_bsd'])) ? $data['propina_bsd'] : 0.00,
                                    (isset($data['pro_ref_debito_credito'])) ? $data['pro_ref_debito_credito'] : null,
                                    (isset($data['pro_nro_tarjeta'])) ? $data['pro_nro_tarjeta'] : null,
                                    $metodoUsd = 'Usd'
                                );

                                if ($fm_dolares) {
                                    // FacturacionMultiple::truncate();
                                    LogController::log(Auth::user()->id, 'facturacion multiple','realizo la ejecucion de una factura multiple en dolares', $response = null);
                                    Notification::make()
                                        ->title('Notificacion:')
                                        ->icon('heroicon-o-shield-check')
                                        ->iconColor('success')
                                        ->body('La facturacion ' . $data_fm[$i] . ' fue realizada con exito.')
                                        ->send();
                                        
                                    // //Envio una notificacion por whatsaap
                                    $notificacion = NotificacionesController::notificacion_servicio_facturado($data_fm[$i]);
                                    if ($notificacion['success'] == true) {
                                        Notification::make()
                                            ->title('Notificacion')
                                            ->icon('heroicon-o-shield-check')
                                            ->color('success')
                                            ->iconColor('success')
                                            ->body('Notificacion al tecnico enviada por WhatsApp con exito. Codigo: ' . $data_fm[$i])
                                            ->send();
                                    } else {
                                        Notification::make()
                                            ->title('Notificacion')
                                            ->icon('heroicon-o-shield-check')
                                            ->iconColor('danger')
                                            ->color('danger')
                                            ->body($notificacion['message'])
                                            ->send();
                                    }
                                    
                                    // $this->redirectRoute('cabinas');
                                }
                            }

                            //Bolivares
                            if ($data['metodo_pago_dos'] != '' &&  $data['metodo_pago'] == '') {
                                $fm_bolivares = CajaController::bolivares(
                                    $data['metodo_pago_dos'],
                                    $data_fm[$i],
                                    (isset($data['ref_pago_movil'])) ? $data['ref_pago_movil'] : null,
                                    (isset($data['ref_debito_credito'])) ? $data['ref_debito_credito'] : null,
                                    (isset($data['nro_tarjeta'])) ? $data['nro_tarjeta'] : null,
                                    (isset($data['propina_usd'])) ? $data['propina_usd'] : 0.00,
                                    (isset($data['propina_bsd'])) ? $data['propina_bsd'] : 0.00,
                                    (isset($data['pro_ref_debito_credito'])) ? $data['pro_ref_debito_credito'] : null,
                                    (isset($data['pro_nro_tarjeta'])) ? $data['pro_nro_tarjeta'] : null,
                                    $data['pago_bsd'],
                                );

                                if ($fm_bolivares) {
                                    // FacturacionMultiple::truncate();
                                    LogController::log(Auth::user()->id, 'facturacion multiple','realizo la ejecucion de una factura multiple en bolivares', $response = null);
                                    Notification::make()
                                        ->title('Notificacion:')
                                        ->icon('heroicon-o-shield-check')
                                        ->iconColor('success')
                                        ->body('La facturacion ' . $data_fm[$i] . ' fue realizada con exito.')
                                        ->send();

                                    // //Envio una notificacion por whatsaap
                                    $notificacion = NotificacionesController::notificacion_servicio_facturado($data_fm[$i]);
                                    if ($notificacion['success'] == true) {
                                        Notification::make()
                                            ->title('Notificacion')
                                            ->icon('heroicon-o-shield-check')
                                            ->color('success')
                                            ->iconColor('success')
                                            ->body('Notificacion al tecnico enviada por WhatsApp con exito. Codigo: ' . $data_fm[$i])
                                            ->send();
                                    } else {
                                        Notification::make()
                                            ->title('Notificacion')
                                            ->icon('heroicon-o-shield-check')
                                            ->iconColor('danger')
                                            ->color('danger')
                                            ->body($notificacion['message'])
                                            ->send();
                                    }

                                    // $this->redirectRoute('cabinas');
                                }
                            }

                            //Dolasres y Bolivares
                            if ($data['metodo_pago'] != '' && $data['metodo_pago_dos'] != '') {
                                $monto_bsd = Str::replace(',', '.', (Str::replace('.', '', $data['pago_bsd'])));
                            
                                    $montos_srv = CajaController::calculo_porcentajes_srv_fm($data_fm[$i], $data['pago_usd'], $monto_bsd);
                                    // dd($montos_srv);
                                    $montos_prod = CajaController::calculo_porcentajes_prod($data_fm[$i], $data['pago_usd'], $monto_bsd);
                                    // dd($montos_srv, $montos_prod);
                                    // dd($montos_srv, $montos_prod);
                                    $fm_multiMoneda = CajaController::multiple(
                                        $montos_srv['valor_usd'],
                                        $montos_srv['valor_bsd'],
                                        $montos_prod['valor_usd'],
                                        $montos_prod['valor_bsd'],
                                        $data_fm[$i],
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

                                    if ($fm_multiMoneda) {

                                        LogController::log(Auth::user()->id, 'facturacion multiple', 'realizo la ejecucion de una factura multiple en dolares y bolivares', $response = null);
                                        Notification::make()
                                            ->title('Notificacion:')
                                            ->icon('heroicon-o-shield-check')
                                            ->iconColor('success')
                                            ->body('La facturacion ' . $data_fm[$i] . ' fue realizada con exito.')
                                            ->send();

                                        //Envio una notificacion por whatsaap
                                        $notificacion = NotificacionesController::notificacion_servicio_facturado($data_fm[$i]);
                                        if ($notificacion['success'] == true) {
                                            Notification::make()
                                                ->title('Notificacion')
                                                ->icon('heroicon-o-shield-check')
                                                ->color('success')
                                                ->iconColor('success')
                                                ->body('Notificacion al tecnico enviada por WhatsApp con exito. Codigo: ' . $data_fm[$i])
                                                ->send();
                                        } else {
                                            Notification::make()
                                                ->title('Notificacion')
                                                ->icon('heroicon-o-shield-check')
                                                ->iconColor('danger')
                                                ->color('danger')
                                                ->body($notificacion['message'])
                                                ->send();
                                        }

                                        // $this->redirectRoute('cabinas');
                                    }
                                
                            }
                        }
                        
                        FacturacionMultiple::truncate();
                        $this->redirectRoute('cabinas');
                        
                    }),


                Action::make('delete')
                    ->before(function (FacturacionMultiple $record) {
                        //Realizamos un rollback de la informacion cargada
                        $data = json_decode($record->cod_asignacion);
                        for ($i = 0; $i < count($data); $i++) {
                            $record = Disponible::where('cod_asignacion', $data[$i])
                                ->where('status_fac_multiple', 2)
                                ->where('sucursal_id', Auth::user()->sucursal_id)
                                ->update(['status_fac_multiple' => 1]);
                        }
                    })
                    ->requiresConfirmation()
                    ->action(function (FacturacionMultiple $record) {
                        $record->delete();
                        $this->dispatch('delete-item');
                    })
                    ->icon('heroicon-c-trash')
                    ->color('danger')
                    //UI - Modal
                    ->modalIcon('heroicon-m-shopping-cart')
                    ->modalHeading('Eliminar Item')
                    ->modalDescription('Estas seguro que desea eliminar el item')
                    ->modalSubmitActionLabel('Si, eliminar item!')
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    //
                ]),
            ]);
    }

    public function calculo($codigo, $monto_usd)
    {
        $tasa_bcv = TasaBcv::all()->first()->tasa;

        //Total de la venta en dolares
        $total_usd = FacturacionMultiple::where('cod_fac_multiple', $codigo)
            ->where('sucursal_id', Auth::user()->sucursal_id)
            ->first()->venta_total_usd;

        $total_bsd = $total_usd * $tasa_bcv;

        if ($monto_usd > $total_usd) {
            return false;
        } else {
            $total_bsd = $total_bsd - ($monto_usd * $tasa_bcv);
            // return number_format($total_bsd, 2);
            return number_format(($total_bsd), 2, ",", ".");
        }
    }

    public function validaGiftMem($codigo, $metodo_pago_prepagado)
    {
        $valor = GiftCardController::validaGiftCard($codigo, $this->cod_asignacion);
        return $valor;
    }

    public function render(): View
    {
        return view('livewire.table-totalizar');
    }
}