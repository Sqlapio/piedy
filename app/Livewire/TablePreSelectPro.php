<?php

namespace App\Livewire;

use App\Models\User;
use Filament\Tables;
use App\Models\Cliente;
use App\Models\TasaBcv;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Livewire\Component;
use App\Models\Disponible;
use App\Models\MetodoPago;
use Filament\Tables\Table;
use App\Models\CarProducto;
use Filament\Support\RawJs;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use App\Models\MetodoPrepago;
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
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use App\Http\Controllers\CajaController;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\ActionGroup;
use App\Http\Controllers\GiftCardController;
use Filament\Tables\Columns\Summarizers\Sum;
use Illuminate\Database\Eloquent\Collection;
use App\Http\Controllers\AsignacionController;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;
use App\Http\Controllers\VentaProductoController;
use Filament\Forms\Components\Actions\Action as HintAction;

class TablePreSelectPro extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    #[On('add-item-car')]
    public function updateItem()
    {
        $this->reset();
    }

    #[On('truncate-item-car')]
    public function truncateItems()
    {
        $this->reset();
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading('Pre-Orden')
            ->description('Producto agregados para la pre-compra')
            ->query(CarProducto::query()->where('sucursal_id', Auth::user()->sucursal_id)->where('status', 1))
            ->columns([

                TextColumn::make('precio_venta')
                    ->label('Precio')
                    ->icon('heroicon-m-currency-dollar')
                    ->color('primary')
                    ->money('USD')
                    ->sortable(),

                TextColumn::make('cantidad')
                    ->label('Cantidad')
                    ->icon('heroicon-c-shopping-bag')
                    ->color('primary')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('total_compra_usd')
                    ->label('USD($)')
                    ->icon('heroicon-m-currency-dollar')
                    ->color('success')
                    ->money('USD')
                        ->summarize(Sum::make()
                            ->money('USD')
                            ->label('Total($)')
                        )
                    ->sortable(),
                TextColumn::make('total_compra_bsd')
                    ->label('BSD(Bs.)')
                    ->color('info')
                    ->money('VES')
                        ->summarize(Sum::make()
                            ->money('VES')
                            ->label('Total(BS.)')
                        )
                    ->sortable()
            ])
            ->filters([
                //
            ])
            ->actions([
                Action::make('delete')
                ->requiresConfirmation()
                ->action(function (CarProducto $record) {
                    $record->delete();
                    LogController::log(Auth::user()->id, 'delete-item-car', "Se ha eliminado un item del carrito de compras", $response = null);
                })
                ->icon('heroicon-c-trash')
                ->color('danger')
                //UI - Modal
                ->modalIcon('heroicon-m-shopping-cart')
                ->modalHeading('Eliminar Item')
                ->modalDescription('Estas seguro que desea eliminar el item a pre-facturacion')
                ->modalSubmitActionLabel('Si, eliminar item!')
                //
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    //
                ])
            ])
            ->headerActions([
                Action::make('Facturar')
                        ->label('Facturar')
                        ->icon('heroicon-c-cog-8-tooth')
                        ->color('success')
                        ->hidden(function () {
                            
                            //Logica para ocultar la accion de facturar
                            $items = CarProducto::where('sucursal_id', Auth::user()->sucursal_id)->get();
                            // dd(count($items));
                            if (count($items) <= 0) {
                                return true;
                                
                            }else{
                                return false;
                            }

                        })
                        ->model(CarProducto::class)
                        ->form([
                            Section::make('Facturación: ')
                                ->description('Debe llenar los campos de forma correcta. Campos Requeridos(*)')
                                ->icon('heroicon-o-shopping-cart')
                                ->schema([
                                    Grid::make(2)
                                        ->schema([
                                            Select::make('cliente_id')
                                                ->label('Cliente')
                                                ->live(onBlur: true)
                                                ->prefixIcon('heroicon-c-credit-card')
                                                ->options(Cliente::all()->pluck('nombre', 'id'))
                                                ->searchable()
                                                ->required(),

                                            Select::make('user_id')
                                                ->label('Tecnico')
                                                ->live(onBlur: true)
                                                ->prefixIcon('heroicon-c-credit-card')
                                                ->options(
                                                    User::all()
                                                    ->where('status', 1)
                                                    ->whereBetween('rol_id', [1,2])
                                                    ->pluck('name', 'id'))
                                                ->searchable(),

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
                                                ->helperText('Total en Dolares($): '.CarProducto::where('sucursal_id', Auth::user()->sucursal_id)->sum('total_compra_usd')),

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
                                                                ->visible(fn(Get $get):bool => $get('metodo_pago') == 3)
                                                                ->required(fn(Get $get): bool => ($get('metodo_pago') == 3) ? true : false),
                                                            TextInput::make('ref_pago_movil')
                                                                ->numeric()
                                                                ->mask('99999999')
                                                                ->label('Referencia Pago Movil(Bs.)')
                                                                ->prefixIcon('heroicon-s-hashtag')
                                                                ->visible(fn(Get $get):bool => $get('metodo_pago_dos') == 5)
                                                                ->required(fn(Get $get): bool => ($get('metodo_pago_dos') == 5) ? true : false),
                                                            TextInput::make('ref_debito_credito')
                                                                ->numeric()
                                                                ->mask('99999999')
                                                                ->label('Referencia Debito/Credito')
                                                                ->prefixIcon('heroicon-s-hashtag')
                                                                ->visible(fn(Get $get):bool => $get('metodo_pago_dos') == 7)
                                                                ->required(fn(Get $get): bool => ($get('metodo_pago_dos') == 7) ? true : false),
                                                            TextInput::make('nro_tarjeta')
                                                                ->numeric()
                                                                ->mask('99999999')
                                                                ->label('Nro. Tarjeta Debito/Credito')
                                                                ->prefixIcon('heroicon-s-hashtag')
                                                                ->visible(fn(Get $get):bool => $get('metodo_pago_dos') == 7)
                                                                ->required(fn(Get $get): bool => ($get('metodo_pago_dos') == 7) ? true : false),
                                                        ])
                                                ])->visible(fn(Get $get):bool => $get('metodo_pago') == 3 || $get('metodo_pago_dos') == 4 || $get('metodo_pago_dos') == 5 || $get('metodo_pago_dos') == 7),


                                        ])

                                ])
                        ])->action(function (array $data) {

                            //Dolares
                            if($data['metodo_pago'] != '' &&  $data['metodo_pago_dos'] == '')
                            {
                                $dolares = VentaProductoController::facturarProducto_usd(
                                    $data['metodo_pago'], 
                                    $data['pago_usd'], 
                                    $data['cliente_id'], 
                                    (isset($data['user_id'])) ? $data['user_id'] : null,
                                    (isset($data['ref_zelle'])) ? $data['ref_zelle'] : 'N/A',
                                );

                                if($dolares){
                                    Notification::make()
                                    ->title('Notificacion')
                                    ->icon('heroicon-o-shield-check')
                                    ->iconColor('success')
                                    ->body('Facturacion Exitosa')
                                    ->send();

                                    LogController::log(Auth::user()->id, 'producto facturado','facturacion de producto vendido en dolares', $response = null);

                                    CarProducto::where('sucursal_id', Auth::user()->sucursal_id)->delete();

                                    $this->reset();

                                    $this->dispatch('truncate-item-car');

                                    //Evento para actualizar la tabla de productos
                                    $this->dispatch('update-table-productos');
                                    
                                    $this->redirectRoute('vender_producto');
                                    
                                }else{
                                    Notification::make()
                                    ->title('Notificacion')
                                    ->icon('heroicon-o-shield-check')
                                    ->iconColor('danger')
                                    ->body('Error al facturar, favor comuniquese con el administrador del Sistema')
                                    ->send();
                                    
                                }

                            }

                            //Pago en Bolivares metodos 2 - 4 - 5 - 7
                            if($data['metodo_pago_dos'] != '' &&  $data['metodo_pago'] == '')
                            {
                                $bolivares = VentaProductoController::facturarProducto_bsd(
                                    $data['metodo_pago_dos'],
                                    $data['pago_bsd'],
                                    $data['cliente_id'], 
                                    (isset($data['user_id'])) ? $data['user_id'] : null,
                                    (isset($data['ref_pago_movil'])) ? $data['ref_pago_movil'] : 'N/A',
                                    (isset($data['ref_debito_credito'])) ? $data['ref_debito_credito'] : 'N/A',
                                    (isset($data['nro_tarjeta'])) ? $data['nro_tarjeta'] : 'N/A',
                                );

                                if($bolivares){
                                    Notification::make()
                                    ->title('Notificacion')
                                    ->icon('heroicon-o-shield-check')
                                    ->iconColor('success')
                                    ->body('Facturacion Exitosa. Codigo: '.$this->cod_asignacion)
                                    ->send();

                                    LogController::log(Auth::user()->id, 'producto facturado','facturacion de producto vendido en bolivares', $response = null);

                                    CarProducto::where('sucursal_id', Auth::user()->sucursal_id)->delete();

                                    $this->reset();

                                    $this->dispatch('truncate-item-car');

                                    //Evento para actualizar la tabla de productos
                                    $this->dispatch('update-table-productos');
                                    
                                    $this->redirectRoute('vender_producto');
                                    
                                }else{
                                    Notification::make()
                                    ->title('Notificacion')
                                    ->icon('heroicon-o-shield-check')
                                    ->iconColor('danger')
                                    ->body('Error al facturar, favor comuniquese con el administrador del Sistema')
                                    ->send();
                                }

                            }

                            //Pago Mulriple
                            if($data['metodo_pago'] != '' && $data['metodo_pago_dos'] != '')
                            {
                                $monto_bsd = Str::replace(',', '.', (Str::replace('.', '', $data['pago_bsd'])));

                                $multiple = VentaProductoController::facturarProducto_multiple(
                                    $data['pago_usd'],
                                    $monto_bsd,
                                    $data['metodo_pago'],
                                    $data['metodo_pago_dos'],
                                    $data['cliente_id'], 
                                    (isset($data['user_id'])) ? $data['user_id'] : null,
                                    (isset($data['ref_zelle'])) ? $data['ref_zelle'] : null,
                                    (isset($data['ref_pago_movil'])) ? $data['ref_pago_movil'] : null,
                                    (isset($data['ref_debito_credito'])) ? $data['ref_debito_credito'] : null,
                                    (isset($data['nro_tarjeta'])) ? $data['nro_tarjeta'] : null,

                                );

                                if($multiple){
                                    Notification::make()
                                    ->title('Notificacion')
                                    ->icon('heroicon-o-shield-check')
                                    ->iconColor('success')
                                    ->body('Facturacion Exitosa')
                                    ->send();

                                    LogController::log(Auth::user()->id, 'producto facturado','facturacion de producto vendido en dolares y bolivares', $response = null);

                                    CarProducto::where('sucursal_id', Auth::user()->sucursal_id)->delete();

                                    $this->reset();

                                    $this->dispatch('truncate-item-car');

                                    //Evento para actualizar la tabla de productos
                                    $this->dispatch('update-table-productos');
                                    
                                    $this->redirectRoute('vender_producto');
                                    
                                }else{
                                    Notification::make()
                                    ->title('Notificacion')
                                    ->icon('heroicon-o-shield-check')
                                    ->iconColor('danger')
                                    ->body('Error al facturar, favor comuniquese con el administrador del Sistema')
                                    ->send();
                                }

                            }

                        }),
            ]);            // ])->striped();
    }

    public function calculo($monto_usd)
    {
        $tasa_bcv = TasaBcv::all()->first()->tasa;

        $total_usd = CarProducto::where('sucursal_id', Auth::user()->sucursal_id)->sum('total_compra_usd');

        $total_bsd = $total_usd * $tasa_bcv;

        if ($monto_usd > $total_usd) {
            return false;
        } else {
            $total_bsd = $total_bsd - ($monto_usd * $tasa_bcv);
            // return number_format($total_bsd, 2);
            return number_format(($total_bsd), 2, ",", ".");
        }
    }

    public function render(): View
    {
        return view('livewire.table-pre-select-pro');
    }
}