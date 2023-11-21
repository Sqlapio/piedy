<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PromocionResource\Pages;
use App\Filament\Resources\PromocionResource\RelationManagers;
use App\Models\Promocion;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PromocionResource extends Resource
{
    protected static ?string $model = Promocion::class;

    protected static ?string $navigationLabel = 'Promociones';

    protected static ?string $navigationGroup = 'Tienda Sambil';

    protected static ?string $navigationIcon = 'heroicon-m-receipt-percent';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('cod_promocion')->default('Ppromo-'.random_int(11111, 99999)),
                TextInput::make('descripcion')->required(),
                TextInput::make('costo')
                    ->prefix('$')
                    ->numeric()
                    ->inputMode('decimal'),
                Select::make('tipo')
                    ->options([
                        'precio_especial' => 'Precio especial',
                        'cupones' => 'Cupones',
                    ]),
                Select::make('status')
                    ->options([
                        'activo' => 'Activo',
                        'inactivo' => 'Inactivo',
                    ]),
                FileUpload::make('image')
                ->imageEditor()
                ->imageEditorAspectRatios([
                    '16:9',
                    '4:3',
                    '1:1',
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('cod_promocion')
                ->label(__('Codigo de promoción'))
                ->searchable(),
                ImageColumn::make('image')
                ->width(100)
                ->height(100),
                TextColumn::make('descripcion')->searchable(),
                TextColumn::make('costo')->searchable(),
                TextColumn::make('tipo')->searchable(),
                IconColumn::make('status')
                ->options([
                    'heroicon-s-check-circle' => fn ($state, $record): bool => $record->status === 'activo',
                    'heroicon-m-minus-circle' => fn ($state, $record): bool => $record->status === 'inactivo',
                ])
                ->colors([
                    'danger' => 'inactivo',
                    'success' => 'activo',
                ]),
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
            'index' => Pages\ListPromocions::route('/'),
            'create' => Pages\CreatePromocion::route('/create'),
            'edit' => Pages\EditPromocion::route('/{record}/edit'),
        ];
    }
}
