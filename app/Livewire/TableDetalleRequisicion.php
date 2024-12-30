<?php

namespace App\Livewire;

use App\Models\DetalleRequisicion;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;

class TableDetalleRequisicion extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public $codigo;
    public $sucursal_id;

    public function mount($codigo, $sucursal_id)
    {
        $this->codigo = $codigo;
        $this->sucursal_id = $sucursal_id;
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading('DETALLE DE REQUISICION')
            ->description('Requisicion Nro: ' . $this->codigo)
            ->query(DetalleRequisicion::query()
                ->where('codigo', $this->codigo)
                ->where('sucursal_id', $this->sucursal_id))
            ->columns([
                Tables\Columns\Layout\Stack::make([
                    Tables\Columns\TextColumn::make('producto.descripcion')
                    ->label('Producto'),
                        // ->description(function (DetalleRequisicion $record) {
                        //     $sucursal = $record->sucursal->nombre;
                        //     return $sucursal;
                        // }),
                    Tables\Columns\Layout\Stack::make([
                        Tables\Columns\TextColumn::make('cantidad')
                        ->label('Cantidad')
                        ->numeric()
                        ->sortable(),
                    ]),
                ])->space(3),
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
            ])->striped();
    }

    public function render(): View
    {
        return view('livewire.table-detalle-requisicion');
    }
}