<?php

namespace App\Filament\Resources;

use Carbon\Carbon;
use Filament\Forms;
use App\Models\User;
use Filament\Tables;
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
use App\Filament\Resources\PreNominaResource\RelationManagers;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

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
            ->query(PreNomina::query())
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

                Tables\Columns\TextColumn::make('propinas_usd')
                    ->label('Propina(USD)')
                    ->sortable(),

                Tables\Columns\TextColumn::make('propinas_bsd')
                    ->label('Propina(Bs.)')
                    ->sortable(),

                Tables\Columns\TextColumn::make('asignaciones_usd')
                    ->label('Asignaciones(USD)')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('asignaciones_bsd')
                    ->label('Asignaciones(Bs.)')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('deducciones_usd')
                    ->label('Deducciones(USD)')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('deducciones_bsd')
                    ->label('Deducciones(Bs.)')
                    ->sortable()
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
                Tables\Actions\Action::make('generar-pdf')
                    ->label('PDF')
                    ->icon('heroicon-c-arrow-down-tray')
                    ->color('danger')
                    ->hidden(function ($record) {
                        if ($record->status == 2) {
                            return false;
                        } else {
                            return true;
                        }
                    })
                    ->action(function (PreNomina $record) {
                        try {
                            $reporte = PreNominaController::reporteNomina($record);
                            if ($reporte) {
                                Notification::make()
                                    ->title('NOTIFICACIÓN')
                                    ->icon('heroicon-o-document-text')
                                    ->iconColor('success')
                                    ->color('success')
                                    ->body('El reporte de: ' . $record->user->name . ' ha sido generado exitosamente')
                                    ->send();
                            }
                        } catch (\Throwable $th) {
                            LogController::log(Auth::user()->id, 'excepcion: reporte de nomina', $th->getMessage(), $response = null);
                            Notification::make()
                                ->title('NOTIFICACIÓN')
                                ->icon('heroicon-o-shield-check')
                                ->iconColor('danger')
                                ->color('danger')
                                ->body($th->getMessage())
                                ->send();
                        }
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    ExportBulkAction::make()
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