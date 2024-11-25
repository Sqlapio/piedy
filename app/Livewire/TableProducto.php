<?php

namespace App\Livewire;

use App\Models\CarProducto;
use App\Models\InventarioSucursal;
use App\Models\TasaBcv;
use App\Models\DetalleAsignacion;
use App\Models\Disponible;
use Carbon\Carbon;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;
use Illuminate\Contracts\View\View;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\ToggleColumn;
use App\Http\Controllers\LogController;

class TableProducto extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public function table(Table $table): Table
    {

        //Busco la sucursal del usuario
        $sucursal_id = Auth::user()->sucursal_id;

        return $table
            ->heading('Inventario de Productos')
            ->description('Inventarios de productos para la venta')
            ->query(InventarioSucursal::query()
            ->where('cantidad', '>', 0)
            ->where('uso', 'venta')
            ->where('accepted_at','!=',null)
            ->where('sucursal_id', $sucursal_id))
            ->columns([
                ImageColumn::make('producto.image')
                    ->square()
                    ->size(100)
                    ->searchable(),
                TextColumn::make('producto.descripcion')
                    ->label('Descripción')
                    ->icon('heroicon-c-clipboard-document-check')
                    ->color(function (InventarioSucursal $record) { 
                        if($record->accepted_at !== null){
                            return 'info';
                        }else{
                            return 'colorDisabled';
                        }
                    } )
                    ->searchable(),
                TextColumn::make('producto.precio_venta')
                    ->label('Precio Venta')
                    ->icon('heroicon-m-currency-dollar')
                    ->color(function (InventarioSucursal $record) { 
                        if($record->accepted_at !== null){
                            return 'success';
                        }else{
                            return 'colorDisabled';
                        }
                    } )
                    ->money('USD'),
                TextColumn::make('cantidad')
                    ->label('Exitencia actual')
                    ->icon('heroicon-c-rectangle-stack')
                    ->color(function (InventarioSucursal $record) { 
                        if($record->accepted_at !== null){
                            return 'primary';
                        }else{
                            return 'colorDisabled';
                        }
                    } ),
                TextInputColumn::make('pre_compra')
                    ->disabled(function (InventarioSucursal $record) { 
                        if($record->accepted_at !== null){
                            return false;
                        }else{
                            return true;
                        }
                    })
                    ->label('Cantidad'),
                // SelectColumn::make('cod_asignacion')
                //     ->label('Codigo de Servicio')
                //     ->options(Disponible::where('status', 'activo')->pluck('cod_asignacion', 'cod_asignacion'))
            ])
            ->filters([
                //
            ])
            ->actions([
                Action::make('Añadir')
                ->disabled(function (InventarioSucursal $record) {    
                    if($record->accepted_at !== null){
                        return false;
                    }else{
                        return true;
                    }
                })
                ->requiresConfirmation()
                ->action(function (InventarioSucursal $record) {

                    $tasa = TasaBcv::all()->first()->tasa;

                    if($record->cantidad > 0 && $record->pre_compra < $record->cantidad)
                    {
                        if($record->cod_asignacion != 0)
                        {
                            $info = Disponible::where('cod_asignacion', $record->cod_asignacion)->where('status', 'activo')->first();
                            $detalle_asignacion = new DetalleAsignacion();
                            $detalle_asignacion->cod_asignacion     = $record->cod_asignacion;
                            $detalle_asignacion->cod_prod_serv      = $record->producto->cod_producto;
                            $detalle_asignacion->empleado_id        = $info->empleado_id;
                            $detalle_asignacion->cliente_id         = $info->cliente_id;
                            $detalle_asignacion->producto_id        = $record->producto->id;
                            $detalle_asignacion->costo              = $record->producto->precio_venta;
                            $detalle_asignacion->fecha              = date('d-m-Y');
                            $detalle_asignacion->responsable        = Auth::user()->name;
                            $detalle_asignacion->sucursal_id        = Auth::user()->sucursal_id;
                            $detalle_asignacion->save();

                            InventarioSucursal::where('id', $record->id)
                            ->update([
                                'pre_compra'     => 0,
                                'cod_asignacion' => 0,
                            ]);

                            Notification::make()
                            ->title('El producto fue asociado al servicio con exito.')
                            ->icon('heroicon-o-document-text')
                            ->iconColor('success')
                            ->color('colorTree')
                            ->send();


                        }else{
                            $preCompra = new CarProducto();
                            $preCompra->cod_prod = $record->producto->cod_producto;
                            $preCompra->precio_venta = $record->producto->precio_venta;
                            $preCompra->cantidad = $record->pre_compra;
                            $preCompra->total_compra_usd = $record->pre_compra * $record->producto->precio_venta;
                            $preCompra->total_compra_bsd = ($record->pre_compra * $record->producto->precio_venta) * $tasa;
                            $preCompra->sucursal_id = Auth::user()->sucursal_id;
                            $preCompra->save();
                        }

                    }else{
                        Notification::make()
                        ->title('La carga debe ser mayor a uno(1) ó la cantidad solicitada es mayor a la existencia. Por favor vuelva a intentar.')
                        ->icon('heroicon-o-document-text')
                        ->iconColor('danger')
                        ->send();
                    }

                    InventarioSucursal::where('id', $record->id)
                    ->update([
                        'pre_compra'     => 0,
                    ]);

                    $this->dispatch('add-item-car');

                })
                ->icon('heroicon-m-shopping-cart')
                ->color(function (InventarioSucursal $record) { 
                    if($record->accepted_at !== null){
                        return 'success';
                    }else{
                        return 'colorDisabled';
                    }
                } )
                ->modalIcon('heroicon-m-shopping-cart')
                ->modalHeading('Añadir Item')
                ->modalDescription('Estas seguro que desea añadir el item a pre-facturacion')
                ->modalSubmitActionLabel('Si, agregar item!')
                //
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    //
                ]),
            ])
            ->headerActions([
                // Action::make('aceptar') 
                // ->requiresConfirmation()
                // ->label('Aceptacion de Inventario')
                // ->icon('heroicon-c-document-plus')
                // ->color('success')
                // ->model(InventarioSucursal::class)
                // ->action(function (array $data) {
                //     $array = InventarioSucursal::where('sucursal_id', Auth::user()->sucursal_id)
                //     ->where('uso', 'venta')
                //     ->where('accepted_at', null)
                //     ->get();

                //     foreach($array as $item)
                //     {
                //         $item->accepted_at = Carbon::now();
                //         $item->aceptado_por = Auth::user()->name;
                //         $item->save();
                //     }

                //     $descripcion = 'El usuario '. Auth::user()->name .' acepto inventario';
                //     LogController::log(Auth::user()->id, 'Aceptacion de inventario', $descripcion);

                // })
            ])
            ->striped()
            ->defaultPaginationPageOption(5);
    }

    public function render(): View
    {
        return view('livewire.table-producto');
    }
}
