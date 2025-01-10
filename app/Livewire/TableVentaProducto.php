<?php

namespace App\Livewire;

use Carbon\Carbon;
use Filament\Tables;
use Livewire\Component;
use Filament\Tables\Table;
use App\Models\VentaProducto;
use Illuminate\Contracts\View\View;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;

class TableVentaProducto extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        return $table
            ->heading('VENTA DIARIA')
            ->description('Tabla de venta de productos')
            ->query(VentaProducto::query()->whereBetween('created_at', [now()->startOfDay(), now()->endOfDay()]))
            ->columns([
                Tables\Columns\TextColumn::make('cod_asignacion')
                    ->label('Codigo Asignacion')
                    ->searchable(),
                Tables\Columns\TextColumn::make('gerente_id')
                    ->label('Gerente')
                    ->searchable(),
                Tables\Columns\TextColumn::make('producto.descripcion')
                    ->label('Producto')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('costo_producto')
                    ->money('USD')
                    ->sortable(),
                Tables\Columns\TextColumn::make('fecha_venta')
                    ->dateTime()
                    ->searchable(),
                Tables\Columns\TextColumn::make('cantidad')
                    ->alignCenter()
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_venta')
                    ->label('Total Venta')
                    ->money('USD')
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
            ])
            ->striped();
    }

    public function render(): View
    {
        return view('livewire.table-venta-producto');
    }
}