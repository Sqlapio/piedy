<?php

namespace App\Livewire;

use Carbon\Carbon;
use Filament\Tables;
use App\Models\TasaBcv;
use Filament\Forms\Get;
use Livewire\Component;
use App\Models\Disponible;
use Filament\Tables\Table;
use App\Models\CarProducto;
use Livewire\Attributes\On;
use App\Models\DetalleAsignacion;
use App\Models\InventarioSucursal;
use Filament\Tables\Actions\Action;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Contracts\HasForms;
use App\Http\Controllers\LogController;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;

class TableProducto extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    #[On('update-table-productos')]
    public function updateTable()
    {
        $this->reset();
    }

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
            ->where('sucursal_id', $sucursal_id))
            ->columns([
                ImageColumn::make('producto.image')
                    ->square()
                    ->size(100)
                    ->searchable(),
                TextColumn::make('producto.descripcion')
                    ->label('Descripción')
                    ->icon('heroicon-c-clipboard-document-check')
                    ->color('info')
                    ->searchable(),
                    
                TextColumn::make('producto.precio_venta')
                    ->label('Precio Venta')
                    ->icon('heroicon-m-currency-dollar')
                    ->color('success')
                    ->money('USD'),
                    
                TextColumn::make('cantidad')
                    ->label('Exitencia actual')
                    ->alignCenter()
                    ->icon('heroicon-m-square-3-stack-3d')
                    ->color(function(InventarioSucursal $record) {
                        if($record->cantidad <= $record->producto->existencia_min_sucursal){
                            return 'danger';
                        }else{
                            return 'success';
                        }
                    }),
                    
                TextInputColumn::make('pre_compra')
                    ->label('Cantidad'),

            ])
            ->filters([
                //
            ])
            ->actions([
                Action::make('Añadir')
                ->color('success')
                ->requiresConfirmation()
                ->action(function (InventarioSucursal $record) {
                    // dd($record);
                    $tasa = TasaBcv::all()->first()->tasa;

                    if($record->pre_compra <= 0)
                    {
                        Notification::make()
                        ->title('La carga debe ser mayor a uno(1) ó la cantidad solicitada es mayor a la existencia. Por favor vuelva a intentar.')
                        ->icon('heroicon-o-document-text')
                        ->iconColor('danger')
                        ->color('danger')
                        ->send();

                    }else {
                        $preCompra = new CarProducto();
                        $preCompra->cod_prod = $record->producto->cod_producto;
                        $preCompra->precio_venta = $record->producto->precio_venta;
                        $preCompra->cantidad = $record->pre_compra;
                        $preCompra->total_compra_usd = $record->pre_compra * $record->producto->precio_venta;
                        $preCompra->total_compra_bsd = ($record->pre_compra * $record->producto->precio_venta) * $tasa;
                        $preCompra->sucursal_id = Auth::user()->sucursal_id;
                        $preCompra->save();

                        //log
                        LogController::log(Auth::user()->id, 'add-item-car', "Se ha agregado un item a la carrito de compras", $response = null);
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
                //     LogController::log(Auth::user()->id, 'Aceptacion de inventario', $descripcion, $response = null);

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