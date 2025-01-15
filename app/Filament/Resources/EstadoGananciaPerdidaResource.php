<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EstadoGananciaPerdidaResource\Pages;
use App\Filament\Resources\EstadoGananciaPerdidaResource\RelationManagers;
use App\Models\EstadoGananciaPerdida;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EstadoGananciaPerdidaResource extends Resource
{
    protected static ?string $model = EstadoGananciaPerdida::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('ingresos_usd')
                    ->required()
                    ->numeric()
                    ->default(0.00),
                Forms\Components\TextInput::make('ingresos_bsd')
                    ->required()
                    ->numeric()
                    ->default(0.00),
                Forms\Components\TextInput::make('ingresos_totales_usd')
                    ->required()
                    ->numeric()
                    ->default(0.00),
                Forms\Components\TextInput::make('costos_directos_usd')
                    ->required()
                    ->numeric()
                    ->default(0.00),
                Forms\Components\TextInput::make('margen_bruto')
                    ->required()
                    ->numeric()
                    ->default(0.00),
                Forms\Components\TextInput::make('gastos_operativos')
                    ->required()
                    ->numeric()
                    ->default(0.00),
                Forms\Components\TextInput::make('gastos_no_operativos')
                    ->required()
                    ->numeric()
                    ->default(0.00),
                Forms\Components\TextInput::make('utilidad_operativa_usd')
                    ->required()
                    ->numeric()
                    ->default(0.00),
                Forms\Components\TextInput::make('utilidad_antes_impuestos')
                    ->required()
                    ->numeric()
                    ->default(0.00),
                Forms\Components\TextInput::make('impuesto')
                    ->required()
                    ->numeric()
                    ->default(0.00),
                Forms\Components\TextInput::make('utilidad_neta')
                    ->required()
                    ->numeric()
                    ->default(0.00),
                Forms\Components\TextInput::make('notas_explicitas')
                    ->maxLength(255),
                Forms\Components\TextInput::make('user_id')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('ingresos_usd')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('ingresos_bsd')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('ingresos_totales_usd')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('costos_directos_usd')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('margen_bruto')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('gastos_operativos')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('gastos_no_operativos')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('utilidad_operativa_usd')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('utilidad_antes_impuestos')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('impuesto')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('utilidad_neta')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('notas_explicitas')
                    ->searchable(),
                Tables\Columns\TextColumn::make('user_id')
                    ->searchable(),
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
            'index' => Pages\ListEstadoGananciaPerdidas::route('/'),
            'create' => Pages\CreateEstadoGananciaPerdida::route('/create'),
            'edit' => Pages\EditEstadoGananciaPerdida::route('/{record}/edit'),
        ];
    }
}
