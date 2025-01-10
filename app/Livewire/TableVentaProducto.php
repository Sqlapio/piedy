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
use Filament\Tables\Columns\Summarizers\Sum;
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

                Tables\Columns\TextColumn::make('cliente.nombre')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('producto.descripcion')
                    ->label('Producto')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('costo_producto')
                    ->label('Costo')
                    ->money('USD')
                    ->sortable(),
                // Tables\Columns\TextColumn::make('fecha_venta')
                //     ->dateTime()
                //     ->searchable(),
                Tables\Columns\TextColumn::make('cantidad')
                    ->alignCenter()
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_venta')
                    ->label('Total Venta')
                    ->money('USD')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('metodoUsd')
                    ->label('Metodo de Pago')
                    ->description(fn(VentaProducto $record): string => $record->metodoBsd)
                    ->searchable(),
                Tables\Columns\TextColumn::make('montoUsd')
                    ->label('Pago($)')
                    ->money('USD')
                    ->summarize(Sum::make()
                        ->label(('Total'))
                        ->money('USD'))
                    ->alignCenter()
                    ->searchable(),
                Tables\Columns\TextColumn::make('montoBsd')
                    ->label('Pago(Bs.)')
                    ->numeric()
                    ->summarize(Sum::make()
                        ->label('Total'))
                    ->alignCenter()
                    ->searchable(),
                Tables\Columns\TextColumn::make('responsable')
                    ->searchable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

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
