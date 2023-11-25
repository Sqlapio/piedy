<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PromocionResource\Pages;
use App\Filament\Resources\PromocionResource\RelationManagers;
use App\Models\Promocion;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
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

    protected static ?string $navigationIcon = 'heroicon-m-bell-alert';

    protected static ?string $navigationGroup = 'Tienda Sambil';

    protected static ?string $navigationLabel = 'Promociones';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('cod_promocion')->default('Pcp-'.random_int(11111, 99999)),
                TextInput::make('descripcion')->required(),
                DatePicker::make('fecha_inicio')->required(),
                DatePicker::make('fecha_fin')->required(),
                Select::make('tipo')
                    ->options([
                        'cupones' => 'Cupones',
                        'descuento' => 'Descuento',
                        '2x1' => '2x1',
                    ])->required(),
                TextInput::make('porcentaje')
                    ->prefix('%')
                    ->numeric(),
                Select::make('status')
                    ->options([
                        'activo' => 'Activo',
                        'inactivo' => 'Inactivo',
                    ])->required(),
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
                TextColumn::make('descripcion')->searchable(),
                TextColumn::make('fecha_inicio')
                    ->label(('Inicia'))
                    ->searchable(),
                TextColumn::make('fecha_fin')
                    ->label(('Finaliza'))
                    ->searchable(),
                TextColumn::make('porcentaje')->searchable(),
                TextColumn::make('tipo')->searchable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'activo' => 'success',
                        'inactivo' => 'error',
                    })
                    ->searchable(),
                ImageColumn::make('image')
                    ->width(100)
                    ->height(100),
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
