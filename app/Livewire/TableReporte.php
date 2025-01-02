<?php

namespace App\Livewire;

use Carbon\Carbon;
use Filament\Tables;
use App\Models\Reporte;
use Livewire\Component;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Grouping\Group;
use Illuminate\Contracts\View\View;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;

class TableReporte extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        return $table
            ->heading('REPORTES DE NÓMINA')
            ->description('Tabla para reporte de nómina')
            ->query(Reporte::query()->orderBy('created_at', 'desc'))
            ->columns([
                Tables\Columns\TextColumn::make('cod_reporte')
                    ->label('Código de Reporte')
                    ->icon('heroicon-c-cog-6-tooth')
                    ->color('success')
                    ->searchable(),
                Tables\Columns\TextColumn::make('descripcion')
                    ->label('Archivo')
                    ->icon('heroicon-m-arrow-down-tray')
                    ->color('danger')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tipo')
                    ->label('Rol')
                    ->searchable(),
                Tables\Columns\TextColumn::make('responsable')
                    ->label('Responsable')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Empleado')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('fecha_ini')
                    ->label('Fecha Inicio')
                    ->icon('heroicon-m-calendar-days')
                    ->searchable(),
                Tables\Columns\TextColumn::make('fecha_fin')
                    ->label('Fecha Fin')
                    ->icon('heroicon-m-calendar-days')
                    ->searchable(),
                Tables\Columns\TextColumn::make('sucursal.nombre')
                    ->numeric()
                    ->sortable(),
            ])
            ->groups([
                Group::make('sucursal.nombre')->label('Sucursal'),
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
                Action::make('ver')
                ->label('Generar PDF')
                ->url(function(Reporte $record){
                    return url('/'. $record->descripcion);
                })
                ->color('danger')
            ->icon('heroicon-c-eye')
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
        return view('livewire.table-reporte');
    }
}
