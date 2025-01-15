<?php

namespace App\Filament\Supervisor\Resources;

use App\Filament\Supervisor\Resources\RequisicionResource\Pages;
use App\Filament\Supervisor\Resources\RequisicionResource\RelationManagers;
use App\Models\Requisicion;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RequisicionResource extends Resource
{
    protected static ?string $model = Requisicion::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('codigo')
                    ->required()
                    ->default('REQ-' . rand(111111, 999999))
                    ->unique()
                    ->readonly()
                    ->maxLength(255),
                Forms\Components\TextInput::make('sucursal_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('fecha')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('status')
                    ->required()
                    ->numeric()
                    ->default(1),
                Forms\Components\TextInput::make('user_id')
                    ->required()
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('codigo')
                    ->searchable(),
                Tables\Columns\TextColumn::make('sucursal.nombre')
                    ->numeric()
                    ->color('colorTree')
                    ->sortable(),
                Tables\Columns\TextColumn::make('fecha')
                    ->searchable(),
                Tables\Columns\TextColumn::make('statusRequisicion.descripcion')
                    ->badge()
                    ->color(function (string $state): string {
                        if ($state === 'Creada') {
                            return 'success';
                        }
                        if ($state === 'En Proceso') {
                            return 'info';
                        }
                        if ($state === 'Finalizada') {
                            return 'colorTree';
                        }
                        if ($state === 'Cancelada') {
                            return 'danger';
                        }
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
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
            'index' => Pages\ListRequisicions::route('/'),
            'create' => Pages\CreateRequisicion::route('/create'),
            'edit' => Pages\EditRequisicion::route('/{record}/edit'),
        ];
    }
}
