<?php

namespace App\Livewire;

use Filament\Tables;
use Livewire\Component;
use App\Models\Producto;
use App\Models\Servicio;
use Filament\Tables\Table;
use App\Models\DetalleAsignacion;
use Illuminate\Contracts\View\View;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;

class TableExternaDetalleAsignacion extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public $cod_asignacion;
    public $cliente_id;

    public function mount($cod_asignacion, $cliente_id)
    {
        $this->cod_asignacion = $cod_asignacion;
        $this->cliente_id = $cliente_id;
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading('Servicios y Productos facturados')
            ->description('Codigo de Factura: '.$this->cod_asignacion)
            ->query(DetalleAsignacion::query()
                ->where('cod_asignacion', $this->cod_asignacion)
                ->where('status', 2))
            ->columns([
                Tables\Columns\TextColumn::make('tipo')
                    ->label('Tipo de Asiganción')
                    ->description(fn(DetalleAsignacion $record): string => ($record->servicio_id != null) ? Servicio::find($record->servicio_id)->descripcion : Producto::find($record->producto_id)->descripcion)
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'servicio' => 'success',
                        'producto' => 'warning',
                    })
                    ->icon(fn(string $state): string => match ($state) {
                        'servicio' => 'heroicon-o-swatch',
                        'producto' => 'heroicon-o-shopping-cart',
                    })
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
        return view('livewire.table-externa-detalle-asignacion');
    }
}