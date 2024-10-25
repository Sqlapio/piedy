<?php

namespace App\Livewire;

use App\Models\Producto;
use App\Models\CarProducto;
use App\Models\InventarioSucursal;
use App\Models\TasaBcv;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

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
            ->query(InventarioSucursal::query()->where('cantidad', '>', 0)->where('sucursal_id', $sucursal_id))
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
                    ->money('USD')
                    ->sortable(),
                TextInputColumn::make('pre_compra')
                    ->label('Cantidad'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Action::make('Añadir')
                ->requiresConfirmation()
                ->action(function (InventarioSucursal $record) {

                    $tasa = TasaBcv::all()->first()->tasa;

                    $preCompra = new CarProducto();
                    $preCompra->cod_producto = $record->producto->cod_producto;
                    $preCompra->precio_venta = $record->producto->precio_venta;
                    $preCompra->cantidad = $record->pre_compra;
                    $preCompra->total_compra_usd = $record->pre_compra * $record->producto->precio_venta;
                    $preCompra->total_compra_bsd = ($record->pre_compra * $record->producto->precio_venta) * $tasa;

                    if($preCompra->cantidad > 0){
                        $preCompra->save();

                    }else{
                        Notification::make()
                        ->title('La carga debe ser mayor a 1. Por favor vuelva a intentar.')
                        ->icon('heroicon-o-document-text')
                        ->iconColor('danger')
                        ->send();
                    }

                    InventarioSucursal::where('id', $record->id)
                    ->update([
                        'pre_compra' => 0,
                    ]);

                    $this->dispatch('add-item-car');

                })
                ->icon('heroicon-m-shopping-cart')
                ->color('success')
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
            ->striped()
            ->defaultPaginationPageOption(5);
    }

    public function render(): View
    {
        return view('livewire.table-producto');
    }
}
