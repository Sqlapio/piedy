<?php

namespace App\Livewire;

use App\Http\Controllers\AsignacionController;
use App\Http\Controllers\GiftCardController;
use App\Http\Controllers\CajaController;
use App\Models\Servicio;
use App\Models\Producto;
use App\Models\DetalleAsignacion;
use App\Models\Disponible;
use App\Models\VentaServicio;
use App\Models\MetodoPago;
use App\Models\TasaBcv;
use App\Models\MetodoPrepago;
use App\Models\InventarioSucursal;
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
use Filament\Tables\Actions\BulkAction;
use Illuminate\Database\Eloquent\Collection;

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
                Action::make('eliminar')
                    ->requiresConfirmation()
                    ->action(function (DetalleAsignacion $record) {

                        $serv_disponible = Disponible::where('cod_asignacion', $record->cod_asignacion)
                        ->where('cliente_id',  $record->cliente_id)
                        ->where('sucursal_id', Auth::user()->sucursal_id)
                        ->first();

                        if($record->tipo == 'servicio'){
                            $serv_disponible->acu_servicios = $serv_disponible->acu_servicios - $record->costo;
                            $serv_disponible->venta_total   = $serv_disponible->venta_total - $record->costo;
                            $serv_disponible->save();

                        }

                        if($record->tipo == 'producto'){
                            $serv_disponible->acu_productos = $serv_disponible->acu_productos - $record->costo;
                            $serv_disponible->venta_total   = $serv_disponible->venta_total - $record->costo;
                            $serv_disponible->save();
                        }

                        $record->delete();

                    })
                    ->icon('heroicon-c-trash')
                    ->color('danger')
                    //UI - Modal
                    ->modalIcon('heroicon-m-shopping-cart')
                    ->modalHeading('Eliminar Item')
                    ->modalDescription('Estas seguro que desea eliminar el item')
                    ->modalSubmitActionLabel('Si, eliminar item!')
                //
            ])
            ->bulkActions([
                BulkAction::make('delete')
                ->requiresConfirmation()
                ->action(fn (Collection $records) => $records->each->delete())
            ])
            ->headerActions([
                ActionGroup::make([
                    Action::make('Añadir Servicios')
                        ->label('Añadir Servicios')
                        ->icon('heroicon-c-document-plus')
                        ->color('success')
                        ->hidden(! (auth()->user()->rol_id == 1 || auth()->user()->rol_id == 2))
                        ->model(DetalleAsignacion::class)
                        ->form([
                            Section::make('Agregar Servicio')
                                ->description('Debe llenar los campos de forma correcta. Campos Requeridos(*)')
                                ->icon('heroicon-o-swatch')
                                ->schema([
                                    //Seleccion de servicio
                                    Select::make('servicio_id')
                                        ->label('Servicios')
                                        ->prefixIcon('heroicon-o-swatch')
                                        ->options(Servicio::where('sucursal_id', Auth::user()->sucursal_id)->pluck('descripcion', 'id'))
                                        ->searchable()
                                        ->required(),
                                ])
                        ])->action(function (array $data) {
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
                                
                            }elseif($servicio->status == 'cerrado' && Auth::user()->rol_id < 3)
                            {
                                //Servicio cerrado, el usuario no es gerente (Oculto)
                                return true;
                                
                            }else{
                                //Servicio cerrado, el usuario es gerente (Visible)
                                return false;
                            }

                        })
                        ->model(VentaServicio::class)
                        ->form([
                            Section::make('Facturación: '.$this->cod_asignacion)
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
                                                                    if($valor['status'] != 'error'){

                                                                        if($valor['valor'] > 0){
                                                                            $update_venta->venta_total = $valor['valor'];
                                                                            $update_venta->save();
                                                                        }

                                                                        if($valor['valor'] == 0){
                                                                            $res = GiftCardController::ejecutar_pago($state, $this->cod_asignacion, $this->cliente_id);
                                                                            if($res){
                                                                                return redirect()->route('cabinas');

                                                                            }else{
                                                                                Notification::make()
                                                                                ->title('NOTIFICACIÓN')
                                                                                ->icon('heroicon-o-shield-check')
                                                                                ->iconColor('danger')
                                                                                ->body('Falla interna del sistema, por favor comuniquese con el administrador')
                                                                                ->send();
                                                                            }
                                                                        }

                                                                    }else{
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
                                                ->helperText('Total en Dolares($): '.Disponible::where('cod_asignacion', $this->cod_asignacion)->where('sucursal_id', Auth::user()->sucursal_id)->first()->venta_total),

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
                        ])->action(function (array $data) {

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

                    Action::make('Añadir Productos')
                        ->label('Añadir Productos')
                        ->icon('heroicon-c-document-plus')
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
                                        ->required(),
                                    TextInput::make('cantidad')
                                        ->label('Cantidad')
                                        ->prefixIcon('heroicon-o-shopping-cart')
                                        ->numeric()
                                        ->required(),
                                ])->columns(2)
                        ])->action(function (array $data) {
                            AsignacionController::asigna_producto(
                                $data['producto_id'],
                                $data['cantidad'],
                                $this->cod_asignacion,
                                $this->cliente_id
                            );
                        }),

                    Action::make('cerrar')
                        ->label('Cerrar Servicio')
                        ->icon('heroicon-c-document-plus')
                        ->color('danger')
                        ->hidden(! (auth()->user()->rol_id == 1 || auth()->user()->rol_id == 2))
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
                        ])->action(function (array $data) {
                            $cierre = AsignacionController::cerrar_servicio(
                                $data['clave'],
                                $this->cod_asignacion,
                            );

                            if($cierre){
                                return redirect()->route('cabinas');
                            }else{
                                Notification::make()
                                ->title('NOTIFICACIÓN')
                                ->icon('heroicon-o-shield-check')
                                ->iconColor('danger')
                                ->body('Clave incorrecta, por favor valide su clave e intente nuevamente.')
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