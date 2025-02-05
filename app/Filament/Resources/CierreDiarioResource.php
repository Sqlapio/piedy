<?php

namespace App\Filament\Resources;

use Carbon\Carbon;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\CierreDiario;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\Summarizers\Sum;
use App\Filament\Resources\CierreDiarioResource\Pages;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class CierreDiarioResource extends Resource
{
    protected static ?string $model = CierreDiario::class;

    protected static ?string $navigationIcon = 'heroicon-s-chart-pie';

    protected static ?string $navigationLabel = 'Cierre Diario(Tienda)';

    protected static ?string $navigationGroup = 'Modulo Administrativo';

    protected static ?int $navigationSort = 9;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->query(CierreDiario::query()->orderBy('created_at', 'desc'))
        ->columns([

            TextColumn::make('sucursal.nombre')
                ->icon('heroicon-s-home')
                ->color('colorTree')
                ->sortable()
                ->searchable(),
            
            TextColumn::make('total_dolares_efectivo')
                ->numeric(decimalPlaces: 2, locale: 'es')
                ->icon('heroicon-m-currency-dollar')
                ->color('success')
                ->label('Efectivo($)')
                ->sortable()
                ->searchable(),

            TextColumn::make('total_dolares_zelle')
                ->numeric(decimalPlaces: 2, locale: 'es')
                ->icon('heroicon-m-credit-card')
                ->color('success')
                ->label('Zelle($)')
                ->sortable()
                ->searchable(),

            TextColumn::make('total_bolivares')
                ->label('Bolivares(Bs)')
                ->icon('heroicon-m-credit-card')
                ->color('info')
                ->numeric(decimalPlaces: 2, locale: 'es')
                ->sortable()
                ->searchable(),

            TextColumn::make('created_at')
                ->label('Fecha de cierre')
                ->icon('heroicon-s-calendar-days')
                ->color('colorTree')
                ->sortable()
                ->searchable(),

            Tables\Columns\TextColumn::make('total_cierre_usd')
                ->color('success')
                ->numeric(decimalPlaces: 2, locale: 'es')
                ->label('Total del Dia($)')
                ->summarize(Sum::make()
                        ->numeric(decimalPlaces: 2, locale: 'es')
                        ->label('Total($)'))
                    ->searchable()
                ->sortable(),

            Tables\Columns\TextColumn::make('total_pago_movil_bsd')
            ->color('info')
            ->numeric(decimalPlaces: 2, locale: 'es')
                ->label('Total Pago Movil')
                ->summarize(Sum::make()
                    ->numeric(decimalPlaces: 2, locale: 'es')
                    ->label('Total(Bs.)'))
                ->searchable()
                ->sortable(),

            Tables\Columns\TextColumn::make('total_punto_venta_bsd')
            ->color('info')
            ->numeric(decimalPlaces: 2, locale: 'es')
                ->label('Total Punto Venta')
                ->summarize(Sum::make()
                ->numeric(decimalPlaces: 2, locale: 'es')
                    ->label('Total(Bs.)'))
                ->searchable()
                ->sortable(),

            Tables\Columns\TextColumn::make('total_cierre_bsd')
                ->color('info')
                ->numeric(decimalPlaces: 2, locale: 'es')
                ->label('Total del Dia(Bs.)')
                ->summarize(Sum::make()
                ->numeric(decimalPlaces: 2, locale: 'es')
                        ->label('Total(Bs.)'))
                    ->searchable()
                ->sortable(),

            TextColumn::make('responsable')
                ->icon('heroicon-s-user')
                ->color('colorOne')
                ->sortable()
                ->searchable()
                ->toggleable(isToggledHiddenByDefault: true),
            
            TextColumn::make('observaciones')
                ->icon('heroicon-s-user')
                ->color('colorOne')
                ->sortable()
                ->searchable()
                ->toggleable(isToggledHiddenByDefault: true),

            // TextColumn::make('efectivo_usd_real')
            //     ->numeric(decimalPlaces: 0)
            //     ->icon('heroicon-m-currency-dollar')
            //     ->color('success')
            //     ->label('Efectivo($) Recibido')
            //     ->searchable(),

            // TextColumn::make('recibido_por')
            // ->label('Recibido por')
            //     ->icon('heroicon-s-user')
            //     ->color('colorOne')
            //     ->searchable(),

            // TextColumn::make('received_at')
            //     ->label('Fecha de Recepcion')
            //     ->icon('heroicon-s-user')
            //     ->color('colorOne')
            //     ->searchable(),

            // TextColumn::make('observ_recepcion')
            //     ->label('Observaciones de Recepcion')
            //     ->icon('heroicon-s-user')
            //     ->color('colorOne')
            //     ->searchable()


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
            SelectFilter::make('tienda')
            ->relationship('sucursal', 'nombre')
            ->attribute('sucursal_id')
            ])
            ->filtersTriggerAction(
                fn(Action $action) => $action
                    ->button()
                    ->label('Filtros'),
            )
            ->actions([
                // Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    ExportBulkAction::make()
                ]),
            ]);
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
            'index' => Pages\ListCierreDiarios::route('/'),
            'create' => Pages\CreateCierreDiario::route('/create'),
            'edit' => Pages\EditCierreDiario::route('/{record}/edit'),
        ];
    }
}