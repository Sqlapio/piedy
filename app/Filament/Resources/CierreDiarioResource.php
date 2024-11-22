<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CierreDiarioResource\Pages;
use App\Filament\Resources\CierreDiarioResource\RelationManagers;
use App\Models\CierreDiario;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CierreDiarioResource extends Resource
{
    protected static ?string $model = CierreDiario::class;

    protected static ?string $navigationIcon = 'heroicon-s-chart-pie';

    protected static ?string $navigationLabel = 'Cierre diario';

    protected static ?string $navigationGroup = 'Administración';

    protected static ?int $navigationSort = 5;

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
            TextColumn::make('total_ventas')
                ->money('USD')
                ->label('Venta Total($)')
                ->sortable()
                ->searchable()
                ->toggleable(isToggledHiddenByDefault: true),

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

            TextColumn::make('ref_debito')
                ->label('Ref. Débito')
                ->sortable()
                ->searchable()
                ->toggleable(isToggledHiddenByDefault: true),

            TextColumn::make('ref_credito')
                ->label('Ref. Credito')
                ->sortable()
                ->searchable()
                ->toggleable(isToggledHiddenByDefault: true),

            TextColumn::make('ref_visaMaster')
                ->label('Ref. Visa/Master')
                ->sortable()
                ->searchable()
                ->toggleable(isToggledHiddenByDefault: true),

            TextColumn::make('created_at')
                ->label('Fecha de cierre')
                ->icon('heroicon-s-calendar-days')
                ->color('colorTree')
                ->sortable()
                ->searchable(),

            TextColumn::make('responsable')
                ->icon('heroicon-s-user')
                ->color('colorOne')
                ->sortable()
                ->searchable(),
            
            TextColumn::make('observaciones')
                ->icon('heroicon-s-user')
                ->color('colorOne')
                ->sortable()
                ->searchable(),

            TextColumn::make('sucursal.nombre')
                ->icon('heroicon-s-home')
                ->color('colorTree')
                ->sortable()
                ->searchable(),
        ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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