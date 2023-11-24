<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DisponibleResource\Pages;
use App\Filament\Resources\DisponibleResource\RelationManagers;
use App\Models\Disponible;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;

class DisponibleResource extends Resource
{
    protected static ?string $model = Disponible::class;

    protected static ?string $navigationIcon = 'heroicon-m-swatch';

    protected static ?string $navigationGroup = 'Facturación';

    protected static ?string $navigationLabel = 'Facturación Servícios';

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
                TextColumn::make('cod_asignacion')->searchable()->label('Código'),
                TextColumn::make('cliente')->searchable(),
                TextColumn::make('empleado')->searchable(),
                TextColumn::make('servicio')->searchable(),
                TextColumn::make('costo')->searchable()->label(__('Costo($)')),
             TextColumn::make('status')
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    'activo' => 'info',
                    'cerrado' => 'danger',
                    'por facturar' => 'warning',
                    'facturado' => 'success',
                }),
                TextColumn::make('created_at')
                    ->label(__('Fecha asignación'))
                    ->searchable(),
                TextColumn::make('updated_at')
                    ->label(__('Fecha Facturación'))
                    ->searchable(),
            ])->groups([
                   'cliente',
                   'empleado',
               ])
            ->filters([
                DateRangeFilter::make('created_at')
                ->timezone('America/Caracas'),
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
            'index' => Pages\ListDisponibles::route('/'),
            'create' => Pages\CreateDisponible::route('/create'),
            'edit' => Pages\EditDisponible::route('/{record}/edit'),
        ];
    }
}
