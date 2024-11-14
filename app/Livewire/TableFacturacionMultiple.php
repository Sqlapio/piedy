<?php

namespace App\Livewire;

use App\Http\Controllers\FacturacionMultipleController;
use App\Models\Disponible;
use Filament\Tables\Actions\BulkAction;
use Illuminate\Database\Eloquent\Collection;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;

class TableFacturacionMultiple extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    #[On('delete-item')]
    public function truncateItems()
    {
        $this->reset();
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading('SERVICIOS POR FACTURAR')
            ->description('Tabla de servicios cerrados listo para facturar')
            ->query(Disponible::query()
            ->where('sucursal_id', Auth::user()->sucursal_id)
            ->where('status', 'cerrado')
            ->where('status_fac_multiple', 1))
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
                ->label('Facturar')
                ->icon('heroicon-c-cog-8-tooth')
                ->color('success')
                ->action(function (Collection $records) {
                    $res = FacturacionMultipleController::totalizar_fac_multiple($records);
                    if($res){
                        $this->redirectRoute('facturar_cliente');
                    }
                }),
            ]);
    }

    public function render(): View
    {
        return view('livewire.table-facturacion-multiple');
    }
}
