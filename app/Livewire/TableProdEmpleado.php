<?php

namespace App\Livewire;

use Carbon\Carbon;
use Filament\Tables;
use Livewire\Component;
use Filament\Tables\Table;
use App\Models\VentaProducto;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\Filter;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;

class TableProdEmpleado extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public $empleado_id;
    public $sucursal_id;

    public function mount($empleado_id)
    {
        $this->empleado_id = $empleado_id;
        $this->sucursal_id = Auth::user()->sucursal_id;
    }


    public function table(Table $table): Table
    {
        return $table
            ->heading('PRODUCTOS VENDIDOS')
            ->description('Tecnico: ' . Auth::user()->name)
            ->query(VentaProducto::query()->where('empleado_id', $this->empleado_id)->where('sucursal_id', $this->sucursal_id))
            ->columns([
                Tables\Columns\TextColumn::make('cod_asignacion')
                    ->searchable(),
                Tables\Columns\TextColumn::make('empleado.name')
                    ->numeric(),
                Tables\Columns\TextColumn::make('cliente.nombre')
                    ->numeric(),
                Tables\Columns\TextColumn::make('producto.descripcion')
                    ->numeric(),
                Tables\Columns\TextColumn::make('cantidad')
                    ->numeric()
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('comision_empleado')
                    ->label('Comision en USD($)')
                    ->alignCenter()
                    ->money('USD')
                    ->summarize(Sum::make()
                        ->money('USD')
                        ->label('Total($)')),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
            ])
            ->filters([
                Filter::make('created_at')
                    ->form([
                        DatePicker::make('desde'),
                        DatePicker::make('hasta'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['desde'] ?? null,
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['hasta'] ?? null,
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['desde'] ?? null) {
                            $indicators['desde'] = 'Venta desde ' . Carbon::parse($data['desde'])->toFormattedDateString();
                        }
                        if ($data['hasta'] ?? null) {
                            $indicators['hasta'] = 'Venta hasta ' . Carbon::parse($data['hasta'])->toFormattedDateString();
                        }

                        return $indicators;
                    }),
            ])
            ->filtersTriggerAction(
                fn(Action $action) => $action
                    ->button()
                    ->label('Filtros'),
            )
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
        return view('livewire.table-prod-empleado');
    }
}