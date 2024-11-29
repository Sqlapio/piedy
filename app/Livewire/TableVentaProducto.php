<?php

namespace App\Livewire;

use App\Models\VentaProducto;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;

class TableVentaProducto extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        return $table
            ->heading('VENTA DE PRODUCTOS')
            ->description('Tabla de ventas diarias. Las misma refleja ventas tanto de productos como servicios')
            ->query(VentaProducto::query())
            ->columns([
                Tables\Columns\TextColumn::make('cod_asignacion')
                    ->searchable(),
                Tables\Columns\TextColumn::make('gerente_id')
                    ->searchable(),
                Tables\Columns\TextColumn::make('producto.id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('costo_producto')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('comision_empleado')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('comision_gerente')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('fecha_venta')
                    ->searchable(),
                Tables\Columns\TextColumn::make('cantidad')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_venta')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('facturado')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('metodo_pago')
                    ->searchable(),
                Tables\Columns\TextColumn::make('metodoUsd')
                    ->searchable(),
                Tables\Columns\TextColumn::make('metodoBsd')
                    ->searchable(),
                Tables\Columns\TextColumn::make('responsable')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nroTarjeta')
                    ->searchable(),
                Tables\Columns\TextColumn::make('montoUsd')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('montoBsd')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('referenciaUsd')
                    ->searchable(),
                Tables\Columns\TextColumn::make('referenciaBsd')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('cliente_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('sucursal_id')
                    ->numeric()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                //
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    //
                ]),
            ]);
    }

    public function render(): View
    {
        return view('livewire.table-venta-producto');
    }
}