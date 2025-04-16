<?php

namespace App\Livewire;

use Closure;
use App\Models\User;
use Filament\Tables;
use App\Models\TasaBcv;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Livewire\Component;
use App\Models\Producto;
use App\Models\Servicio;
use App\Models\Disponible;
use App\Models\MetodoPago;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use App\Models\ServicioUser;
use App\Models\MetodoPrepago;
use App\Models\VentaServicio;
use App\Models\DetalleAsignacion;
use App\Models\InventarioSucursal;
use Filament\Forms\Components\Grid;
use Filament\Tables\Actions\Action;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Forms\Contracts\HasForms;
use Filament\Support\Enums\ActionSize;
use App\Http\Controllers\LogController;
use Filament\Forms\Components\Fieldset;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Contracts\HasTable;
use App\Http\Controllers\CajaController;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\ActionGroup;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\GiftCardController;
use Filament\Tables\Columns\Summarizers\Sum;
use Illuminate\Database\Eloquent\Collection;
use App\Http\Controllers\AsignacionController;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;
use App\Http\Controllers\NotificacionesController;
use App\Http\Controllers\DescuentoAutomaticoController;
use Filament\Forms\Components\Actions\Action as HintAction;

class TableDetalleAsignacion extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public $cod_asignacion;
    public $cliente_id;
    public int $valor_giftCard = 0;

    public function mount($cod_asignacion, $cliente_id)
    {
        $this->cod_asignacion = $cod_asignacion;
        $this->cliente_id = $cliente_id;
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading('Asignaciones')
            ->query(DetalleAsignacion::query()
                ->where('cod_asignacion', $this->cod_asignacion))
            ->columns([
                Tables\Columns\TextColumn::make('tipo')
                    ->label('Tipo de Asiganción')
                    ->description(fn(DetalleAsignacion $record): string => ($record->servicio_id != null) ? Servicio::find($record->servicio_id)->descripcion : Producto::find($record->producto_id)->descripcion)
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'servicio' => 'success',
                        'producto' => 'warning',
                    })
                    ->icon(fn(string $state): string => match ($state) {
                        'servicio' => 'heroicon-o-swatch',
                        'producto' => 'heroicon-o-shopping-cart',
                    }),

                Tables\Columns\TextColumn::make('cantidad')
                    ->label('Cantidad')
                    ->badge()
                    ->color('success'),
                // ->icon('heroicon-o-clipboard-check'),

                Tables\Columns\TextColumn::make('costo')
                    ->label('Costo($)')
                    ->money('USD')
                    ->summarize(Sum::make()
                        ->money('USD')
                        ->label('Total pagar($)')),
                Tables\Columns\TextColumn::make('costo_bsd')
                    ->label('Costo(Bs.)')
                    ->money('VES')
                    ->summarize(Sum::make()
                        ->money('VES')
                        ->label('Total pagar(BS.)'))
            ])
            ->filters([
                //
            ])
            ->actions([

            ])
            ->bulkActions([
                BulkAction::make('Eliminar items')
                    ->requiresConfirmation()
                    ->color('danger')
                    ->icon('heroicon-c-trash')
                    ->action(fn(Collection $records) => $records->each->delete())
                    ->action(function (Collection $records) {

                        try {

                            foreach ($records as $record) {
                                $serv_disponible = Disponible::where('cod_asignacion', $record->cod_asignacion)
                                    ->where('cliente_id',  $record->cliente_id)
                                    ->where('sucursal_id', Auth::user()->sucursal_id)
                                    ->first();

                                if ($record->tipo == 'servicio') {
                                    $serv_disponible->acu_servicios = $serv_disponible->acu_servicios - $record->costo;
                                    $serv_disponible->venta_total   = $serv_disponible->venta_total - $record->costo;
                                    $serv_disponible->save();

                                    Notification::make()
                                        ->title('NOTIFICACIÓN')
                                        ->icon('heroicon-m-shield-check')
                                        ->iconColor('success')
                                        ->color('success')
                                        ->body('El servicio fue eliminado correctamente!')
                                        ->send();
                                }

                                if ($record->tipo == 'producto') {
                                    $serv_disponible->acu_productos = $serv_disponible->acu_productos - $record->costo;
                                    $serv_disponible->venta_total   = $serv_disponible->venta_total - $record->costo;
                                    $serv_disponible->save();

                                    Notification::make()
                                        ->title('NOTIFICACIÓN')
                                        ->icon('heroicon-m-shield-check')
                                        ->iconColor('success')
                                        ->color('success')
                                        ->body('El producto fue eliminado correctamente!')
                                        ->send();
                                }

                                $record->delete();
                            }
                        } catch (\Throwable $th) {
                            LogController::log(Auth::user()->id, 'excepcion-TableDetalleAsignacion(Eliminación de Item)', $th->getMessage(), $response = null);
                            Notification::make()
                                ->title('NOTIFICACIÓN')
                                ->icon('heroicon-m-shield-check')
                                ->iconColor('danger')
                                ->color('danger')
                                ->body($th->getMessage())
                                ->send();
                        }
                    })
            ])
            ->headerActions([
                ActionGroup::make([
                    Action::make('Añadir Servicios')
                        ->label('Añadir Servicios')
                        ->icon('heroicon-c-swatch')
                        ->color('success')
                        ->hidden(! (auth()->user()->rol_id == 1 || auth()->user()->rol_id == 2 || auth()->user()->rol_id == 5))
                        ->model(DetalleAsignacion::class)
                        ->form([
                            Section::make('Agregar Servicio')
                                ->description('Debe llenar los campos de forma correcta. Campos Requeridos(*)')
                                ->icon('heroicon-o-swatch')
                                ->schema([
                                        
                                    Select::make('servicio_id')
                                        ->label('Seleccione el Servício')
                                        ->options(function () {

                                            $disponible_user = Disponible::where('cod_asignacion', $this->cod_asignacion)
                                            ->first()
                                            ->empleado_id;
                                            
                                            $servicio_user = ServicioUser::where('user_id', $disponible_user)
                                            ->pluck('descripcion', 'servicio_id');

                                            return $servicio_user;
                                        })
                                        ->required()
                                        ->searchable(), 
                                ])
                        ])
                        ->action(function (array $data) {
                            AsignacionController::asigna_servicio_adicional(
                                $data['servicio_id'],
                                $this->cod_asignacion,
                                $this->cliente_id
                            );
                        }),


                    Action::make('Facturar')
                        ->label('Facturar')
                        ->icon('heroicon-c-cog-8-tooth')
                        ->color('success')
                        ->hidden(function () {

                            //Logica para ocultar la accion de facturar
                            $servicio = Disponible::where('cod_asignacion', $this->cod_asignacion)
                                ->where('sucursal_id', Auth::user()->sucursal_id)
                                ->first();

                            if ($servicio->status == 'activo') {
                                //Servicio activo (Oculto)
                                return true;
                            } elseif ($servicio->status == 'cerrado' && Auth::user()->rol_id < 3) {
                                //Servicio cerrado, el usuario no es gerente (Oculto)
                                return true;
                            } else {
                                //Servicio cerrado, el usuario es gerente (Visible)
                                return false;
                            }
                        })
                        ->model(VentaServicio::class)
                        ->form([
                            Section::make('Facturación: ' . $this->cod_asignacion)
                                ->description(function () {

                                    $servicios = DetalleAsignacion::where('cod_asignacion', $this->cod_asignacion)
                                        ->where('sucursal_id', Auth::user()->sucursal_id)
                                        ->where('tipo', 'servicio')
                                        ->where('status', 2)
                                        ->count();

                                    $productos = DetalleAsignacion::where('cod_asignacion', $this->cod_asignacion)
                                        ->where('sucursal_id', Auth::user()->sucursal_id)
                                        ->where('tipo', 'producto')
                                        ->where('status', 2)
                                        ->count();

                                    return 'SERVICIOS: ' . $servicios . ' - PRODUCTOS: ' . $productos;
                                })
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
                                                ->afterStateUpdated(function (Get $get, Set $set, ?string $old, ?string $state) {
                                                    $set('pago_bsd', $this->calculo($state));
                                                })
                                                ->required()
                                                ->helperText('Total en Dolares($): ' . Disponible::where('cod_asignacion', $this->cod_asignacion)->where('sucursal_id', Auth::user()->sucursal_id)->first()->venta_total),

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
                                                                ->mask('99999999')
                                                                ->label('Referencia Zelle($)')
                                                                ->prefixIcon('heroicon-s-hashtag')
                                                                ->visible(fn(Get $get): bool => $get('metodo_pago') == 3)
                                                                ->required(fn(Get $get): bool => ($get('metodo_pago') == 3) ? true : false),
                                                            TextInput::make('ref_pago_movil')
                                                                ->numeric()
                                                                // ->mask('99999999')
                                                                ->label('Referencia Pago Movil(Bs.)')
                                                                ->prefixIcon('heroicon-s-hashtag')
                                                                ->visible(fn(Get $get): bool => $get('metodo_pago_dos') == 5)
                                                                ->required(fn(Get $get): bool => ($get('metodo_pago_dos') == 5) ? true : false),
                                                            TextInput::make('ref_debito_credito')
                                                                // ->numeric()
                                                                // ->mask('99999999')
                                                                ->label('Referencia Debito/Credito')
                                                                ->prefixIcon('heroicon-s-hashtag')
                                                                ->visible(fn(Get $get): bool => $get('metodo_pago_dos') == 7)
                                                                ->required(fn(Get $get): bool => ($get('metodo_pago_dos') == 7) ? true : false),
                                                            TextInput::make('nro_tarjeta')
                                                                // ->numeric()
                                                                // ->mask('99999999')
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
                                                        // ->numeric()
                                                        // ->mask('99999999')
                                                        ->label('Referencia Debito/Credito')
                                                        ->prefixIcon('heroicon-s-hashtag')
                                                        ->visible(fn(Get $get): bool => $get('is_bsd'))
                                                        ->required(fn(Get $get): bool => $get('is_bsd')),

                                                    TextInput::make('pro_nro_tarjeta')
                                                        // ->numeric()
                                                        // ->mask('99999999')
                                                        ->label('Nro. Tarjeta Debito/Credito')
                                                        ->prefixIcon('heroicon-s-hashtag')
                                                        ->visible(fn(Get $get): bool => $get('is_bsd'))
                                                        ->required(fn(Get $get): bool => $get('is_bsd')),
                                                    // ->visible(fn(Get $get):bool => $get('usa_propinas')),
                                                ]),
                                        ])

                                ])
                        ])
                        ->action(function (array $data) {
                                // dd($data);
                            //Dolares
                            if ($data['metodo_pago'] != '' &&  $data['metodo_pago_dos'] == '') {
                                $dolares = CajaController::dolares(
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

                                if ($dolares) {
                                    Notification::make()
                                        ->title('Notificacion')
                                        ->icon('heroicon-o-shield-check')
                                        ->iconColor('success')
                                        ->color('success')
                                        ->body('Facturacion Exitosa. Codigo: ' . $this->cod_asignacion)
                                        ->send();

                                    LogController::log(Auth::user()->id, 'servicio facturado', 'facturacion de servicio en dolares: ' . $this->cod_asignacion, $response = null);

                                    ClienteController::add_visita($this->cliente_id);

                                    DescuentoAutomaticoController::descuento_automatico($this->cod_asignacion);

                                    //Envio una notificacion por whatsaap
                                    $notificacion = NotificacionesController::notificacion_servicio_facturado($this->cod_asignacion);

                                    if($notificacion['success'] == true){
                                        Notification::make()
                                        ->title('Notificacion')
                                        ->icon('heroicon-o-shield-check')
                                        ->color('success')
                                        ->iconColor('success')
                                        ->body('Notificacion al tecnico enviada por WhatsApp con exito. Codigo: ' . $this->cod_asignacion)
                                        ->send();
                                    }else{
                                        Notification::make()
                                        ->title('Notificacion')
                                        ->icon('heroicon-o-shield-check')
                                        ->iconColor('danger')
                                        ->color('danger')
                                        ->body($notificacion['message'])
                                        ->send();
                                    }

                                    $this->redirectRoute('cabinas');
                                } else {
                                    Notification::make()
                                        ->title('Notificacion')
                                        ->icon('heroicon-o-shield-check')
                                        ->iconColor('danger')
                                        ->body('Error al facturar, favor comuniquese con el administrador del Sistema')
                                        ->send();
                                }
                            }

                            //Pago en Bolivares metodos 2 - 4 - 5 - 7
                            if ($data['metodo_pago_dos'] != '' &&  $data['metodo_pago'] == '') {
                                $bolivares = CajaController::bolivares(
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

                                if ($bolivares) {
                                    Notification::make()
                                        ->title('Notificacion')
                                        ->icon('heroicon-o-shield-check')
                                        ->iconColor('success')
                                        ->color('success')
                                        ->body('Facturacion Exitosa. Codigo: ' . $this->cod_asignacion)
                                        ->send();

                                    LogController::log(Auth::user()->id, 'servicio facturado', 'facturacion de servicio en bolivares: ' . $this->cod_asignacion, $response = null);

                                    ClienteController::add_visita($this->cliente_id);

                                    //Envio una notificacion por whatsaap
                                    $notificacion = NotificacionesController::notificacion_servicio_facturado($this->cod_asignacion);
                                    if ($notificacion['success'] == true) {
                                        Notification::make()
                                            ->title('Notificacion')
                                            ->icon('heroicon-o-shield-check')
                                            ->color('success')
                                            ->iconColor('success')
                                            ->body('Notificacion al tecnico enviada por WhatsApp con exito. Codigo: ' . $this->cod_asignacion)
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

                                    $this->redirectRoute('cabinas');
                                } else {
                                    Notification::make()
                                        ->title('Notificacion')
                                        ->icon('heroicon-o-shield-check')
                                        ->iconColor('danger')
                                        ->body('Error al facturar, favor comuniquese con el administrador del Sistema')
                                        ->send();
                                }
                            }

                            //Pago en Bolivares metodos 2 - 4 - 5 - 7
                            if ($data['metodo_pago'] != '' && $data['metodo_pago_dos'] != '') {
                                $monto_bsd = Str::replace(',', '.', (Str::replace('.', '', $data['pago_bsd'])));

                                $montos_srv = CajaController::calculo_porcentajes_srv($this->cod_asignacion, $data['pago_usd'], $monto_bsd);
                                $montos_prod = CajaController::calculo_porcentajes_prod($this->cod_asignacion, $data['pago_usd'], $monto_bsd);
  
                                $multiple = CajaController::multiple(
                                    $montos_srv['valor_usd'],
                                    $montos_srv['valor_bsd'],
                                    $montos_prod['valor_usd'],
                                    $montos_prod['valor_bsd'],
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

                                if ($multiple) {
                                    Notification::make()
                                        ->title('Notificacion')
                                        ->icon('heroicon-o-shield-check')
                                        ->iconColor('success')
                                        ->color('success')
                                        ->body('Facturacion Exitosa. Codigo: ' . $this->cod_asignacion)
                                        ->send();

                                    LogController::log(Auth::user()->id, 'servicio facturado', 'facturacion de servicio en bolivares y dolares: ' . $this->cod_asignacion, $response = null);

                                    ClienteController::add_visita($this->cliente_id);

                                    //Envio una notificacion por whatsaap
                                    $notificacion = NotificacionesController::notificacion_servicio_facturado($this->cod_asignacion);
                                    if ($notificacion['success'] == true) {
                                        Notification::make()
                                            ->title('Notificacion')
                                            ->icon('heroicon-o-shield-check')
                                            ->color('success')
                                            ->iconColor('success')
                                            ->body('Notificacion al tecnico enviada por WhatsApp con exito. Codigo: ' . $this->cod_asignacion)
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


                                    $this->redirectRoute('cabinas');
                                } else {
                                    Notification::make()
                                        ->title('Notificacion')
                                        ->icon('heroicon-o-shield-check')
                                        ->iconColor('danger')
                                        ->body('Error al facturar, favor comuniquese con el administrador del Sistema')
                                        ->send();
                                }
                            }
                        }),

                    Action::make('Añadir Productos')
                        ->label('Añadir Productos')
                        ->icon('heroicon-c-shopping-bag')
                        ->color('success')
                        ->model(DetalleAsignacion::class)
                        ->form([
                            Section::make('Agregar Producto')
                                ->description('Debe llenar los campos de forma correcta. Campos Requeridos(*)')
                                ->icon('heroicon-o-shopping-cart')
                                ->schema([
                                    //Seleccion de servicio
                                    Select::make('producto_id')
                                        ->label('Productos')
                                        ->prefixIcon('heroicon-o-shopping-cart')
                                        ->options(InventarioSucursal::all()
                                            ->where('sucursal_id', Auth::user()->sucursal_id)
                                            ->where('cantidad', '>', 0)
                                            ->where('uso', 'venta')
                                            ->pluck('producto.descripcion', 'producto_id'))
                                        ->searchable()
                                        ->required()
                                        ->live(),
                                    TextInput::make('cantidad')
                                        ->label('Cantidad')
                                        ->prefixIcon('heroicon-o-shopping-cart')
                                        ->numeric()
                                        ->required()
                                        ->hint(function (Get $get) {
                                            $id = $get('producto_id');
                                            if (isset($id)) {
                                                $existencia = InventarioSucursal::where('producto_id', $get('producto_id'))
                                                    ->where('sucursal_id', Auth::user()->sucursal_id)
                                                    ->where('uso', 'venta')
                                                    ->first()
                                                    ->cantidad;
                                                return 'Existencia: ' . $existencia;
                                            } else {
                                                return 'Existencia: 0';
                                            }
                                        })
                                        ->hintIcon('heroicon-m-square-3-stack-3d')
                                        ->hintColor(function (Get $get) {
                                            $id = $get('producto_id');
                                            if (isset($id)) {

                                                $existencia = InventarioSucursal::where('producto_id', $get('producto_id'))
                                                    ->where('sucursal_id', Auth::user()->sucursal_id)
                                                    ->where('uso', 'venta')
                                                    ->first()
                                                    ->cantidad;

                                                if ($existencia < 6) {
                                                    return 'danger';
                                                } else {
                                                    return 'success';
                                                }
                                            } else {
                                                return 'primary';
                                            }
                                        })
                                        //Regla ara validar que la cantidad introducida por el usuario es menor a la existencia total
                                        ->rules([
                                            fn(Get $get): Closure => function (string $attribute, $value, Closure $fail) use ($get) {
                                                if ($value > InventarioSucursal::where('producto_id', $get('producto_id'))
                                                    ->where('sucursal_id', Auth::user()->sucursal_id)
                                                    ->where('uso', 'venta')
                                                    ->first()
                                                    ->cantidad
                                                ) {
                                                    $fail("La cabtidad es mayor a la existencia.");
                                                }
                                            },
                                        ])
                                ])->columns(2)
                        ])
                        ->action(function (array $data) {
                            AsignacionController::asigna_producto(
                                $data['producto_id'],
                                $data['cantidad'],
                                $this->cod_asignacion,
                                $this->cliente_id
                            );
                        }),

                    Action::make('cerrar')
                        ->label('Cerrar Servicio')
                        ->icon('heroicon-s-key')
                        ->color('danger')
                        ->hidden(! (auth()->user()->rol_id == 1 || auth()->user()->rol_id == 2 || auth()->user()->rol_id == 5))
                        ->model(DetalleAsignacion::class)
                        ->form([
                            Section::make('Contraseña')
                                ->description('Debe llenar los campos de forma correcta. Campos Requeridos(*)')
                                ->icon('heroicon-c-finger-print')
                                ->schema([
                                    //Seleccion de servicio
                                    TextInput::make('clave')
                                        ->label('Contraseña')
                                        ->prefixIcon('heroicon-c-finger-print')
                                        ->password()
                                        ->revealable()
                                        ->autofocus()
                                        ->required(),
                                ])
                        ])
                        ->action(function (array $data) {
                            $cierre = AsignacionController::cerrar_servicio(
                                $data['clave'],
                                $this->cod_asignacion,
                            );

                            if ($cierre) {
                                LogController::log(Auth::user()->id, 'servicio cerrado', 'ejecucion del cierre de servicio: ' . $this->cod_asignacion, $response = null);
                                return redirect()->route('cabinas');
                            } else {
                                Notification::make()
                                    ->title('NOTIFICACIÓN')
                                    ->icon('heroicon-o-shield-check')
                                    ->iconColor('danger')
                                    ->body('Clave incorrecta, por favor valide su clave e intente nuevamente.')
                                    ->send();
                            }
                        }),
                        
                    Action::make('editar')
                        ->label('Editar Técnico')
                        ->icon('heroicon-m-pencil-square')
                        ->color('danger')
                        ->hidden(! (auth()->user()->rol_id == 3 || auth()->user()->rol_id == 5))
                        ->model(DetalleAsignacion::class)
                        ->form([
                            Section::make('tecnico')
                                ->description('Debe llenar los campos de forma correcta. Campos Requeridos(*)')
                                ->icon('heroicon-c-finger-print')
                                ->schema([
                                    //Seleccion de empleado
                                    Select::make('user_id')
                                    ->label('Selección del Técnico')
                                    ->options(User::whereBetween('rol_id', [1, 2])->where('status', 1)->pluck('name', 'id'))
                                    ->required()
                                    ->live()
                                    ->searchable(),
                                ])
                        ])
                        ->action(function (array $data) {
                            $editar = AsignacionController::editar_tecnico(
                                $data['user_id'],
                                $this->cod_asignacion,
                            );

                            if ($editar) {
                                LogController::log(Auth::user()->id, 'tecnico editado', 'se edita el tecnico para el servicio: ' . $this->cod_asignacion, $response = null);
                                return redirect()->route('cabinas');
                            }
                        }),
                        
                    Action::make('eliminar')
                        ->label('Eliminar Asignación')
                        ->icon('heroicon-c-document-plus')
                        ->color('danger')
                        ->hidden(! ( auth()->user()->rol_id == 3 || auth()->user()->rol_id == 5))
                        ->action(function (array $data) {
                            
                            $servicios_asignados = DetalleAsignacion::where('cod_asignacion', $this->cod_asignacion)->count();
                            $status_de_asignacion = Disponible::where('cod_asignacion', $this->cod_asignacion)->first()->status;
                            
                            if ($servicios_asignados == 0 && $status_de_asignacion == 'activo') { 
                                 
                                Disponible::where('cod_asignacion', $this->cod_asignacion)->delete();
                                
                                //Notificacion para Admin
                                $recipient = User::where('rol_id', 5)->get();
                                foreach ($recipient as $user) {
                                    $recipient_for_user = User::find($user->id);
                                    Notification::make()
                                    ->title('NOTIFICACIÓN')
                                    ->icon('heroicon-c-shield-exclamation')
                                    ->iconColor('danger')
                                    ->body(Auth::user()->name . ', elimino la cabina: ' . $this->cod_asignacion)
                                    ->sendToDatabase($recipient_for_user);
                                }
                                
                                return redirect()->route('cabinas');
                                
                            }else{
                                Notification::make()
                                    ->title('Notificacion')
                                    ->icon('heroicon-o-shield-check')
                                    ->color('danger')
                                    ->iconColor('danger')
                                    ->body('No puede eliminar una cabina que tenga servicios activos o que se encuentre en estatus de "cerrada", debe eliminar todos los servicios asociados y luego eliminar la asigancion. Si la cabina ya fue cerrada debe comunicarse con el administrador.')
                                    ->send();
                            }
                        })
                ])
                ->label('Menú')
                ->icon('heroicon-c-adjustments-horizontal')
                ->size(ActionSize::Small)
                ->color('colorOne')
                ->button()
            ]);
    }

    public function calculo($monto_usd)
    {
        $tasa_bcv = TasaBcv::all()->first()->tasa;

        $total_usd = Disponible::where('cod_asignacion', $this->cod_asignacion)
            ->where('sucursal_id', Auth::user()->sucursal_id)
            ->where('status', 'cerrado')
            ->sum('venta_total');

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
        return view('livewire.table-detalle-asignacion');
    }
}