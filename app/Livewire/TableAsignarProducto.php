<?php

namespace App\Livewire;

use Carbon\Carbon;
use Filament\Tables;
use Livewire\Component;
use Filament\Tables\Table;
use App\Models\AsignarProducto;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\Filter;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;

class TableAsignarProducto extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        return $table
            ->heading('MATERIALES ASIGNADOS')
            ->description('Tabla de materiales asignados por técnico para uso diario')
            ->query(AsignarProducto::query()
            ->where('sucursal_id', Auth::user()->sucursal_id)
            ->orderBy('created_at', 'desc'))
            ->columns([
                Tables\Columns\TextColumn::make('producto.descripcion')
                    ->label('Producto')
                    ->icon('heroicon-m-document-check')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Técnico')
                    ->icon('heroicon-c-user-circle')
                    ->searchable(),
                Tables\Columns\TextColumn::make('cantidad')
                    ->label('Cantidad')
                    ->icon('heroicon-m-square-3-stack-3d')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('responsable')
                    ->label('Responsable')
                    ->icon('heroicon-c-user-circle')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Entregado el')
                    ->icon('heroicon-m-calendar-days')
                    ->dateTime()
                    ->sortable(),
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
                            fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                        )
                        ->when(
                            $data['hasta'] ?? null,
                            fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
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
                fn (Action $action) => $action
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
            ]);
    }

    public function render(): View
    {
        return view('livewire.table-asignar-producto');
    }
}