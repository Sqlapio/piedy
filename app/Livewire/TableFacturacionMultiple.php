<?php

namespace App\Livewire;

use App\Models\Disponible;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Filament\Tables\Actions\BulkAction;
use Illuminate\Database\Eloquent\Collection;

class TableFacturacionMultiple extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        return $table
            ->heading('Servicios por Facturar')
            ->description('Tabla de servicios cerrados listo para facturar')
            ->query(Disponible::query()->where('sucursal_id', Auth::user()->sucursal_id)->where('status', 'cerrado'))
            ->columns([
                Tables\Columns\TextColumn::make('cod_asignacion')
                ->label('Codigo de Asigancion')
                ->icon('heroicon-o-hashtag')
                ->color('colorOne')
                ->searchable(),
                Tables\Columns\TextColumn::make('cliente.nombre')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('empleado.name')
                    ->numeric()
                    ->sortable(),
                // Tables\Columns\TextColumn::make('cod_prod_serv')
                //     ->searchable(),
                // Tables\Columns\TextColumn::make('servicio.descripcion')
                //     ->numeric()
                //     ->sortable(),
                // Tables\Columns\TextColumn::make('status')
                //     ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                // Tables\Columns\TextColumn::make('sucursal_id')
                //     ->searchable(),
                Tables\Columns\TextColumn::make('acu_servicios')
                    ->label('Total Servicios($)')
                    ->color('colorTree')
                    ->money('USD')
                    ->sortable(),
                Tables\Columns\TextColumn::make('acu_productos')
                    ->label('Total Productos($)')
                    ->color('colorOne')
                    ->money('USD')
                    ->sortable(),
                Tables\Columns\TextColumn::make('venta_total')
                ->label('Total a Pagar')
                ->icon('heroicon-c-currency-dollar')
                ->color('success')
                    ->money('USD')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                //
            ])
            ->bulkActions([
                BulkAction::make('facturar')
                ->color('success')
                ->requiresConfirmation()
                ->action(function (Collection $records) {
                    dd($records[0]->cod_asignacion);
                })
            ]);
    }

    public function render(): View
    {
        return view('livewire.table-facturacion-multiple');
    }
}
