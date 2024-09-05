<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CierreFinancieroResource\Pages;
use App\Models\CierreFinanciero;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;

class CierreFinancieroResource extends Resource
{
    protected static ?string $model = CierreFinanciero::class;

    protected static ?string $navigationIcon = 'heroicon-m-chart-bar-square';

    protected static ?string $navigationLabel = 'Dashboard Financiero';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('total_general_ventas')
                    ->required()
                    ->numeric()
                    ->default(0.00),
                Forms\Components\TextInput::make('total_ingreso_bolivares')
                    ->required()
                    ->numeric()
                    ->default(0.00),
                Forms\Components\TextInput::make('total_ingreso_dolares')
                    ->required()
                    ->numeric()
                    ->default(0.00),
                Forms\Components\TextInput::make('total_servicios')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('total_clientes_atendidos')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('total_membresias_vendidas')
                    ->required()
                    ->numeric()
                    ->default(0.00),
                Forms\Components\TextInput::make('total_gif_card_vendidas')
                    ->required()
                    ->numeric()
                    ->default(0.00),
                Forms\Components\TextInput::make('total_productos_vendidos')
                    ->required()
                    ->numeric()
                    ->default(0.00),
                Forms\Components\TextInput::make('total_costos_operativos')
                    ->required()
                    ->numeric()
                    ->default(0.00),
                Forms\Components\TextInput::make('total_general_comiciones')
                    ->required()
                    ->numeric()
                    ->default(0.00),
                Forms\Components\TextInput::make('total_comisiones_bolivares')
                    ->required()
                    ->numeric()
                    ->default(0.00),
                Forms\Components\TextInput::make('total_comisiones_dolares')
                    ->required()
                    ->numeric()
                    ->default(0.00),
                Forms\Components\TextInput::make('utilidad_real')
                    ->required()
                    ->numeric()
                    ->default(0.00),
                Forms\Components\TextInput::make('tasa_bcv')
                    ->required()
                    ->numeric()
                    ->default(0.00),
                Forms\Components\TextInput::make('fecha')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('codigo_quincena')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('mes')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('responsable')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('total_general_ventas')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_ingreso_bolivares')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_ingreso_dolares')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_servicios')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_clientes_atendidos')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_membresias_vendidas')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_gif_card_vendidas')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_productos_vendidos')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_costos_operativos')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_general_comiciones')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_comisiones_bolivares')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_comisiones_dolares')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('utilidad_real')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tasa_bcv')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('fecha')
                    ->searchable(),
                Tables\Columns\TextColumn::make('codigo_quincena')
                    ->searchable(),
                Tables\Columns\TextColumn::make('responsable')
                    ->searchable(),
                Tables\Columns\TextColumn::make('mes')
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
                SelectFilter::make('mes')
                ->options([
                    'Enero' => 'Enero',
                    'Febrero' => 'Febrero',
                    'Marzo' => 'Marzo',
                    'Abril' => 'Abril',
                    'Mayo' => 'Mayo',
                    'Junio' => 'Junio',
                    'Julio' => 'Julio',
                    'Agosto' => 'Agosto',
                    'Septiembre' => 'Septiembre',
                    'Octubre' => 'Octubre',
                    'Noviembre' => 'Noviembre',
                    'Diciembre' => 'Diciembre',
                ]),
                SelectFilter::make('numero_quincena')
                ->options([
                    '1' => 'Primera Quicena',
                    '2' => 'Segunda Quincena',
                ]),
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
            'index' => Pages\ListCierreFinancieros::route('/'),
            'create' => Pages\CreateCierreFinanciero::route('/create'),
            'edit' => Pages\EditCierreFinanciero::route('/{record}/edit'),
        ];
    }
}
