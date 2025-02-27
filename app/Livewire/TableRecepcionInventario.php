<?php

namespace App\Livewire;

use id;
use Carbon\Carbon;
use App\Models\User;
use Filament\Tables;
use Livewire\Component;
use Filament\Tables\Table;
use App\Models\InventarioSucursal;
use App\Models\RecepcionInventario;
use Filament\Forms\Components\Grid;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\Filter;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Contracts\HasForms;
use App\Http\Controllers\LogController;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use App\Models\MovimientoInventarioSucursal;
use Illuminate\Database\Eloquent\Collection;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;
use App\Http\Controllers\InventarioSucursalController;
use App\Http\Controllers\MovimientoInventarioSucursalController;

class TableRecepcionInventario extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        return $table
            ->heading('RECEPCIÓN DE INVENTARIO')
            ->description('Recepción de inventario asignado tanto para ventas como consumo interno')
            ->query(RecepcionInventario::query()
            ->where('sucursal_id', Auth::user()->sucursal_id)
            ->orderBy('created_at', 'desc'))
            ->columns([
                Tables\Columns\TextColumn::make('producto.descripcion')
                    ->numeric()
                    ->color(function (RecepcionInventario $record) {
                        if($record->accepted_at !== null){
                            return 'colorDisabled';
                        }else{
                            return 'success';
                        }
                    })
                    ->icon(function (RecepcionInventario $record) {
                        if($record->accepted_at !== null){
                            return 'heroicon-s-document-check';
                        }else{
                            return 'heroicon-m-lock-closed';
                        }
                    }),

                Tables\Columns\TextColumn::make('uso')
                    ->icon('heroicon-s-megaphone')
                    ->color(function (RecepcionInventario $record) {
                        if($record->accepted_at !== null){
                            return 'colorDisabled';
                        }else{
                            return 'colorOne';
                        }
                    })
                    ->searchable(),

                Tables\Columns\TextColumn::make('cantidad')
                    ->label('Existencia')
                    ->color(function (RecepcionInventario $record) {
                        if($record->accepted_at !== null){
                            return 'colorDisabled';
                        }else{
                            return 'success';
                        }
                    })
                    ->icon('heroicon-c-rectangle-stack'),

                Tables\Columns\TextColumn::make('responsable')
                    ->label('Asignado por:')
                    ->color(function (RecepcionInventario $record) {
                        if($record->accepted_at !== null){
                            return 'colorDisabled';
                        }else{
                            return 'success';
                        }
                    })
                    ->icon('heroicon-c-user-circle'),

                Tables\Columns\TextColumn::make('user_accepted')
                    ->label('Aceptado por:')
                    ->color(function (RecepcionInventario $record) {
                        if($record->accepted_at !== null){
                            return 'colorDisabled';
                        }else{
                            return 'success';
                        }
                    })
                    ->icon('heroicon-c-user-circle'),

                Tables\Columns\TextColumn::make('accepted_at')
                    ->label('Aceptado el:')
                    ->color(function (RecepcionInventario $record) {
                        if($record->accepted_at !== null){
                            return 'colorDisabled';
                        }else{
                            return 'colorTree';
                        }
                    })
                    ->date()
                    ->icon('heroicon-c-calendar-days'),
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
            ->bulkActions([
                BulkAction::make('aceptar')
                ->requiresConfirmation()
                ->deselectRecordsAfterCompletion()
                ->label('Aceptación de Inventario')
                ->icon('heroicon-c-cog-8-tooth')
                ->color('success')
                ->action(function (Collection $records) {
                    // dd($records);
                    try {

                        $records = $records->toArray();
                        for ($i = 0; $i < count($records); $i++) {

                            $producto_aceptado = RecepcionInventario::where('id', $records[$i]['id'])->first();

                            if ($records[$i]['status'] == 1) {

                                $producto_existe = InventarioSucursal::where('producto_id', $records[$i]['producto_id'])
                                    ->where('sucursal_id', auth()->user()->sucursal_id)
                                    ->first();

                                if (isset($producto_existe)) {
                                    $producto_existe->cantidad += $records[$i]['cantidad'];
                                    $producto_existe->accepted_at = now();
                                    $producto_existe->save();
                                } else {

                                    InventarioSucursal::create([
                                        'producto_id'   => $records[$i]['producto_id'],
                                        'sucursal_id'   => auth()->user()->sucursal_id,
                                        'cantidad'      => $records[$i]['cantidad'],
                                        'responsable'   => auth()->user()->name,
                                        'uso'           => $records[$i]['uso'],
                                        'aceptado_por'  => auth()->user()->name,
                                    ]);
                                }

                                $producto_aceptado->accepted_at = now();
                                $producto_aceptado->user_accepted = auth()->user()->name;
                                $producto_aceptado->status = 2;
                                $producto_aceptado->save();

                                //creamos la entrada en la tabla de movimiento_inventario_sucursal
                                MovimientoInventarioSucursalController::crear_movimiento_inventario_sucursal($records[$i]['producto_id'], $records[$i]['cantidad'], 'entrada', 'recepcion-de-inventario');

                                Notification::make()
                                    ->title('NOTIFICACIÓN')
                                    ->icon('heroicon-o-document-text')
                                    ->iconColor('success')
                                    ->color('dangersuccess')
                                    ->body('El producto ha sido aceptado con exito')
                                    ->send();

                            } else {
                                return Notification::make()
                                    ->title('NOTIFICACIÓN')
                                    ->icon('heroicon-o-document-text')
                                    ->iconColor('danger')
                                    ->color('danger')
                                    ->body('Debe seleccionar productos que no estén previamente aceptados. Por favor verifica la selección y vuelve a intentar')
                                    ->send();
                            }
                        }

                    } catch (\Throwable $th) {
                        LogController::log(Auth::user()->id, 'excepcion', $th->getMessage(), $response = null);
                        Notification::make()
                        ->title('NOTIFICACIÓN')
                        ->icon('heroicon-c-x-circle')
                        ->color('danger')
                        ->iconColor('danger')
                        ->body($th->getMessage())
                        ->send();
                    }

                }),
            ])->striped();
    }

    public function render(): View
    {
        return view('livewire.table-recepcion-inventario');
    }
}