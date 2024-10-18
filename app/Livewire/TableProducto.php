<?php

namespace App\Livewire;

use App\Models\Producto;
use App\Models\CarProducto;
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

class TableProducto extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        return $table
            ->heading('Inventario de Productos')
            ->description('Inventarios de productos para la venta')
            ->query(Producto::query()->where('existencia', '>', 0))
            ->columns([
                ImageColumn::make('image')
                    ->square()
                    ->size(100)
                    ->searchable(),
                TextColumn::make('descripcion')
                    ->label('Descripción')
                    ->icon('heroicon-c-clipboard-document-check')
                    ->color('info')
                    ->searchable(),
                TextColumn::make('precio_venta')
                    ->label('Precio Venta')
                    ->icon('heroicon-m-currency-dollar')
                    ->color('success')
                    ->money('USD')
                    ->sortable(),
                TextInputColumn::make('pre_seleccion')
                    ->label('Cantidad'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Action::make('Añadir')
                ->requiresConfirmation()
                ->action(function (Producto $record) {

                    $tasa = TasaBcv::all()->first()->tasa;

                    $preCompra = new CarProducto();
                    $preCompra->cod_producto = $record->cod_producto;
                    $preCompra->precio_venta = $record->precio_venta;
                    $preCompra->cantidad = $record->pre_seleccion;
                    $preCompra->total_compra_usd = $record->pre_seleccion * $record->precio_venta;
                    $preCompra->total_compra_bsd = ($record->pre_seleccion * $record->precio_venta) * $tasa;
                    $preCompra->save();

                    Producto::where('id', $record->id)
                    ->update([
                        'pre_seleccion' => 0,
                    ]);

                    $this->dispatch('add-item-car');

                })
                ->icon('heroicon-m-shopping-cart')
                ->color('success')
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
