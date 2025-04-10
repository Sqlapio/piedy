<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Models\AuditoriaInventario;
use Filament\Forms\Components\Section;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\AuditoriaInventarioResource\Pages;
use App\Filament\Resources\AuditoriaInventarioResource\RelationManagers;
use App\Filament\Resources\AuditoriaInventarioResource\RelationManagers\DetalleProductosRelationManager;
use App\Filament\Resources\AuditoriaInventarioResource\RelationManagers\DetalleServiciosRelationManager;

class AuditoriaInventarioResource extends Resource
{
    protected static ?string $model = AuditoriaInventario::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Modulo de Inventario';

    protected static ?string $navigationLabel = 'Auditoria Inventario';

    protected static ?int $navigationSort = 11;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Auditoria de Inventario')
                    ->description('Informacion Auditoria de Inventario')
                    ->schema([
                        Forms\Components\TextInput::make('codigo_auditoria')
                            ->disabled()
                            ->dehydrated(),
                        Forms\Components\TextInput::make('desde')
                            ->disabled()
                            ->dehydrated(),
                        Forms\Components\TextInput::make('hasta')
                            ->disabled()
                            ->dehydrated(),
                        Forms\Components\TextInput::make('responsable')
                            ->disabled()
                            ->dehydrated(),
                    ])->columns(4)
                    
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('codigo_auditoria')
                    ->sortable(),
                Tables\Columns\TextColumn::make('requisiciones')
                    ->label('Requisiciones Auditadas')
                    ->alignCenter()
                    ->listWithLineBreaks(),
                Tables\Columns\TextColumn::make('desde')
                    ->searchable(),
                Tables\Columns\TextColumn::make('hasta')
                    ->searchable(),
                Tables\Columns\TextColumn::make('responsable')
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
            DetalleProductosRelationManager::class,
            DetalleServiciosRelationManager::class,
            
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAuditoriaInventarios::route('/'),
            'create' => Pages\CreateAuditoriaInventario::route('/create'),
            'edit' => Pages\EditAuditoriaInventario::route('/{record}/edit'),
        ];
    }
}