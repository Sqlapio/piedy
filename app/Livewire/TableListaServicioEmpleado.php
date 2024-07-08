<?php

namespace App\Livewire;

use App\Models\VentaServicio;
use Carbon\Carbon;
use Filament\Enums\ThemeMode;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Livewire\Component;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class TableListaServicioEmpleado extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public function table(Table $table): Table
    {

        return $table
            ->query(VentaServicio::query()->where('empleado_id', Auth::user()->id))
            ->columns([
                TextColumn::make('cliente')
                    ->label('Cliente')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('cod_asignacion')
                    ->label('Codigo')
                    ->alignCenter()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('duracion')
                    ->label('Duracion del servicio')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('comision_dolares')
                    ->label('Comision($)')
                        ->summarize(Sum::make()
                        ->label('Total($)'))
                    ->sortable()
                    ->alignCenter()
                    ->searchable(),
                TextColumn::make('comision_bolivares')
                    ->label('Comision(Bs.)')
                        ->summarize(Sum::make()
                        ->label('Total(Bs.)'))
                    ->sortable()
                    ->alignCenter()
                    ->searchable(),
                TextColumn::make('propina_bsd')
                    ->label('Propina(Bs.)')
                    ->sortable()
                        ->summarize(Sum::make()
                        ->label('Total(Bs.)'))
                    ->alignCenter()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Filter::make('created_at')
                ->form([
                    DatePicker::make('desde')->default(now()),
                    DatePicker::make('hasta')->default(now()),
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
            ])
            ->striped();
    }

    public function render(): View
    {
        return view('livewire.table-lista-servicio-empleado');
    }
}
