<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CierreDiarioResource\Pages;
use App\Models\CierreDiario;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Columns\Summarizers\Sum;

class CierreDiarioResource extends Resource
{
    protected static ?string $model = CierreDiario::class;

    protected static ?string $navigationIcon = 'heroicon-s-chart-pie';

    protected static ?string $navigationLabel = 'Cierre diario';

    protected static ?string $navigationGroup = 'Contabilidad';

    protected static ?int $navigationSort = 3;

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
        ->columns([

            TextColumn::make('sucursal.nombre')
                ->icon('heroicon-s-home')
                ->color('colorTree')
                ->sortable()
                ->searchable(),
            
            TextColumn::make('total_dolares_efectivo')
                ->money('USD')
                ->icon('heroicon-m-currency-dollar')
                ->color('success')
                ->label('Efectivo($)')
                ->sortable()
                ->searchable(),

            TextColumn::make('total_dolares_zelle')
                ->money('USD')
                ->icon('heroicon-m-credit-card')
                ->color('success')
                ->label('Zelle($)')
                ->sortable()
                ->searchable(),

            TextColumn::make('total_bolivares')
                ->label('Bolivares(Bs)')
                ->icon('heroicon-m-credit-card')
                ->color('info')
                ->money('VES')
                ->sortable()
                ->searchable(),
            
            Tables\Columns\TextColumn::make('monto_ref_debito')
                ->color('info')
                ->description(fn (CierreDiario $record): string => 'Lote: '.$record->ref_debito)
                ->label('Monto Debito')
                ->money('VES')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),

            Tables\Columns\TextColumn::make('monto_ref_credito')
                ->color('info')
                ->description(fn (CierreDiario $record): string => 'Lote: '.$record->ref_credito)
                ->label('Monto Crédito')
                ->money('VES')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),

            Tables\Columns\TextColumn::make('monto_ref_visaMaster')
                ->color('info')
                ->description(fn (CierreDiario $record): string => 'Lote: '.$record->ref_visaMaster)
                ->label('Monto Debito')
                ->money('VES')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),

            TextColumn::make('created_at')
                ->label('Fecha de cierre')
                ->icon('heroicon-s-calendar-days')
                ->color('colorTree')
                ->sortable()
                ->searchable(),

            Tables\Columns\TextColumn::make('total_cierre_usd')
                ->color('success')
                ->label('Total del Dia($)')
                ->summarize(Sum::make()
                        ->money('USD')
                        ->label('Total($)'))
                    ->searchable()
                ->sortable(),

            Tables\Columns\TextColumn::make('total_cierre_bsd')
                ->color('success')
                ->money('VES')
                ->label('Total del Dia(Bs.)')
                ->summarize(Sum::make()
                        ->money('VES')
                        ->label('Total($)'))
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


        ])
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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