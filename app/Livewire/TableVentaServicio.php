<?php

namespace App\Livewire;

use App\Models\VentaServicio;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;

class TableVentaServicio extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        return $table
            ->heading('VENTA DE SERVICIOS')
            ->description('Tabla de venta de servicios')
            ->query(VentaServicio::query()
            ->whereBetween('created_at', [now()->startOfDay(), now()->endOfDay()])
            ->orderBy('created_at', 'desc'))
            ->columns([
                Tables\Columns\TextColumn::make('cod_asignacion')
                    ->searchable(),
                Tables\Columns\TextColumn::make('cliente.nombre')                    
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('metodo_pago')
                    ->description(fn (VentaServicio $record): string => $record->metodo_pago_dos)
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha')
                    ->dateTime()
                    ->searchable(),
                Tables\Columns\TextColumn::make('total_USD')
                    ->label('Total Venta')
                    ->money('USD')
                    ->sortable(),
                Tables\Columns\TextColumn::make('pago_usd')
                    ->description(fn (VentaServicio $record): string => 'Bs.'.$record->pago_bsd)
                    ->money('USD')
                    ->sortable(),
                Tables\Columns\TextColumn::make('responsable_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('ref_zelle')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('ref_pago_movil')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('ref_debito_credito')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('nro_tarjeta')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('pro_ref_debito_credito')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('pro_nro_tarjeta')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('responsable_id')
                    ->searchable()
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
        return view('livewire.table-venta-servicio');
    }
}