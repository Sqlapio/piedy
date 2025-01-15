<?php

namespace App\Filament\Resources;

use Carbon\Carbon;
use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use App\Models\Reporte;
use Filament\Forms\Form;
use App\Models\PreNomina;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Actions\ImportAction;
use Illuminate\Support\Collection;
use App\Models\ConfiguracionNomina;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Grouping\Group;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use App\Http\Controllers\LogController;
use Filament\Tables\Actions\BulkAction;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\Layout\Grid;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Actions\CreateAction;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Actions\BulkActionGroup;
use App\Http\Controllers\PreNominaController;
use App\Filament\Resources\PreNominaResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use App\Filament\Resources\PreNominaResource\RelationManagers;

class PreNominaResource extends Resource
{
    protected static ?string $model = PreNomina::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Administración';

    protected static ?string $navigationLabel = 'Nomina';

    public static function table(Table $table): Table
    {
        return $table
            ->heading('NOMINA')
            ->description('Tabla de nominas generales')
            ->query(PreNomina::query()->orderBy('created_at', 'desc'))
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->icon('heroicon-c-cog-8-tooth')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('rol.descripcion')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('sucursal.nombre')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('total_servicios')
                    ->label('Servicios')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('total_productos')
                    ->label('Productos')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('comision_usd')
                    ->label('Comision(USD)')
                    ->money('USD')
                    ->sortable(),

                Tables\Columns\TextColumn::make('comision_bsd')
                    ->label('Comision(BSD)')
                    ->money('Bs.')
                    ->sortable(),

                Tables\Columns\TextColumn::make('comision_prod')
                    ->label('Comision Productos')
                    ->money('USD')
                    ->sortable(),

                Tables\Columns\TextInputColumn::make('propinas_usd')
                    ->label('Propina(USD)')
                    ->sortable()
                    ->afterStateUpdated(function ($record, $state) {
                        $record->total_usd = $state + $record->total_usd;
                        $record->save();
                        //log
                        LogController::log(Auth::user()->id, 'update pre-nomina', 'agrego propina en dolares: ' . $state, $response = null);
                    })
                    ->disabled(function ($record) {
                        if ($record->status == 2) {
                            return true;
                        } else {
                            return false;
                        }
                    }),

                Tables\Columns\TextInputColumn::make('propinas_bsd')
                    ->label('Propina(Bs.)')
                    ->sortable()
                    ->afterStateUpdated(function ($record, $state) {
                        $record->total_bsd = $state + $record->total_bsd;
                        $record->save();
                        //log
                        LogController::log(Auth::user()->id, 'update pre-nomina', 'agrego propina en bolivares: ' . $state, $response = null);
                    })
                    ->disabled(function ($record) {
                        if ($record->status == 2) {
                            return true;
                        } else {
                            return false;
                        }
                    }),

                Tables\Columns\TextInputColumn::make('asignaciones_usd')
                    ->label('Asignaciones(USD)')
                    ->sortable()
                    ->afterStateUpdated(function ($record, $state) {
                        $record->total_usd = $state + $record->total_usd;
                        $record->save();
                        //log
                        LogController::log(Auth::user()->id, 'update pre-nomina', 'agrego asignaciones en dolares: ' . $state, $response = null);
                    })
                    ->disabled(function ($record) {
                        if ($record->status == 2) {
                            return true;
                        } else {
                            return false;
                        }
                    })
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextInputColumn::make('asignaciones_bsd')
                    ->label('Asignaciones(Bs.)')
                    ->afterStateUpdated(function ($record, $state) {
                        $record->total_bsd = $state + $record->total_bsd;
                        $record->save();
                        //log
                        LogController::log(Auth::user()->id, 'update pre-nomina', 'agrego asignaciones en bolivares: ' . $state, $response = null);
                    })
                    ->disabled(function ($record) {
                        if ($record->status == 2) {
                            return true;
                        } else {
                            return false;
                        }
                    })
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextInputColumn::make('deducciones_usd')
                    ->label('Deducciones(USD)')
                    ->sortable()
                    ->afterStateUpdated(function ($record, $state) {
                        $record->total_usd = $record->total_usd - $state;
                        $record->save();
                        //log
                        LogController::log(Auth::user()->id, 'update pre-nomina', 'agrego deducciones en dolares: ' . $state, $response = null);
                    })
                    ->disabled(function ($record) {
                        if ($record->status == 2) {
                            return true;
                        } else {
                            return false;
                        }
                    })
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextInputColumn::make('deducciones_bsd')
                    ->label('Deducciones(Bs.)')
                    ->sortable()
                    ->afterStateUpdated(function ($record, $state) {
                        $record->total_bsd = $record->total_bsd - $state;
                        $record->save();
                        //log
                        LogController::log(Auth::user()->id, 'update pre-nomina', 'agrego deducciones en bolivares: ' . $state, $response = null);
                    })
                    ->disabled(function ($record) {
                        if ($record->status == 2) {
                            return true;
                        } else {
                            return false;
                        }
                    })
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('fecha_ini')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('fecha_fin')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('total_usd')
                    ->label('Total(USD)')
                    ->money('USD')
                    ->sortable(),

                Tables\Columns\TextColumn::make('total_bsd')
                    ->label('Total(Bs.)')
                    ->money('Bs.')
                    ->sortable(),

                Tables\Columns\TextColumn::make('total_venta_sin_iva')
                    ->label('Venta sin IVA')
                    ->money('Bs.')
                    ->sortable(),


                Tables\Columns\TextColumn::make('iva')
                    ->label('IVA')
                    ->money('Bs.')
                    ->sortable(),


                Tables\Columns\TextColumn::make('retencion_isrl')
                    ->label('Retencion ISRL')
                    ->money('Bs.')
                    ->sortable(),


                Tables\Columns\TextColumn::make('total_pagar_bsd')
                    ->label('Total A Pagar(Bs.)')
                    ->money('Bs.')
                    ->sortable(),


                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->groups([
                Group::make('rol.descripcion')->label('Rol'),
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
                // Tables\Actions\Action::make('generar-pdf')
                // ->label('Generar PDF')
                // ->url(function (PreNomina $record) {
                //     $reporte = Reporte::where('cod_reporte', $record->cod_nomina)->first();
                //     return url('/' . $reporte->descripcion);
                // })
                // ->color('danger')
                // ->icon('heroicon-c-eye')
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    BulkAction::make('totalizar')
                        ->label('Totalizar Nómina')
                        ->color('success')
                        ->icon('heroicon-c-cog-8-tooth')
                        ->requiresConfirmation()
                        ->action(function (Collection $records) {

                            $parametros = ConfiguracionNomina::first();

                            foreach ($records as $item) {
                                $item->status = 2;
                                $item->total_venta_sin_iva = $item->total_bsd / $parametros->iva;
                                $item->iva = $item->total_bsd - $item->total_venta_sin_iva;
                                $item->retencion_isrl = $item->iva * $parametros->isrl;
                                $item->total_pagar_bsd = $item->total_bsd - $item->retencion_isrl;
                                $item->save();
                            }

                            //log
                            LogController::log(Auth::user()->id, 'cierre de nomina', 'totalizo nomina', $response = null);

                            // $this->resetTable();
                        })->deselectRecordsAfterCompletion(),

                    BulkAction::make('generar-pdf')
                        ->label('Generar PDFs')
                        ->color('danger')
                        ->icon('heroicon-c-arrow-down-tray')
                        ->requiresConfirmation()
                        ->action(function (Collection $records) {
                            try {
                                $reporte = PreNominaController::reporteMasivoNomina($records);
                                // $this->resetTable();
                            } catch (\Throwable $th) {
                                LogController::log(Auth::user()->id, 'excepcion: reporte masivo de nomina', $th->getMessage(), $response = null);
                                Notification::make()
                                    ->title('NOTIFICACIÓN')
                                    ->icon('heroicon-o-shield-check')
                                    ->iconColor('danger')
                                    ->color('danger')
                                    ->body($th->getMessage())
                                    ->send();
                            }
                        })->deselectRecordsAfterCompletion(),

                    BulkAction::make('delete')
                        ->label('Reversar Cálculo')
                        ->color('primary')
                        ->icon('heroicon-c-arrow-uturn-left')
                        ->requiresConfirmation()
                        ->action(function (Collection $records) {
                            $records->each->delete();
                            //log
                            LogController::log(Auth::user()->id, 'cierre de nomina', 'totalizo nomina', $response = null);

                            // $this->resetTable();
                        })->deselectRecordsAfterCompletion(),

                    ExportBulkAction::make()
                        ->label('Exportar Excel')
                ]),
            ])
            ->striped()
            ->defaultPaginationPageOption(15);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPreNominas::route('/'),
            'create' => Pages\CreatePreNomina::route('/create'),
            'edit' => Pages\EditPreNomina::route('/{record}/edit'),
        ];
    }
}