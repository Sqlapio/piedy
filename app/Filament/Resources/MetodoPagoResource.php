<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MetodoPagoResource\Pages;
use App\Filament\Resources\MetodoPagoResource\RelationManagers;
use App\Models\MetodoPago;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MetodoPagoResource extends Resource
{
    protected static ?string $model = MetodoPago::class;

    protected static ?string $navigationIcon = 'heroicon-m-banknotes';

    protected static ?string $navigationGroup = 'Administración';

    protected static ?string $navigationLabel = 'Métodos de pago';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('descripcion')->required(),
                Select::make('moneda')
                    ->options([
                        'usd' => 'Usd',
                        'bsd' => 'Bsd',
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->searchable(),
                TextColumn::make('descripcion')->searchable(),
                TextColumn::make('moneda')
                ->label('Tipo de moneda')
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    'usd' => 'success',
                    'bsd' => 'info',
                    'multiple' => 'warning',
                })
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
            'index' => Pages\ListMetodoPagos::route('/'),
            'create' => Pages\CreateMetodoPago::route('/create'),
            'edit' => Pages\EditMetodoPago::route('/{record}/edit'),
        ];
    }
}
