<?php

namespace App\Filament\Resources;

use Carbon\Carbon;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Requisicion;
use Filament\Resources\Resource;
use App\Models\DetalleRequisicion;
use App\Models\InventarioSucursal;
use Illuminate\Support\Collection;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\Filter;
use Illuminate\Contracts\View\View;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Controllers\RequisicionController;
use App\Filament\Resources\RequisicionResource\Pages;
use App\Filament\Resources\RequisicionResource\RelationManagers\DetalleRequisicionRelationManager;

class RequisicionResource extends Resource
{
    protected static ?string $model = Requisicion::class;

    protected static ?string $navigationIcon = 'heroicon-c-truck';

    protected static ?string $navigationGroup = 'Modulo de Inventario';

    protected static ?string $navigationLabel = 'Requisiciones';

    protected static ?int $navigationSort = 10;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status_id', 5)->get()->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return static::getModel()::where('status_id', 5)->get()->count() > 10 ? 'warning' : 'success';
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(Requisicion::query()->orderBy('created_at', 'desc'))
            ->columns([
                Tables\Columns\TextColumn::make('codigo')
                    ->icon('heroicon-c-cog-8-tooth')
                    ->searchable(),
                Tables\Columns\TextColumn::make('sucursal.nombre')
                    ->icon('heroicon-s-building-storefront')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('fecha')
                    ->icon('heroicon-c-calendar-days')
                    ->date()
                    ->searchable(),
                Tables\Columns\TextColumn::make('status.descripcion')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Abierta' => 'success',
                        'Cerrada' => 'danger',
                    })
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->icon('heroicon-c-user-circle')
                    ->numeric()
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
                SelectFilter::make('sucursal')
                    ->relationship('sucursal', 'nombre')
                    ->attribute('sucursal_id'),
            ])
            ->filtersTriggerAction(
                fn(Action $action) => $action
                    ->button()
                    ->label('Filtros'),
            )
            ->actions([

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('auditar')
                    ->label('Auditar Requisiciones')
                    ->icon('heroicon-s-exclamation-triangle')
                    ->color('colorDanger')
                    ->action(function (Collection $records) {

                       $auditoria = RequisicionController::auditoria($records);
                        
                    }),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            DetalleRequisicionRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRequisicions::route('/'),
            'create' => Pages\CreateRequisicion::route('/create'),
            'edit' => Pages\EditRequisicion::route('/{record}/edit'),
        ];
    }
}